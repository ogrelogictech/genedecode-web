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

    function ivList(val) {
        if (val === "Newest") {
            return IV.slice().reverse();
        }

        if (val === "Most popular") {
            return IV.slice().sort(function(a, b) {
                return (b.p || 0) - (a.p || 0);
            });
        }

        return IV;
    }

    render("grid-interviews", ivList("All"));
    wireChips("#iv-chips", "grid-interviews", ivList);
</script>
@endpush