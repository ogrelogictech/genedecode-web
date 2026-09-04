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

        <div class="rowhead">
            <h2>
                <span class="bar"></span>
                Continue watching
            </h2>
        </div>

        <div class="rowscroll" id="row-continue"></div>

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
    render(
        "row-continue",
        VIDEOS.filter(function(v) {
            return v.p > 0;
        })
    );

    function ddList(val) {
        return val === "All" ? VIDEOS : byCat(val);
    }

    render("grid-dd", ddList("All"));

    wireChips("#dd-chips", "grid-dd", ddList);
</script>
@endpush