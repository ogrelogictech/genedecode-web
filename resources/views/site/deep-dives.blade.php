@extends('site.layouts.app')

@section('title', 'Gene Decode — Deep Dives')

@section('page', 'deep-dives')

@section('content')

<section class="pagehero">
    <div class="wrap">

        <span class="kicker">Members library</span>

        <h1>Deep Dives</h1>

        <p>
            Every Deep Dive video, Q&amp;A Zoom, exclusive decode, and the monthly
            content, in one place. Pick up where you left off on any device.
        </p>

    </div>
</section>

<section>
    <div class="wrap">

        <div id="continue-watching-section" style="display:none;">

            <div class="rowhead">
                <h2>
                    <span class="bar"></span>
                    Continue watching
                </h2>
            </div>

            <div class="rowscroll" id="row-continue"></div>

        </div>

        <div class="rowhead" style="margin-top:40px">
            <h2>
                <span class="bar"></span>
                Browse the library
            </h2>
        </div>

        <div class="chips" id="dd-chips">
            <span class="chip on" data-val="All">All</span>
            <span class="chip" data-val="Deep Dive">Deep Dives</span>
            <span class="chip" data-val="Pearls of Wisdom">Pearls of Wisdom</span>
            <span class="chip" data-val="Q&amp;A">Q&amp;A Zooms</span>
        </div>

        <div class="grid" id="grid-dd"></div>

    </div>
</section>

@endsection

@push('scripts')
<script>
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
                    return video !== null && video.c === "Deep Dive";
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
    @else
        document.getElementById("continue-watching-section").style.display = "none";
        render("row-continue", []);
    @endauth
    
    function ddList(val) {
        return val === "All" ? VIDEOS : byCat(val);
    }

    render("grid-dd", ddList("All"));

    wireChips("#dd-chips", "grid-dd", ddList);
</script>
@endpush