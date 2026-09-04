@extends('site.layouts.app')

@section('title', 'Gene Decode — Schedule & Newsletters')

@section('page', 'schedule')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Schedule</span>

        <h1>Schedule &amp; Newsletters</h1>

        <p>
            Upcoming interviews, community chats, Q&amp;A Zooms, and Deep Dive releases.
            Sign up to get them in your inbox.
        </p>
    </div>
</section>

<section>
    <div class="wrap">

        <div class="rowhead">
            <h2>
                <span class="bar"></span>
                Upcoming
            </h2>
        </div>

        <div class="evrow">
            <div class="date">
                <b>28</b>
                <span>AUG</span>
            </div>

            <div>
                <h4>The General's Tent — Live Q&amp;A</h4>
                <p>Nino's Corner · 7:00 pm CT</p>
            </div>

            <div class="type">
                Live Q&amp;A
            </div>
        </div>

        <div class="evrow">
            <div class="date">
                <b>05</b>
                <span>SEP</span>
            </div>

            <div>
                <h4>Monthly Deep Dive premiere</h4>
                <p>New members-only decode</p>
            </div>

            <div class="type">
                Premiere
            </div>
        </div>

        <div class="evrow">
            <div class="date">
                <b>12</b>
                <span>SEP</span>
            </div>

            <div>
                <h4>Interview: The Sovereign Soul Show</h4>
                <p>Public interview</p>
            </div>

            <div class="type">
                Interview
            </div>
        </div>

        <div class="evrow">
            <div class="date">
                <b>18</b>
                <span>SEP</span>
            </div>

            <div>
                <h4>Community prayer call</h4>
                <p>envisioningforlife · 6:00 pm CT</p>
            </div>

            <div class="type">
                Community
            </div>
        </div>

        <div class="card-panel" style="margin-top:24px;text-align:center">

            <h3 style="font-family:var(--serif);margin:0 0 8px">
                Get the newsletter
            </h3>

            <p style="color:var(--muted);margin:0 0 14px">
                New content, live sessions, and announcements, straight to your inbox.
            </p>

            <div class="news">
                <input
                    class="field"
                    placeholder="you@email.com"
                >

                <button class="btn">
                    Sign up
                </button>
            </div>

        </div>

    </div>
</section>

@endsection