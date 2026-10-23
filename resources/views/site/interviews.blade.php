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

        <div class="grid" id="grid-interviews" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @forelse($videos as $item)
                <div class="vcard-item" style="background: #0d1527; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
                    <a href="{{ route('watch', ['videoId' => $item->id, 'from' => 'interviews']) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                        
                        <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; overflow: hidden;">
                            <img 
                                src="{{ $item->thumbnail_url ?: asset('site/assets/hero.jpg') }}" 
                                alt="{{ $item->title }}"
                                style="width: 100%; height: 100%; object-fit: cover;"
                            >

                            <span style="position: absolute; bottom: 6px; left: 6px; background: rgba(0,0,0,0.85); color: #7cc242; font-size: 9px; font-weight: 800; padding: 2px 5px; border-radius: 2px; letter-spacing: 0.5px; text-transform: uppercase;">
                                {{ strtoupper($item->section ?: 'INTERVIEW') }}
                            </span>

                            @if($item->duration_seconds)
                                <span style="position: absolute; bottom: 6px; right: 6px; background: rgba(0,0,0,0.85); color: #ffffff; font-size: 10px; padding: 2px 5px; border-radius: 2px; font-weight: 600;">
                                    {{ floor($item->duration_seconds / 60) }}:{{ sprintf('%02d', $item->duration_seconds % 60) }}
                                </span>
                            @endif
                        </div>

                        <div style="padding: 12px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="color: #ffffff; font-size: 14px; font-weight: 600; line-height: 1.35; margin: 0 0 6px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $item->title }}
                                </h3>
                            </div>

                            <div style="margin-top: 8px; font-size: 11px; color: rgba(255,255,255,0.45);">
                                @if($item->uploaded_at)
                                    <span>{{ \Carbon\Carbon::parse($item->uploaded_at)->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>

                    </a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; color: rgba(255,255,255,0.6); padding: 40px 0; text-align: center;">
                    No interview videos available.
                </div>
            @endforelse
        </div>

        @if ($videos->hasPages())
            <div class="custom-pagination" style="margin-top: 40px; display: flex; justify-content: center; align-items: center; gap: 8px;">
                @if ($videos->onFirstPage())
                    <span style="padding: 6px 12px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border-radius: 4px; font-size: 13px; cursor: not-allowed;">&laquo; Prev</span>
                @else
                    <a href="{{ $videos->previousPageUrl() }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">&laquo; Prev</a>
                @endif

                @foreach ($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                    @if ($page == $videos->currentPage())
                        <span style="padding: 6px 12px; background: #7cc242; color: #0a101d; font-weight: 700; border-radius: 4px; font-size: 13px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($videos->hasMorePages())
                    <a href="{{ $videos->nextPageUrl() }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">Next &raquo;</a>
                @else
                    <span style="padding: 6px 12px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border-radius: 4px; font-size: 13px; cursor: not-allowed;">Next &raquo;</span>
                @endif
            </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
{{-- <script>
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
</script> --}}
@endpush