{{-- @extends('site.layouts.app')

@section('title', 'Gene Decode — Privacy Policy')
@section('page', 'privacy')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>

        <h1>Privacy Policy</h1>

        <p>
            How we handle your information on the Gene Decode platform.
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

@section('title', 'Gene Decode — Privacy Policy')
@section('meta_description', 'Privacy Policy explaining how Gene Decode collects, uses, and protects your personal and billing information.')
@section('page', 'privacy')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>
        <h1>Privacy Policy</h1>
        <p>Last updated: {{ date('F Y') }} — How we collect, use, and protect your information on the Gene Decode platform.</p>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="prose">
            <h2>1. Overview</h2>
            <p>
                Gene Decode (&ldquo;we,&rdquo; &ldquo;our,&rdquo; or &ldquo;us&rdquo;) respects your privacy and is committed to protecting the personal information you share when visiting <strong>genedecode.org</strong> or subscribing to our membership services.
            </p>

            <h2>2. Information We Collect</h2>
            <p>
                When you register an account, subscribe, or contact us, we collect your name, email address, login credentials (stored in encrypted/hashed form), and basic account preferences. Video watch progress is stored solely to allow you to resume playback across sessions.
            </p>

            <h2>3. Payment Processing &amp; Security</h2>
            <p>
                All subscription payments are processed securely through <strong>Stripe</strong>. We do not store your full credit or debit card numbers on our servers. Stripe processes payment details in accordance with PCI-DSS security standards.
            </p>

            <h2>4. Cookies &amp; Analytics</h2>
            <p>
                We use essential session cookies to keep you signed in and protect form submissions. With your consent, we also use Google Tag Manager and Google Analytics (GA4) to understand aggregate site traffic and improve platform performance. You can manage your cookie preference at any time via your browser settings.
            </p>

            <h2>5. Your Rights &amp; Data Requests</h2>
            <p>
                We never sell or rent your personal data to third parties. You may update your profile details or email preferences anytime from your <a href="{{ url('/account') }}">Account Settings</a>, or request account deletion via our <a href="{{ url('/contact') }}">Contact</a> page.
            </p>
        </div>
    </div>
</section>

@endsection