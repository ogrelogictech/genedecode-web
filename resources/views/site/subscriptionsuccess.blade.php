@extends('site.layouts.app')

@section('title', 'gene Decode — Subscription Success')
@section('page', 'subscription-success')

@section('content')
<section class="pagehero" style="text-align: center; padding: 80px 20px;">
    <div class="wrap" style="max-width: 650px; margin: 0 auto;">
        
        <div style="width: 72px; height: 72px; background: rgba(34, 197, 94, 0.15); border: 2px solid #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <span class="kicker" style="color: #22c55e; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
            {{ !empty($isScheduledFromGift) ? 'Auto-Renewal Scheduled' : 'Success' }}
        </span>
        @if(!empty($isScheduledFromGift))
            <h1 style="margin-top: 10px; font-size: 32px;">Your Membership Continuation is Set!</h1>
            <p style="font-size: 16px; color: var(--muted, #94a3b8); line-height: 1.6; margin: 16px 0 32px;">
                Your payment method has been saved and no charge was made today. You will continue enjoying your current gift access, and your
                <strong style="color:#fff;">{{ $transactionData['plan_name'] ?? 'membership' }}</strong> will automatically begin on
                <strong style="color:#7cc242;">{{ $billingStartDate }}</strong>.
            </p>
        @else
            <h1 style="margin-top: 10px; font-size: 32px;">Welcome to the Membership!</h1>
            <p style="font-size: 16px; color: var(--muted, #94a3b8); line-height: 1.6; margin: 16px 0 32px;">
                Your payment was successful and your subscription is now active. You now have full access to our entire library, live sessions, and community.
            </p>
        @endif

        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ url('/deep-dives') }}" class="btn" style="padding: 12px 28px; text-decoration: none;">Start Watching</a>
            <a href="{{ url('/account') }}" class="btn ghost" style="padding: 12px 24px; text-decoration: none;">View Account</a>
        </div>

    </div>
</section>
@push('scripts')
@if(!empty($transactionData))
<script>
    (function() {
        var txId = @json($transactionData['transaction_id']);
        var storageKey = 'gd_tracked_tx_' + txId;

        if (!sessionStorage.getItem(storageKey)) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push({
                event: 'purchase',
                ecommerce: {
                    transaction_id: txId,
                    value: {{ $transactionData['value'] }},
                    currency: @json($transactionData['currency']),
                    items: [{
                        item_id: @json($transactionData['plan_id']),
                        item_name: @json($transactionData['plan_name']),
                        price: {{ $transactionData['value'] }},
                        quantity: 1
                    }]
                }
            });
            sessionStorage.setItem(storageKey, '1');
        }
    })();
</script>
@endif
@endpush
@endsection
