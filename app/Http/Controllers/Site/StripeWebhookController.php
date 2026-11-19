<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\User;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\MembershipActivatedMail;
use App\Mail\PaymentFailedMail;
use App\Mail\MembershipCancelledMail;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Throwable $e) {
            Log::error('Stripe Webhook Signature/Payload Error: ' . $e->getMessage());
            return response('Invalid Signature', 400);
        }

        try {
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                default:
                    Log::info('Unhandled Stripe Event: ' . $event->type);
                    break;
            }
        } catch (\Throwable $e) {
            Log::error('Webhook Processing Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return response('Internal Error Logged', 500);
        }

        return response('Webhook Handled', 200);
    }

    protected function handleCheckoutCompleted($session)
    {
        if (($session->mode ?? '') === 'payment' || ($session->metadata->type ?? '') === 'gift_membership') {
            Log::info("Gift checkout completed (awaiting recipient redemption): {$session->id}");
            return;
        }

        $userId = $session->metadata->user_id ?? null;
        $plan = $session->metadata->plan ?? 'monthly';
        $stripeSubscriptionId = $session->subscription ?? null;

        if (!$userId) {
            $user = User::where('stripe_id', $session->customer)->first();
            $userId = $user ? $user->id : null;
        }

        if ($userId && $stripeSubscriptionId) {
            $startedAt = Carbon::now();
            $expiresAt = $plan === 'annual'
                ? Carbon::now()->addYear()
                : Carbon::now()->addMonth();

            
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $stripeSub = Subscription::retrieve($stripeSubscriptionId);
                $subItem = $stripeSub->items->data[0] ?? null;

                if (!empty($stripeSub->trial_end) && $stripeSub->trial_end > time()) {
                    $startedAt = Carbon::createFromTimestamp($stripeSub->trial_end);
                    $expiresAt = $plan === 'annual'
                        ? $startedAt->copy()->addYear()
                        : $startedAt->copy()->addMonth();
                } else {
                    $periodEndTs = $stripeSub->current_period_end ?? ($subItem->current_period_end ?? null);
                    if ($periodEndTs) {
                        $expiresAt = Carbon::createFromTimestamp($periodEndTs);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Could not retrieve Stripe subscription {$stripeSubscriptionId} in webhook: " . $e->getMessage());
            }

            $stripePriceId = null;
            if (isset($session->line_items->data[0]->price->id)) {
                $stripePriceId = $session->line_items->data[0]->price->id;
            } else {
                $stripePriceId = $plan === 'annual'
                    ? config('services.stripe.price_annual')
                    : config('services.stripe.price_monthly');
            }

            
            $membership = Membership::updateOrCreate(
                ['stripe_subscription_id' => $stripeSubscriptionId],
                [
                    'user_id'         => $userId,
                    'plan'            => $plan,
                    'status'          => 'active',
                    'is_gift'         => false,
                    'stripe_price_id' => $stripePriceId,
                    'started_at'      => $startedAt,
                    'expires_at'      => $expiresAt,
                    'canceled_at'     => null,
                ]
            );

            $user = User::find($userId);
            if ($user) {
                try {
                    Mail::to($user->email)->send(new MembershipActivatedMail($user, $membership));
                } catch (\Throwable $e) {
                    Log::error("Welcome email failed for User ID {$userId}: " . $e->getMessage());
                }
            }

            Log::info("Membership activated/scheduled for User ID: {$userId}");
        }
    }

    protected function handleInvoicePaymentSucceeded($invoice)
    {
        $stripeSubscriptionId = $invoice->subscription;

        if ($stripeSubscriptionId) {
            $membership = Membership::where('stripe_subscription_id', $stripeSubscriptionId)->first();

            if ($membership) {
                $expiresAt = $membership->plan === 'annual' 
                    ? Carbon::now()->addYear() 
                    : Carbon::now()->addMonth();

                $membership->update([
                    'status'     => 'active',
                    'expires_at' => $expiresAt,
                ]);

                Log::info("Membership renewed for User ID: {$membership->user_id}");
            }
        }
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        $stripeSubscriptionId = $invoice->subscription;

        if ($stripeSubscriptionId) {
            $membership = Membership::where('stripe_subscription_id', $stripeSubscriptionId)->first();
            if ($membership) {
                $membership->update([
                    'status' => 'past_due',
                ]);

                if ($membership->user) {
                    try {
                        Mail::to($membership->user->email)->send(new PaymentFailedMail($membership->user, $membership));
                    } catch (\Throwable $e) {
                        Log::error("Payment failed email error for User ID {$membership->user_id}: " . $e->getMessage());
                    }
                }

                Log::warning("Payment failed (dunning triggered) for User ID: {$membership->user_id}");
            }
        }
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        $membership = Membership::where('stripe_subscription_id', $subscription->id)->first();

        if ($membership) {
            $membership->update([
                'status'      => 'cancelled',
                'canceled_at' => Carbon::now(),
            ]);

            if ($membership->user) {
                try {
                    Mail::to($membership->user->email)->send(new MembershipCancelledMail($membership->user, $membership, true));
                } catch (\Throwable $e) {
                    Log::error("Cancellation ended email error for User ID {$membership->user_id}: " . $e->getMessage());
                }
            }

            Log::info("Membership cancelled for User ID: {$membership->user_id}");
        }
    }
}