<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\UserActivity;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            UserActivity::create([
                'user_id' => Auth::id(),
                'activity_type' => 'login',
                'page' => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/');
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match with our records.',
            ])
            ->withInput($request->only('email'));
    }

    // public function register(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'email', 'max:255', 'unique:users,email'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //         // 'plan' => ['required', 'in:monthly,annual']
    //     ]);

    //     $user = User::create([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'password' => $validated['password'],
    //     ]);

    //     Auth::login($user);

    //     $request->session()->regenerate();

    //     return redirect('/account')->with(
    //         'success',
    //         'Your account has been created successfully.'
    //     );
    // }


    public function register(Request $request)
    {
        $isGift = $request->filled('gift_ref');

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gift_ref' => ['nullable', 'string', 'max:255'],
        ];

        if (!$isGift) {
            $rules['plan'] = ['required', 'in:monthly,annual'];
        }

        $validated = $request->validate($rules);

        Stripe::setApiKey(config('services.stripe.secret'));

        // Gift Redemption Flow (Supports single & multi-quantity gift links)
        if ($isGift) {
            $giftRef = trim($validated['gift_ref']);

            try {
                $giftSession = Session::retrieve($giftRef);
                $purchasedQty = max(1, (int) ($giftSession->metadata->quantity ?? 1));

                if (
                    ($giftSession->payment_status ?? '') !== 'paid' ||
                    ($giftSession->metadata->type ?? '') !== 'gift_membership'
                ) {
                    return back()->withInput()->with('error', 'Invalid or unpaid gift membership link.');
                }

                $months = max(1, (int) ($giftSession->metadata->months ?? 1));
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Unable to verify gift link. Please check the URL or contact support.');
            }

            $redeemedCount = Membership::where('gift_session_id', $giftRef)->count();
            if ($redeemedCount >= $purchasedQty) {
                return back()->withInput()->with('error', 'All gift memberships from this link have already been redeemed.');
            }

            $user = \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $giftRef, $purchasedQty, $months) {
                $currentCount = Membership::where('gift_session_id', $giftRef)
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $purchasedQty) {
                    return null;
                }

                $newUser = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                Membership::create([
                    'user_id'         => $newUser->id,
                    'plan'            => $months >= 12 ? 'annual' : 'monthly',
                    'status'          => 'active',
                    'is_gift'         => true,
                    'gift_months'     => $months,
                    'gift_session_id' => $giftRef,
                    'started_at'      => Carbon::now(),
                    'expires_at'      => Carbon::now()->addMonths($months),
                ]);

                return $newUser;
            });

            if (!$user) {
                return back()->withInput()->with('error', 'All gift memberships from this link have already been redeemed.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            session(['ga_sign_up' => true]);

            return redirect('/deep-dives')->with('success', "Welcome! Your {$months}-month gift membership is now active.");
        }

        // Standard Paid Membership Flow
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $customer = Customer::create([
            'email'    => $user->email,
            'name'     => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->stripe_id = $customer->id;
        $user->save();

        $priceId = $validated['plan'] === 'monthly'
            ? config('services.stripe.price_monthly')
            : config('services.stripe.price_annual');

        $sessionParams = [
            'customer' => $user->stripe_id,
            'line_items' => [[
                'price'    => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'allow_promotion_codes' => true,
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('subscription.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'plan'    => $validated['plan'],
            ],
        ];

        if ($request->filled('stripe_promo_id')) {
            $sessionParams['discounts'] = [[
                'promotion_code' => $request->stripe_promo_id,
            ]];
            unset($sessionParams['allow_promotion_codes']);
        }

        $session = Session::create($sessionParams);

        session(['ga_sign_up' => true]);

        return redirect($session->url);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'If an account exists for this email address, a password reset link has been sent.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'We could not send a password reset link. Please check the email address and try again.',
        ], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with(
                'success',
                'Your password has been reset successfully. You can now sign in with your new password.'
            );
        }

        return back()
            ->withErrors([
                'email' => __($status),
            ])
            ->withInput($request->only('email'));
    }
}