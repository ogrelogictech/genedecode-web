@extends('site.layouts.app')

@section('title', 'Gene Decode — Surface Area')

@section('page', 'surface')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Monthly update</span>

        <h1>Surface Area</h1>

        <p>
            Gene's monthly update video on what is coming in Deep Dives,
            and the themes he is working through.
        </p>
    </div>
</section>

<section>
    <div class="wrap splitgrid" style="grid-template-columns:1.4fr .6fr">

        <video
            class="vplayer"
            poster="{{ asset('site/assets/hero.jpg') }}"
            controls
            playsinline
            preload="none"
        >
            <source
                src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
                type="video/mp4"
            >
            Your browser does not support the video tag.
        </video>

        <div>
            <span class="kicker">This month</span>

            <h2 style="font-family:var(--serif);font-size:26px;margin:8px 0 12px">
                This month on Deep Dives
            </h2>

            <p style="color:var(--muted)">
                A short update from Gene on the upcoming monthly Deep Dive,
                the next live Q&amp;A, and the topics members have asked to cover.
                New members can watch this to see what the membership includes.
            </p>

            <div style="margin-top:14px">
                <a class="btn" href="{{ url('/join-us') }}">
                    Subscribe to Deep Dives
                </a>
            </div>
        </div>

    </div>
</section>

@endsection