<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\User;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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

                // 1. Dunning Logic: Payment fail hone par status 'past_due' mark karein
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                // 2. Cancellation Logic: Stripe subscription delete hone par status 'cancelled' mark karein
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
        $userId = $session->metadata->user_id ?? null;
        $plan = $session->metadata->plan ?? 'monthly';
        $stripeSubscriptionId = $session->subscription ?? null;

        if (!$userId) {
            $user = User::where('stripe_id', $session->customer)->first();
            $userId = $user ? $user->id : null;
        }

        if ($userId) {
            $expiresAt = $plan === 'annual' 
                ? Carbon::now()->addYear() 
                : Carbon::now()->addMonth();

            $stripePriceId = null;
            if (isset($session->line_items->data[0]->price->id)) {
                $stripePriceId = $session->line_items->data[0]->price->id;
            } else {
                $stripePriceId = $plan === 'annual' 
                    ? config('services.stripe.price_annual') 
                    : config('services.stripe.price_monthly');
            }

            Membership::updateOrCreate(
                ['user_id' => $userId],
                [
                    'plan'                   => $plan,
                    'status'                 => 'active',
                    'stripe_subscription_id' => $stripeSubscriptionId,
                    'stripe_price_id'        => $stripePriceId,
                    'started_at'             => Carbon::now(),
                    'expires_at'             => $expiresAt,
                    'canceled_at'            => null,
                ]
            );

            Log::info("Membership activated for User ID: {$userId}");
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

            Log::info("Membership cancelled for User ID: {$membership->user_id}");
        }
    }
}