{{-- @extends('site.layouts.app')

@section('title', 'Gene Decode — Subscriber Agreement & Health Disclaimer')
@section('page', 'subscriber-agreement')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>

        <h1>Subscriber Agreement &amp; Health Disclaimer</h1>

        <p>
            Your membership terms and the medical and health disclaimer.
        </p>
    </div>
</section>

<section>
    <div class="wrap">

        <div class="prose">

            <h2>1. Overview</h2>

            <p>
                This is placeholder legal text for layout only.
                The final, client-approved copy will be reproduced here verbatim.
            </p>

            <h2>2. Your information</h2>

            <p>
                How we collect, use, and protect member information,
                and your choices, will be described in full in the approved copy.
            </p>

            <h2>3. Payments and membership</h2>

            <p>
                Billing, renewals, cancellation, and refunds are governed by
                the Subscriber Agreement and the Terms &amp; Conditions.
            </p>

            <h2>4. Contact</h2>

            <p>
                Questions about this document can be sent from the Contact page.
            </p>

        </div>

    </div>
</section>

@endsection --}}

@extends('site.layouts.app')

@section('title', 'Gene Decode — Subscriber Agreement & Health Disclaimer')
@section('meta_description', 'Subscriber Agreement, billing renewal terms, cancellation policy, and health/educational disclaimer for Gene Decode.')
@section('page', 'subscriber-agreement')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>
        <h1>Subscriber Agreement &amp; Health Disclaimer</h1>
        <p>Last updated: {{ date('F Y') }} — Your membership billing terms and educational/health disclaimer.</p>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="prose">
            <h2>1. Membership Plans &amp; Auto-Renewal</h2>
            <p>
                Gene Decode Deep Dives memberships are offered on recurring <strong>Monthly ($7.00/month)</strong> and <strong>Annual ($77.00/year)</strong> billing cycles. Unless cancelled prior to your renewal date, your subscription will automatically renew at the end of each billing period using your saved payment method on Stripe.
            </p>

            <h2>2. Plan Changes &amp; Cancellations</h2>
            <p>
                You may switch between Monthly and Annual plans or cancel your subscription at any time from your <a href="{{ url('/account') }}">Account Billing Dashboard</a>. When you cancel, your access to member-only videos continues through the end of your current paid billing period, and no further recurring charges will be made.
            </p>

            <h2>3. Refund Policy</h2>
            <p>
                Subscription fees are generally non-refundable once a billing cycle has begun. If you switch from an Annual plan to a Monthly plan, any unused balance is automatically applied as prorated credit toward future invoices on your Stripe account. Exceptional refund requests may be reviewed through our <a href="{{ url('/contact') }}">Contact page</a>.
            </p>

            <h2>4. Educational &amp; Health Disclaimer</h2>
            <p>
                All content presented on Gene Decode—including Deep Dives, Q&amp;A Zooms, interviews, and research discussions—is provided strictly for informational and educational purposes. Nothing on this platform constitutes medical, legal, financial, or professional health advice. Always consult a qualified healthcare professional before making decisions regarding any medical condition or treatment.
            </p>
        </div>
    </div>
</section>

@endsection