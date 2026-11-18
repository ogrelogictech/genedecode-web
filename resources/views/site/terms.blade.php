{{-- @extends('site.layouts.app')

@section('title', 'Gene Decode — Terms & Conditions')
@section('page', 'terms')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>

        <h1>Terms &amp; Conditions</h1>

        <p>
            The terms that govern your use of the Gene Decode website and membership.
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

@section('title', 'Gene Decode — Terms & Conditions')
@section('meta_description', 'Terms & Conditions governing your use of the Gene Decode website, video library, and membership access.')
@section('page', 'terms')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Legal</span>
        <h1>Terms &amp; Conditions</h1>
        <p>Last updated: {{ date('F Y') }} — The terms that govern your use of the Gene Decode website and membership.</p>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="prose">
            <h2>1. Acceptance of Terms</h2>
            <p>
                By accessing or using <strong>genedecode.org</strong>, creating an account, or purchasing a membership, you agree to be bound by these Terms &amp; Conditions and our <a href="{{ url('/subscriber-agreement') }}">Subscriber Agreement</a>.
            </p>

            <h2>2. Account Responsibility</h2>
            <p>
                Your membership is for individual, personal use only. You are responsible for maintaining the confidentiality of your login credentials and must not share, redistribute, or publicly stream member-only videos without prior written authorization.
            </p>

            <h2>3. Intellectual Property</h2>
            <p>
                All videos, audio recordings, live streams, graphics, and written materials published on Gene Decode are protected by applicable copyright and intellectual property laws. Unauthorized downloading, screen-recording, or commercial redistribution is strictly prohibited.
            </p>

            <h2>4. Platform Availability &amp; Conduct</h2>
            <p>
                We reserve the right to suspend or terminate accounts that violate these terms, attempt to bypass paywall protections, or disrupt live streams and community spaces.
            </p>

            <h2>5. Contact</h2>
            <p>
                For questions regarding these Terms &amp; Conditions, please reach out through our <a href="{{ url('/contact') }}">Contact page</a>.
            </p>
        </div>
    </div>
</section>

@endsection