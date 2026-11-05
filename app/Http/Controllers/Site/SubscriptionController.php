<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Stripe\PromotionCode;


class SubscriptionController extends Controller
{
    public function __construct()
    {
        
        Stripe::setApiKey(config('services.stripe.secret'));
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        $user = Auth::user();

        
        $priceId = $request->plan === 'monthly'
            ? config('services.stripe.price_monthly')
            : config('services.stripe.price_annual');

        
        if (!$user->stripe_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name'  => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            $user->stripe_id = $customer->id;
            $user->save();
        }

        
        $session = Session::create([
            'customer' => $user->stripe_id,
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'allow_promotion_codes' => true, 
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('subscription.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'plan'    => $request->plan,
            ],
        ]);

        
        return redirect($session->url);
    }

   
    public function success(Request $request)
    {
        return view('site.subscriptionsuccess');
    }


    public function cancel()
    {
        return redirect('/join-us')->with('error', 'Subscription checkout cancelled.');
    }

    // public function validateCoupon(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|string',
    //         'plan' => 'required|in:monthly,annual',
    //     ]);

    //     $inputCode = trim($request->input('code'));

    //     try {
            
    //         $promoCodes = PromotionCode::all([
    //             'code'   => $inputCode,
    //             'active' => true,
    //             'limit'  => 1,
    //             'expand' => ['data.coupon'],
    //         ]);

    //         if (empty($promoCodes->data)) {
    //             return response()->json([
    //                 'valid'   => false,
    //                 'message' => 'Invalid or expired coupon code.',
    //             ], 422);
    //         }

    //         $promo = $promoCodes->data[0];
    //         $coupon = $promo->coupon;

            
    //         if (is_string($coupon)) {
    //             $coupon = \Stripe\Coupon::retrieve($coupon);
    //         }

           
    //         if (!empty($coupon->valid) === false && isset($coupon->valid) && $coupon->valid === false) {
    //             return response()->json([
    //                 'valid'   => false,
    //                 'message' => 'This coupon is no longer valid.',
    //             ], 422);
    //         }

    //         $isAnnual = strtolower($request->input('plan')) === 'annual';
    //         $basePrice = $isAnnual ? 77.00 : 7.00;
    //         $discountAmount = 0.00;

    //         $percentOff = $coupon->percent_off ?? null;
    //         $amountOff  = $coupon->amount_off ?? null;

    //         if ($percentOff !== null && (float)$percentOff > 0) {
    //             $discountAmount = ($basePrice * (float)$percentOff) / 100;
    //         } elseif ($amountOff !== null && (float)$amountOff > 0) {
    //             $discountAmount = (float)$amountOff / 100; // Cents to Dollars
    //         }

    //         $newTotal = max(0, $basePrice - $discountAmount);

    //         return response()->json([
    //             'valid'           => true,
    //             'promo_id'        => $promo->id,
    //             'code'            => $promo->code,
    //             'percent_off'     => $percentOff,
    //             'amount_off'      => $amountOff ? ($amountOff / 100) : null,
    //             'discount_amount' => number_format($discountAmount, 2),
    //             'new_total'       => number_format($newTotal, 2),
    //             'message'         => 'Coupon applied successfully!',
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'valid'   => false,
    //             'message' => 'Error verifying coupon: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'plan' => 'required|in:monthly,annual',
        ]);

        $inputCode = strtoupper(trim($request->input('code')));

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            
            $promoCodes = \Stripe\PromotionCode::all([
                'code'   => $inputCode,
                'active' => true,
                'limit'  => 1,
            ]);

            $promo = $promoCodes->data[0] ?? null;

            if (!$promo) {
                $allPromos = \Stripe\PromotionCode::all([
                    'active' => true,
                    'limit'  => 30,
                ]);

                foreach ($allPromos->data as $item) {
                    if (strcasecmp($item->code, $inputCode) === 0) {
                        $promo = $item;
                        break;
                    }
                }
            }

            if (!$promo) {
                return response()->json([
                    'valid'   => false,
                    'message' => 'Invalid or expired coupon code.',
                ], 422);
            }

            
            $promoArr = $promo->toArray();

            
            $couponId = null;

            if (isset($promoArr['promotion']['coupon'])) {
                $c = $promoArr['promotion']['coupon'];
                $couponId = is_array($c) ? ($c['id'] ?? null) : $c;
            } elseif (isset($promoArr['coupon'])) {
                $c = $promoArr['coupon'];
                $couponId = is_array($c) ? ($c['id'] ?? null) : $c;
            }

            if (!$couponId) {
                return response()->json([
                    'valid'   => false,
                    'message' => 'Associated coupon ID not found.',
                ], 422);
            }

            
            $coupon = \Stripe\Coupon::retrieve($couponId);
            $couponArr = $coupon->toArray();

            if (($couponArr['valid'] ?? true) === false) { 
                return response()->json([
                    'valid'   => false,
                    'message' => 'This coupon is no longer active.',
                ], 422);
            }

           
            $isAnnual = strtolower($request->input('plan')) === 'annual';
            $basePrice = $isAnnual ? 77.00 : 7.00;
            $discountAmount = 0.00;

            $percentOff = isset($couponArr['percent_off']) ? (float)$couponArr['percent_off'] : null;
            $amountOff  = isset($couponArr['amount_off'])  ? ((float)$couponArr['amount_off'] / 100.0) : null;

            if ($percentOff !== null && $percentOff > 0) {
                $discountAmount = ($basePrice * $percentOff) / 100.0;
            } elseif ($amountOff !== null && $amountOff > 0) {
                $discountAmount = $amountOff;
            }

            $newTotal = max(0.00, $basePrice - $discountAmount);

            return response()->json([
                'valid'           => true,
                'promo_id'        => $promoArr['id'],
                'code'            => $promoArr['code'],
                'percent_off'     => $percentOff,
                'amount_off'      => $amountOff,
                'discount_amount' => number_format($discountAmount, 2, '.', ''),
                'new_total'       => number_format($newTotal, 2, '.', ''),
                'message'         => 'Coupon applied successfully!',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'valid'   => false,
                'message' => 'Error verifying coupon: ' . $e->getMessage(),
            ], 500);
        }
    }

}