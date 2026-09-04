@extends('site.layouts.app')

@section('title', 'Gene Decode — Live Q&A')
@section('page', 'community')

@section('content')

<section>
    <div class="wrap">

        <div class="livewrap">

            <div>
                <div class="player big"
                     style="background-image:url('{{ asset('site/assets/vid/v7ardmw.jpg') }}')">

                    <div style="position:absolute;top:14px;left:14px;z-index:3"
                         class="livebadge">
                        <span class="dot"></span>
                        LIVE · 1,204 watching
                    </div>

                    <span class="play">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </span>

                </div>

                <h1 style="font-family:var(--serif);font-size:25px;margin:16px 0 8px">
                    The General's Tent — Live Q&amp;A with Gene
                </h1>

                <p style="color:var(--muted);max-width:720px;margin:0">
                    Live now for members. Ask your questions in the chat.
                    The full replay is added to the Deep Dives library right after
                    the broadcast ends.
                </p>

                <div style="display:flex;gap:14px;color:var(--faint);font-size:13px;margin-top:12px">
                    <span>Members only</span>
                    <span>·</span>
                    <span>Auto-recorded to library</span>
                </div>
            </div>

            <div class="chat">

                <div class="h">
                    Live chat
                </div>

                <div class="b" id="chat"></div>

                <div class="f">
                    <input placeholder="Say something to the room…">
                    <button class="btn sm">Send</button>
                </div>

            </div>

        </div>

        <div class="rowhead" style="margin-top:40px">
            <h2>
                <span class="bar"></span>
                Upcoming live events
            </h2>
        </div>

        <div id="events"></div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    var chat = [
        ["Maria", "Thank you Gene, this is exactly what I needed today"],
        ["Tom", "Joining from Texas, praying with you all"],
        ["Sandra", "Can you revisit the point on discernment?"],
        ["Kevin", "The download feature on the app is great now"],
        ["Gene Decode Team", "Great questions coming in — keep them going"],
        ["Lillian", "First live I've caught in real time, love this"],
        ["Joan", "Hello from Ontario"]
    ];

    document.getElementById("chat").innerHTML = chat.map(function(m) {
        return '<div class="msg"><b>' + m[0] + '</b>' + m[1] + '</div>';
    }).join("");

    var ev = [
        ["28", "AUG", "The General's Tent — Live Q&A", "Nino's Corner · 7:00 pm CT", "Live Q&A"],
        ["05", "SEP", "Monthly Deep Dive premiere", "New members-only decode", "Premiere"],
        ["18", "SEP", "Community prayer call", "envisioningforlife · 6:00 pm CT", "Community"]
    ];

    document.getElementById("events").innerHTML = ev.map(function(e) {
        return '<div class="evrow">' +
            '<div class="date"><b>' + e[0] + '</b><span>' + e[1] + '</span></div>' +
            '<div><h4>' + e[2] + '</h4><p>' + e[3] + '</p></div>' +
            '<div class="type">' + e[4] + '</div>' +
        '</div>';
    }).join("");
</script>
@endpush