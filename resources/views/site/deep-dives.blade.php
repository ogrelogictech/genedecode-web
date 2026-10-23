@extends('site.layouts.app')

@section('title', 'gene Decode — Deep Dives Catalog')
@section('page', 'deep-dives')

@section('content')

@php
    $user = auth()->user();
    $isLoggedIn = auth()->check();
    $isMember = $isLoggedIn && $user->hasActiveSubscription();
@endphp


<style>
    

    /* Video Cards Color & Green Glow Borders */
    .catalog-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
        gap: 20px !important;
    }
    .vcard-box {
        background: #0d1527 !important;
        border: 1px solid rgba(104, 211, 145, 0.22) !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .vcard-box:hover {
        border-color: #A9E07A !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 20px rgba(104, 211, 145, 0.15) !important;
    }
    .vcard-thumb {
        position: relative !important;
        width: 100% !important;
        aspect-ratio: 16/9 !important;
        background: #000000 !important;
        overflow: hidden !important;
    }
    .vcard-thumb img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }
    .vcard-badge-lock {
        position: absolute !important;
        top: 6px !important;
        right: 6px !important;
        background: rgba(0, 0, 0, 0.65) !important;
        color: #ffffff !important;
        padding: 3px 5px !important;
        border-radius: 3px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .vcard-badge-section {
        position: absolute !important;
        bottom: 6px !important;
        left: 6px !important;
        background: rgba(0, 0, 0, 0.85) !important;
        color: #A9E07A !important;
        font-size: 9px !important;
        font-weight: 800 !important;
        padding: 2px 6px !important;
        border-radius: 2px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
    }
    .vcard-badge-duration {
        position: absolute !important;
        bottom: 6px !important;
        right: 6px !important;
        background: rgba(0, 0, 0, 0.85) !important;
        color: #ffffff !important;
        font-size: 10px !important;
        padding: 2px 5px !important;
        border-radius: 2px !important;
        font-weight: 600 !important;
    }
    .vcard-body {
        padding: 12px !important;
        flex-grow: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
    }
    .vcard-title {
        color: #ffffff !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        line-height: 1.35 !important;
        margin: 0 0 6px 0 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    .vcard-date {
        font-size: 11px !important;
        color: rgba(255, 255, 255, 0.45) !important;
        margin-top: 6px !important;
    }
</style>

<section class="pagehero">
    <div class="wrap">
        <span class="kicker">Members library</span>
        <h1>Deep Dives Catalog</h1>
        <p>
            Every Deep Dive video, Q&amp;A Zoom, exclusive decode, and monthly content, in one place. Membership is required to watch.
        </p>
    </div>
</section>

<section>
    <div class="wrap">

        <!-- Separate Lock Bar & Get Membership Pill -->
        <div class="lockbar">
            <span class="locknote">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                Membership required to watch</span>
            <a class="btn sm" href="{{ url('join-us') }}">Get Membership</a>
        </div>

        <!-- Section Title -->
        <div class="rowhead" style="margin-bottom: 16px;">
            <h2><span class="bar"></span>
                Browse the library
            </h2>
        </div>

        <!-- Filter Chips with Links -->
        @php $cat = $categoryFilter ?? 'all'; @endphp
        <div class="chips" id="dd-chips">
            <span class="chip on" data-val="All">All</span>
            <span class="chip" data-val="Deep Dive">Deep Dives</span>
            <span class="chip" data-val="Pearls of Wisdom">Pearls of Wisdom</span>
            <span class="chip" data-val="Q&amp;A">Q&amp;A Zooms</span>
        </div>

        <!-- Video Catalog Grid -->
        <div class="catalog-grid">
            @forelse($videos as $item)
                @php
                    if (! $isLoggedIn) {
                        $targetUrl = route('login');
                        $linkClass = 'guest-link';
                    } elseif ($isMember) {
                        $targetUrl = route('watch', ['videoId' => $item->id, 'from' => 'deep-dives']);
                        $linkClass = 'member-link';
                    } else {
                        $targetUrl = 'javascript:void(0)';
                        $linkClass = 'non-member-link disabled-link';
                    }
                @endphp

                <div class="vcard-box">
                    <a href="{{ $targetUrl }}" 
                       class="{{ $linkClass }}"
                       style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                        
                        <div class="vcard-thumb">
                            <img src="{{ $item->thumbnail_url ?: asset('site/assets/hero.jpg') }}" alt="{{ $item->title }}">
                            
                            @if(! $isMember)
                                <span class="vcard-badge-lock" title="Membership required">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                                </span>
                            @endif

                            <span class="vcard-badge-section">
                                {{ strtoupper($item->section ?: 'DEEP DIVE') }}
                            </span>

                            @if($item->duration_seconds)
                                <span class="vcard-badge-duration">
                                    {{ floor($item->duration_seconds / 60) }}:{{ sprintf('%02d', $item->duration_seconds % 60) }}
                                </span>
                            @endif

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

                        <div class="vcard-body">
                            <div>
                                <h3 class="vcard-title">
                                    {{ $item->title }}
                                </h3>
                            </div>

                            <div class="vcard-date">
                                @if($item->uploaded_at)
                                    <span>{{ \Carbon\Carbon::parse($item->uploaded_at)->format('M d') }}</span>
                                @endif
                            </div>
                        </div>

                    </a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; color: rgba(255,255,255,0.6); padding: 40px 0; text-align: center;">
                    No videos found in this category.
                </div>
            @endforelse
        </div>

        <!-- Custom Clean Pagination -->
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