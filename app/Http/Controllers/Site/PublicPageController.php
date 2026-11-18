<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PublicPageController extends Controller
{
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:180'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        try {
            // $adminEmail = config('mail.from.address');
            $adminEmail = 'prachi.tripathi+1@ogrelogic.com';
            $body = "New Contact Form Submission — Gene Decode\n\n"
                  . "Name: {$validated['name']}\n"
                  . "Email: {$validated['email']}\n"
                  . "Subject: {$validated['subject']}\n\n"
                  . "Message:\n{$validated['message']}";

            Mail::raw($body, function ($mail) use ($validated, $adminEmail) {
                $mail->to($adminEmail)
                     ->replyTo($validated['email'], $validated['name'])
                     ->subject('[Gene Decode Contact] ' . $validated['subject']);
            });
        } catch (\Throwable $e) {
            Log::error('Contact form email failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Unable to send message right now. Please try again shortly.');
        }

        return back()->with('success', 'Thank you! Your message has been sent. Our team will get back to you soon.');
    }

    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:180'],
        ]);

        try {
            $adminEmail = config('mail.from.address');
            Mail::raw("New Newsletter Subscriber: {$validated['email']}", function ($mail) use ($adminEmail, $validated) {
                $mail->to($adminEmail)
                     ->subject('[Gene Decode Newsletter] New Signup: ' . $validated['email']);
            });
        } catch (\Throwable $e) {
            Log::warning('Newsletter notification email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'You are subscribed! Watch your inbox for upcoming Deep Dives and live session alerts.');
    }

    public function giftCheckout(Request $request)
    {
        $validated = $request->validate([
            'duration_type' => ['required', 'in:annual,monthly,custom'],
            'custom_months' => ['nullable', 'integer', 'min:1', 'max:36'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $months = match ($validated['duration_type']) {
            'annual'  => 12,
            'monthly' => 1,
            'custom'  => (int) ($validated['custom_months'] ?? 3),
        };

        $unitAmountCents = $validated['duration_type'] === 'annual'
            ? 7700
            : ($months * 700);

        $label = $validated['duration_type'] === 'annual'
            ? 'Gene Decode Deep Dives — 1 Year Gift Membership'
            : "Gene Decode Deep Dives — {$months} Month" . ($months > 1 ? 's' : '') . ' Gift Membership';

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $unitAmountCents,
                        'product_data' => [
                            'name'        => $label,
                            'description' => 'Gift membership access to Gene Decode Deep Dives (valid to redeem within 12 months).',
                        ],
                    ],
                    'quantity' => (int) $validated['quantity'],
                ]],
                'success_url' => url('/gift') . '?gift_success=1&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => url('/gift') . '?gift_cancelled=1',
                'metadata' => [
                    'type'          => 'gift_membership',
                    'months'        => $months,
                    'quantity'      => $validated['quantity'],
                    'purchaser_id'  => auth()->id() ?? 'guest',
                ],
            ]);

            return redirect($session->url);
        } catch (\Throwable $e) {
            Log::error('Gift Stripe Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Could not start gift checkout: ' . $e->getMessage());
        }
    }
}