@extends('site.layouts.app')

@section('title', $video->title . ' — gene Decode')

@section('page', request('from') === 'surface-area' ? 'surface' : (request('from') === 'deep-dives' ? 'deep-dives' : 'watch'))

@section('content')

<section class="watch-hero" style="padding: 30px 0 60px;">
    <div class="wrap">
        <div class="watch-grid" style="display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start;">
            
            <!-- Main Video Column -->
            <div class="watch-main">
                
                <!-- Player Frame -->
                <div class="vplayer-wrapper" style="position: relative; padding-top: 56.25%; background: #000; border: 3px solid rgba(255,255,255,0.15); border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    @if($embedUrl)
                        <iframe 
                            id="bunny-stream-player"
                            src="{{ $embedUrl }}" 
                            loading="lazy" 
                            style="border: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #fff;">
                            <p style="font-size: 16px; color: var(--muted);">Video stream currently offline or processing.</p>
                        </div>
                    @endif
                </div>

                <!-- Video Meta Info -->
                <div class="vdetails" style="margin-top: 24px;">
                    <div style="margin-bottom: 12px;">
                        <span class="chip-badge" style="display: inline-block; background: #68d391; color: #0a101d; font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; text-transform: capitalize;">
                            {{ $video->section ?: 'Deep Dive' }}
                        </span>
                    </div>

                    <h1 style="font-family: var(--serif, serif); font-size: 28px; line-height: 1.3; color: #ffffff; margin: 0 0 16px 0; font-weight: 700;">
                        {{ $video->title }}
                    </h1>

                    <div class="video-body-text" style="color: rgba(255,255,255,0.8); font-size: 15px; line-height: 1.7; margin-bottom: 20px;">
                        {!! $video->description ?: $video->short_description !!}
                    </div>

                    <div class="vmeta-bar" style="font-size: 13px; color: rgba(255,255,255,0.5); display: flex; gap: 12px; align-items: center;">
                        @if($video->uploaded_at)
                            <span>{{ \Carbon\Carbon::parse($video->uploaded_at)->format('M d, Y') }}</span>
                            <span>•</span>
                        @endif
                        @if($video->duration_seconds)
                            <span>{{ floor($video->duration_seconds / 60) }}:{{ sprintf('%02d', $video->duration_seconds % 60) }}</span>
                            <span>•</span>
                        @endif
                        <span>Included with membership</span>
                    </div>
                </div>

            </div>

            <!-- Up Next Sidebar Column -->
            <div class="watch-sidebar">
                <div class="rowhead" style="margin-bottom: 20px;">
                    <h2 style="font-size: 20px; color: #fff; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <span class="bar" style="display: inline-block; width: 4px; height: 18px; background: #68d391; border-radius: 2px;"></span>
                        Up next
                    </h2>
                </div>

                <div class="upnext-cards" style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($upNextVideos as $next)
                        <a href="{{ route('watch', ['videoId' => $next->id, 'from' => request('from', 'deep-dives')]) }}" style="text-decoration: none; display: block;">
                            <div class="sidebar-vcard" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden; transition: transform 0.2s ease, border-color 0.2s ease;">
                                
                                <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; overflow: hidden;">
                                    <img 
                                        src="{{ $next->thumbnail_url ?: asset('site/assets/hero.jpg') }}" 
                                        alt="{{ $next->title }}"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                    >
                                    
                                    <span style="position: absolute; bottom: 8px; left: 8px; background: rgba(0,0,0,0.8); color: #68d391; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 3px; letter-spacing: 0.5px; text-transform: uppercase;">
                                        {{ strtoupper($next->section ?: 'DEEP DIVE') }}
                                    </span>

                                    @if($next->duration_seconds)
                                        <span style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.8); color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 3px;">
                                            {{ floor($next->duration_seconds / 60) }}:{{ sprintf('%02d', $next->duration_seconds % 60) }}
                                        </span>
                                    @endif
                                </div>

                                <div style="padding: 12px 14px;">
                                    <h3 style="color: #ffffff; font-size: 14px; font-weight: 600; line-height: 1.4; margin: 0 0 6px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $next->title }}
                                    </h3>
                                    <span style="font-size: 12px; color: rgba(255,255,255,0.5);">
                                        Included with membership
                                    </span>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://assets.mediadelivery.net/playerjs/player-0.1.0.min.js"></script>
<script>
(function () {
    var videoId       = "{{ $video->id }}";
    var csrfToken     = "{{ csrf_token() }}";
    var storeUrl      = "{{ route('video.progress.store') }}";
    var totalDuration = {{ (int) ($video->duration_seconds ?: 0) }};

    var lastSavedSecond = -1;
    var lastSaveAt      = 0;
    var latest          = { seconds: 0, duration: totalDuration };

    var resumeAt = {{ (int) ($resumeSeconds ?? 0) }};
    var canSave  = resumeAt <= 0;

    function save(force) {
        var sec = Math.round(latest.seconds);
        var dur = Math.round(latest.duration || totalDuration);

        if (!canSave || sec <= 0 || dur <= 0) return;
        if (!force && sec === lastSavedSecond) return;

        lastSavedSecond = sec;
        lastSaveAt = Date.now();

        var percent = Math.min(100, Math.round((sec / dur) * 100));

        fetch(storeUrl, {
            method: "POST",
            keepalive: true,
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                video_id: videoId,
                progress_seconds: sec,
                duration_seconds: dur,
                progress_percent: percent
            })
        })
        .then(function (res) { return res.json(); })
        .then(function () { console.log("Saved progress:", sec, "/", dur); })
        .catch(function (err) { console.error("Progress save failed:", err); });
    }

    var iframe = document.getElementById("bunny-stream-player");
    if (!iframe || typeof playerjs === "undefined") return;

    var player = new playerjs.Player(iframe);

    player.on("ready", function () {
        if (resumeAt > 0) {
            player.setCurrentTime(resumeAt);
            setTimeout(function () { canSave = true; }, 3000);
        }

        
        player.on("timeupdate", function (d) {
            latest.seconds  = d.seconds;
            latest.duration = d.duration || totalDuration;
            if (Date.now() - lastSaveAt >= 5000) save(false);
        });

        player.on("pause", function () { save(true); });

        player.on("ended", function () {
            latest.seconds = latest.duration || totalDuration;
            save(true);
        });
    });

    // tab close / page change par last position save
    window.addEventListener("beforeunload", function () { save(true); });
})();
</script>
@endpush