@extends('site.layouts.app')

@section('title', 'Gene Decode — Watch')
@section('page', 'deep-dives')

@section('content')

<section>
    <div class="wrap watchgrid">

        <div>
            <div id="stage"></div>

            <div style="margin-top:18px">

                <span class="chip on" style="cursor:default" id="cat">
                    Deep Dive
                </span>

                <h1 id="title"
                    style="font-family:var(--serif);font-size:27px;margin:14px 0 8px">
                    Envisioning Freedom
                </h1>

                <p id="desc"
                   style="color:var(--muted);max-width:820px">
                    Members get the complete session plus the companion notes
                    and the live Q&amp;A replay. Your progress is saved automatically,
                    so you can pick up where you left off on any device.
                </p>

                <div id="meta"
                     style="display:flex;gap:14px;color:var(--faint);font-size:13px;margin-top:12px">
                    <span>Included with membership</span>
                </div>

            </div>
        </div>

        <div>
            <div class="rowhead">
                <h2>
                    <span class="bar"></span>
                    Up next
                </h2>
            </div>

            <div id="upnext"
                 style="display:flex;flex-direction:column;gap:14px">
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')

<script>
    // Keep the correct nav tab active based on where the user came from
    var params = new URLSearchParams(window.location.search);
    var from = params.get("from") || "deep-dives";

    document.body.dataset.page = "deep-dives";

    var id = params.get("v") || "v40vxe6";

    var v = VIDEOS.find(function(x) {
        return x.id === id;
    }) || VIDEOS[0];

    document.getElementById("stage").innerHTML =
        '<video class="vplayer" poster="/site/assets/vid/' + v.id + '.jpg" controls playsinline preload="none">' +
            '<source src="' + SAMPLE_VIDEO + '" type="video/mp4">' +
            'Your browser does not support the video tag.' +
        '</video>';

    document.getElementById("title").textContent = v.t;

    document.getElementById("cat").textContent = tag(v.c);

    document.getElementById("meta").innerHTML =
        "<span>" + v.s + "</span>" +
        "<span>·</span>" +
        "<span>" + v.d + "</span>" +
        "<span>·</span>" +
        "<span>Included with membership</span>";

    var next = VIDEOS
        .filter(function(x) {
            return x.id !== v.id;
        })
        .slice(0, 4);

    document.getElementById("upnext").innerHTML =
        next.map(function(x) {
            return card(x, false);
        }).join("");
</script>

@endpush