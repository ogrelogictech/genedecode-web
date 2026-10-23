@extends('site.layouts.app')

@section('title', 'gene Decode — Surface Area')

@section('page', 'surface')

@section('content')

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
        <div class="grid" id="free-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @forelse($freeVideos as $item)
                <div class="vcard-item" style="background: #0d1527; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
                    <a href="{{ route('watch', ['videoId' => $item->id, 'from' => 'surface-area']) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                        
                        <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; overflow: hidden;">
                            <img 
                                src="{{ $item->thumbnail_url ?: asset('site/assets/hero.jpg') }}" 
                                alt="{{ $item->title }}"
                                style="width: 100%; height: 100%; object-fit: cover;"
                            >

                            <span style="position: absolute; bottom: 6px; left: 6px; background: rgba(0,0,0,0.85); color: #68d391; font-size: 9px; font-weight: 800; padding: 2px 5px; border-radius: 2px; letter-spacing: 0.5px; text-transform: uppercase;">
                                FREE
                            </span>

                            @if($item->duration_seconds)
                                <span style="position: absolute; bottom: 6px; right: 6px; background: rgba(0,0,0,0.85); color: #ffffff; font-size: 10px; padding: 2px 5px; border-radius: 2px; font-weight: 600;">
                                    {{ floor($item->duration_seconds / 60) }}:{{ sprintf('%02d', $item->duration_seconds % 60) }}
                                </span>
                            @endif

                            <!-- Watch Progress Bar Line -->
                            @auth
                                @php
                                    $userProgress = \App\Models\VideoProgress::where('user_id', auth()->id())
                                        ->where('video_id', (string) $item->id)
                                        ->first();
                                    $progressPercent = $userProgress ? $userProgress->progress_percent : 0;
                                @endphp

                                @if($progressPercent > 0)
                                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: rgba(255,255,255,0.2);">
                                        <div style="width: {{ $progressPercent }}%; height: 100%; background: #68d391; transition: width 0.3s ease;"></div>
                                    </div>
                                @endif
                            @endauth
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
                    No free videos available at the moment.
                </div>
            @endforelse
        </div>

        <!-- Custom Clean Pagination -->
        @if ($freeVideos->hasPages())
            <div class="custom-pagination" style="margin-top: 40px; display: flex; justify-content: center; align-items: center; gap: 8px;">
                @if ($freeVideos->onFirstPage())
                    <span style="padding: 6px 12px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border-radius: 4px; font-size: 13px; cursor: not-allowed;">&laquo; Prev</span>
                @else
                    <a href="{{ $freeVideos->previousPageUrl() }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">&laquo; Prev</a>
                @endif

                @foreach ($freeVideos->getUrlRange(1, $freeVideos->lastPage()) as $page => $url)
                    @if ($page == $freeVideos->currentPage())
                        <span style="padding: 6px 12px; background: #7cc242; color: #0a101d; font-weight: 700; border-radius: 4px; font-size: 13px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($freeVideos->hasMorePages())
                    <a href="{{ $freeVideos->nextPageUrl() }}" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: #fff; border-radius: 4px; font-size: 13px; text-decoration: none;">Next &raquo;</a>
                @else
                    <span style="padding: 6px 12px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border-radius: 4px; font-size: 13px; cursor: not-allowed;">Next &raquo;</span>
                @endif
            </div>
        @endif

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