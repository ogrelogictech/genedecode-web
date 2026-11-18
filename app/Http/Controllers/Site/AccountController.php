<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MembershipCancelledMail;
use App\Mail\PlanChangedMail;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if user has an active gift membership
        $giftMembership = $user->memberships()
            ->where('is_gift', true)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        // Check if user has a paid/scheduled Stripe subscription
        $paidMembership = $user->memberships()
            ->where('is_gift', false)
            ->whereIn('status', ['active', 'cancelling'])
            ->latest()
            ->first();

        $membership = $giftMembership ?? $paidMembership;
        $upcomingSubscription = ($giftMembership && $paidMembership) ? $paidMembership : null;
        
        $paymentMethod = null;
        $invoices = [];

        if ($user->stripe_id) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                
                $customer = \Stripe\Customer::retrieve([
                    'id' => $user->stripe_id,
                    'expand' => ['default_source', 'invoice_settings.default_payment_method']
                ]);

                $pm = $customer->invoice_settings->default_payment_method;
                if ($pm && is_object($pm) && isset($pm->card)) {
                    $paymentMethod = ucfirst($pm->card->brand) . ' ending in ' . $pm->card->last4;
                }

                $stripeInvoices = \Stripe\Invoice::all([
                    'customer' => $user->stripe_id,
                    'limit' => 5,
                ]);
                $invoices = $stripeInvoices->data ?? [];

            } catch (\Throwable $e) {
                // Fail silently
            }
        }

        return view('site.account', compact('membership', 'upcomingSubscription', 'paymentMethod', 'invoices'));
    }

    // Stripe Customer Portal Session 
    public function customerPortal(Request $request)
    {
        $user = $request->user();

        if (!$user->stripe_id) {
            return back()->with('error', 'No active billing record found.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $portalSession = PortalSession::create([
            'customer' => $user->stripe_id,
            'return_url' => route('account.index'),
        ]);

        return redirect($portalSession->url);
    }

    // Cancel Subscription 
    public function cancelMembership(Request $request)
    {
        $user = $request->user();
        $membership = $user->memberships()
            ->where('is_gift', false)
            ->whereIn('status', ['active', 'cancelling'])
            ->latest()
            ->first();

        if (!$membership || !$membership->stripe_subscription_id) {
            return back()->with('error', 'No active recurring subscription found to cancel.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            Subscription::update($membership->stripe_subscription_id, [
                'cancel_at_period_end' => true,
            ]);

            $membership->update([
                'status' => 'cancelling',
                'canceled_at' => Carbon::now(),
            ]);

            try {
                Mail::to($user->email)->send(new MembershipCancelledMail($user, $membership, false));
            } catch (\Throwable $mailErr) {
                // Silent fail for email
            }

            return back()->with('success', 'Your subscription will cancel at the end of your billing period.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error cancelling subscription: ' . $e->getMessage());
        }
    }

    // Plan switch (Monthly <-> Annual)
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        $user = $request->user();
        $membership = $user->memberships()
            ->where('is_gift', false)
            ->whereIn('status', ['active', 'cancelling'])
            ->latest()
            ->first();

        if (!$membership || !$membership->stripe_subscription_id) {
            return back()->with('error', 'No active recurring subscription found.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $newPriceId = $request->plan === 'annual'
                ? config('services.stripe.price_annual')
                : config('services.stripe.price_monthly');

            $subscription = Subscription::retrieve($membership->stripe_subscription_id);

            Subscription::update($membership->stripe_subscription_id, [
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $newPriceId,
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]);

            $membership->update([
                'plan' => $request->plan,
                'stripe_price_id' => $newPriceId,
            ]);

            try {
                Mail::to($user->email)->send(new PlanChangedMail($user, $membership));
            } catch (\Throwable $mailErr) {
                // Silent fail for email
            }

            return back()->with('success', 'Your subscription plan has been updated successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not update plan: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'email' => [
            //     'required',
            //     'email',
            //     'max:255',
            //     Rule::unique('users')->ignore($user->id),
            // ],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Your private profile has been updated successfully.',
        ]);
    }

    public function updatePublicProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'display_name' => ['nullable', 'string', 'max:255'],
            'location'     => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'website'      => ['nullable', 'url', 'max:255'],
            'profilepic'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            
            if ($user->profilepic) {
                Storage::disk('public')->delete($user->profilepic);
            }

            
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['profilepic'] = $path;
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Your public profile has been updated successfully.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success'  => true,
            'message'  => 'Your password has been changed successfully. Please log in again.',
            'redirect' => route('login'), 
        ]);
    }
}