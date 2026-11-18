@extends('site.layouts.app')

@section('title', 'Gene Decode — Contact Us')
@section('meta_description', 'Have questions about your Gene Decode membership, billing, or video access? Send a message to our support team.')
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

        <form method="POST" action="{{ route('contact.send') }}" class="card-panel">
            @csrf

            <div class="acct-grid">
                <div class="field">
                    <label for="contact-name">Name</label>
                    <input id="contact-name" type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Your name" required>
                    @error('name') <small style="color:#f87171">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="contact-email">Email</label>
                    <input id="contact-email" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="you@email.com" required>
                    @error('email') <small style="color:#f87171">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="field">
                <label for="contact-subject">Subject</label>
                <input id="contact-subject" type="text" name="subject" value="{{ old('subject') }}" placeholder="How can we help?" required>
                @error('subject') <small style="color:#f87171">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label for="contact-message">Message</label>
                <textarea id="contact-message" name="message" rows="5" placeholder="Write your message…" required>{{ old('message') }}</textarea>
                @error('message') <small style="color:#f87171">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn">
                Send message
            </button>

        </form>

    </div>
</section>

@endsection