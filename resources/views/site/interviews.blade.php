@extends('site.layouts.app')

@section('title', 'Gene Decode — Interviews & Videos')

@section('page', 'interviews')

@section('content')

<section class="pagehero">
    <div class="wrap">

        <span class="kicker">Free public library</span>

        <h1>Interviews &amp; Videos</h1>

        <p>
            Gene's interviews from across many platforms, free to watch.
            Topics include Current Events, DUMBs, World Government, True History,
            and more. For the full library, subscribe to Deep Dives.
        </p>

        <div style="margin-top:16px">
            <a class="btn" href="{{ url('/join-us') }}">
                Subscribe to Deep Dives
            </a>
        </div>

    </div>
</section>

<section>
    <div class="wrap">

        @auth
            <div id="continue-watching-section" style="display:none; margin-bottom:40px;">

                <div class="rowhead">
                    <h2>
                        <span class="bar"></span>
                        Continue watching
                    </h2>
                </div>

                <div class="rowscroll" id="row-continue"></div>

            </div>
        @endauth

        <div class="chips" id="iv-chips">
            <span class="chip on" data-val="All">All</span>
            <span class="chip" data-val="Newest">Newest</span>
            <span class="chip" data-val="Most popular">Most popular</span>
        </div>

        <div class="grid" id="grid-interviews"></div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    var IV = byCat("Interview");

    @auth
        fetch("{{ route('video.progress.index') }}", {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Unable to load Continue Watching data.");
            }

            return response.json();
        })
        .then(function(data) {
            if (!data.success || !Array.isArray(data.progress)) {
                document.getElementById("continue-watching-section").style.display = "none";
                render("row-continue", []);
                return;
            }

            var continueVideos = data.progress
                .map(function(progress) {
                    var video = VIDEOS.find(function(v) {
                        return v.id === progress.video_id;
                    });

                    if (!video) {
                        return null;
                    }

                    return Object.assign({}, video, {
                        p: parseFloat(progress.progress_percent) || 0
                    });
                })
                .filter(function(video) {
                    return video !== null && video.c === "Interview";
                });

            if (continueVideos.length > 0) {
                document.getElementById("continue-watching-section").style.display = "";
                render("row-continue", continueVideos);
            } else {
                document.getElementById("continue-watching-section").style.display = "none";
                render("row-continue", []);
            }
        })
        .catch(function(error) {
            console.error("Unable to load Continue Watching:", error);

            document.getElementById("continue-watching-section").style.display = "none";
            render("row-continue", []);
        });
    @endauth

    var ivList = function(val) {
        if (val === "Newest") {
            return IV.slice().reverse();
        }

        if (val === "Most popular") {
            return IV.slice().sort(function(a, b) {
                return (b.p || 0) - (a.p || 0);
            });
        }

        return IV;
    };

    render("grid-interviews", ivList("All"));
    wireChips("#iv-chips", "grid-interviews", ivList);
</script>
@endpush