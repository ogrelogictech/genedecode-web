@extends('site.layouts.app')

@section('title', 'Gene Decode — Donate')

@section('page', 'donate')

@section('content')

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Support the mission</span>

        <h1>Donate</h1>

        <p>
            If this work has blessed you and you feel called to support it,
            thank you. Every contribution helps keep the truth flowing.
        </p>
    </div>
</section>

<section>
    <div class="wrap" style="max-width:760px">

        <div class="card-panel center">

            <h2 style="font-family:var(--serif);margin:0 0 10px">
                Give a gift
            </h2>

            <p style="color:var(--muted);margin:0 0 18px">
                One-time or recurring donations are handled through Ko-fi.
            </p>

            <a class="btn" href="#">
                Donate on Ko-fi
            </a>

            <div style="border-top:1px solid var(--line);margin:22px 0 0;padding-top:18px;color:var(--muted);font-size:14px">
                Prefer to send a check or money order? Mail to:<br>

                <b style="color:var(--text)">
                    Gene Decode · PO Box 0000 · Your City, ST 00000
                </b>
            </div>

            <p style="margin-top:18px">
                <a class="btn ghost" href="{{ url('/join-us') }}">
                    Or become a member
                </a>
            </p>

        </div>

    </div>
</section>

@endsection