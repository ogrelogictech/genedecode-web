<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Gene Decode')</title>
    <link rel="icon" href="{{ asset('site/assets/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('site/css/styles.css') }}">
</head>

<body data-page="@yield('page')">

    @include('site.partials.header')

    <main>
        @yield('content')
    </main>

    @include('site.partials.footer')

    <script src="{{ asset('site/js/app.js') }}"></script>
    <script src="{{ asset('site/js/catalog.js') }}"></script>

    @stack('scripts')

</body>
</html>