@extends('site.layouts.app')

@section('title', 'Gene Decode — Contact Us')
@section('page', 'contact')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Get in touch</span>

        <h1>Contact Us</h1>

        <p>
            Questions about your membership, billing, or access?
            Send us a message and our team will help.
        </p>
    </div>
</section>

<section>
    <div class="wrap" style="max-width:620px">

        <div class="card-panel">

            <div class="acct-grid">

                <div class="field">
                    <label>Name</label>
                    <input placeholder="Your name">
                </div>

                <div class="field">
                    <label>Email</label>
                    <input placeholder="you@email.com">
                </div>

            </div>

            <div class="field">
                <label>Subject</label>
                <input placeholder="How can we help?">
            </div>

            <div class="field">
                <label>Message</label>
                <textarea rows="5" placeholder="Write your message…"></textarea>
            </div>

            <button class="btn">
                Send message
            </button>

        </div>

    </div>
</section>

@endsection