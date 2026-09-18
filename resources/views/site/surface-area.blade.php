@extends('site.layouts.app')

@section('title', 'Gene Decode — Surface Area')

@section('page', 'surface')

@section('content')

{{-- <section class="pagehero">
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
</section> --}}

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Free to watch</span>
        <h1>Surface Area</h1>
        <p>A selection of Gene's free videos, open to everyone. For his full library of free interviews from across many platforms, use the box below.</p>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="rowhead">
            <h2><span class="bar"></span>Free videos</h2>
        </div>
        <div class="grid" id="free-grid"></div>
        <div class="linkbox" style="margin-top:34px">
            <div class="lb-ic"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M10 9l5 3-5 3z"/></svg></div>
            <div style="min-width:0">
                <h3>Gene's free interviews &amp; videos</h3>
                <p>Interviews from across many platforms — Current Events, DUMBs, World Government, True History, and more. Free to watch.</p>
            </div>
            <a class="btn" href="{{ URL('interviews') }}">Browse interviews</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')

<script>
  // Free videos on Surface Area: Gene's free interviews, open to all (not locked).
  render("free-grid", byCat("Interview").slice(0,6), false);
</script>

@endpush