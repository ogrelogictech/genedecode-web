@extends('site.layouts.app')

@section('title', 'Gene Decode — Watch')
@section('page', 'deep-dives')

@section('content')

<section>
    <div class="wrap watchgrid">

        <div>
            <!-- Bunny.net Gated Iframe Player -->
            <div id="stage" class="ratio ratio-16x9" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; border-radius: 8px;">
                <iframe 
                    id="bunny-player"
                    src="{{ $embedUrl }}" 
                    loading="lazy" 
                    style="border: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" 
                    allowfullscreen="true">
                </iframe>
            </div>

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
                    and the live Q&A replay. Your progress is saved automatically,
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
    var params = new URLSearchParams(window.location.search);
    document.body.dataset.page = "deep-dives";

    var currentVideoId = "{{ $videoId }}";

    // Progress Saving logic for Bunny Embed Player via postMessage API
    var iframe = document.getElementById('bunny-player');
    var lastSavedTime = 0;

    window.addEventListener('message', function(event) {
        // Ensure message comes from Bunny player
        if (!event.origin.includes('mediadelivery.net')) return;

        try {
            var data = JSON.parse(event.data);

            // Playback progress update from Bunny player
            if (data.event === 'timeupdate') {
                var currentTime = data.value.currentTime;
                var duration = data.value.duration;

                if (!duration || currentTime - lastSavedTime < 5) return;
                lastSavedTime = currentTime;

                var progressPercent = (currentTime / duration) * 100;

                fetch("{{ route('video.progress.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        video_id: currentVideoId,
                        progress_seconds: currentTime,
                        duration_seconds: duration,
                        progress_percent: progressPercent
                    })
                });
            }
        } catch (e) {
            // Non-JSON message handler
        }
    });
</script>

@endpush