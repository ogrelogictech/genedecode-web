<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $seoTitle = trim($__env->yieldContent('title', 'Gene Decode — Deep Dives, Interviews & Truth Research'));
        $seoDescription = trim($__env->yieldContent('meta_description', 'Watch Gene Decode Deep Dives, Q&A Zooms, Pearls of Wisdom, and free interviews covering world history, current events, and in-depth research.'));
        $seoImage = trim($__env->yieldContent('meta_image', asset('site/assets/hero.jpg')));
        $canonicalUrl = trim($__env->yieldContent('canonical', url()->current()));
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / Social Meta -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Gene Decode">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    @stack('meta')
    <link rel="icon" href="{{ asset('site/assets/logo.png') }}">

    <!-- Google Tag Manager -->
    <script>
        window.dataLayer = window.dataLayer || [];
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-K7XL2NL');
    </script>
    <!-- End Google Tag Manager -->

    <link rel="stylesheet" href="{{ asset('site/css/styles.css') }}">
</head>

<body data-page="@yield('page')">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K7XL2NL"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @if(session()->pull('ga_sign_up'))
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'sign_up',
                'method': 'email'
            });
        </script>
    @endif

    @include('site.partials.header')

    <main>
        @include('site.partials.notification')
        @yield('content')
    </main>

    @include('site.partials.footer')
    @include('site.partials.cookie-consent')

    <script src="{{ asset('site/js/app.js') }}"></script>
    <script src="{{ asset('site/js/catalog.js') }}"></script>

    @stack('scripts')

</body>
</html>