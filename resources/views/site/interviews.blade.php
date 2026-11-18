@extends('site.layouts.app')

@section('title', 'Gene Decode — Interviews & Videos')

@section('page', 'interviews')

@section('content')

@php
    $userProgressMap = [];
    if (auth()->check()) {
        $userProgressMap = \App\Models\VideoProgress::where('user_id', auth()->id())
            ->whereIn('video_id', $videos->pluck('id')->map(fn($id) => (string)$id))
            ->pluck('progress_percent', 'video_id')
            ->toArray();
    }
    $activeFilter = $filter ?? 'all';
@endphp

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Free public library</span>
        <h1>Interviews &amp; Videos</h1>
        <p>
            Gene's interviews from across many platforms, free to watch.
            Topics include Current Events, DUMBs, World Government, True History,
            and more. For the full library, subscribe to Deep Dives.
        </p>

        @if(!auth()->check() || !auth()->user()->hasActiveSubscription())
            <div style="margin-top:16px">
                <a class="btn" href="{{ url('/join-us') }}">
                    Subscribe to Deep Dives
                </a>
            </div>
        @endif
    </div>
</section>

<section>
    <div class="wrap">

        <!-- Header + Search Form -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px; margin-bottom: 20px;">
            <div class="rowhead" style="margin: 0;">
                <h2><span class="bar"></span>Browse interviews</h2>
            </div>

            <form method="GET" action="{{ route('interviews') }}" style="display: flex; gap: 8px; max-width: 320px; width: 100%;">
                <input type="hidden" name="filter" value="{{ $activeFilter }}">
                <div style="position: relative; width: 100%;">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search ?? '' }}" 
                        placeholder="Search interviews..." 
                        style="width: 100%; padding: 8px 34px 8px 12px; background: #0d1527; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; color: #fff; font-size: 13.5px;"
                    >
                    @if(!empty($search))
                        <a href="{{ route('interviews', ['filter' => $activeFilter]) }}" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.4); text-decoration: none; font-size: 16px;">&times;</a>
                    @endif
                </div>
                <button type="submit" class="btn sm" style="padding: 8px 14px;">Search</button>
            </form>
        </div>

        <!-- Working Filter Chips -->
        <div class="chips" id="iv-chips" style="margin-bottom: 24px;">
            <a href="{{ route('interviews', array_filter(['filter' => 'all', 'q' => $search ?? null])) }}" 
               class="chip {{ $activeFilter === 'all' ? 'on' : '' }}" style="text-decoration: none;">All</a>
            <a href="{{ route('interviews', array_filter(['filter' => 'newest', 'q' => $search ?? null])) }}" 
               class="chip {{ $activeFilter === 'newest' ? 'on' : '' }}" style="text-decoration: none;">Newest</a>
            <a href="{{ route('interviews', array_filter(['filter' => 'oldest', 'q' => $search ?? null])) }}" 
               class="chip {{ $activeFilter === 'oldest' ? 'on' : '' }}" style="text-decoration: none;">Oldest</a>
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
                                    @php
                                        $hours = floor($item->duration_seconds / 3600);
                                        $minutes = floor(($item->duration_seconds % 3600) / 60);
                                        $seconds = $item->duration_seconds % 60;
                                    @endphp

                                    @if($hours > 0)
                                        {{ $hours }}:{{ sprintf('%02d', $minutes) }}:{{ sprintf('%02d', $seconds) }}
                                    @else
                                        {{ $minutes }}:{{ sprintf('%02d', $seconds) }}
                                    @endif
                                </span>
                            @endif

                            @auth
                                @php $progressPercent = $userProgressMap[(string)$item->id] ?? 0; @endphp
                                @if($progressPercent > 0)
                                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: rgba(255,255,255,0.2);">
                                        <div style="width: {{ $progressPercent }}%; height: 100%; background: #7cc242; transition: width 0.3s ease;"></div>
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
                    @if(!empty($search))
                        No interview videos matching "<strong>{{ e($search) }}</strong>".
                        <a href="{{ route('interviews') }}" style="color: #7cc242; margin-left: 8px; text-decoration: underline;">Clear search</a>
                    @else
                        No interview videos available.
                    @endif
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