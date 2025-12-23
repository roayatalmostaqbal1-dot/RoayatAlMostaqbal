<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>@yield('title', __('messages.header.title'))</title>
    <meta name="description" content="@yield('description', __('messages.header.subtitle'))">
    <meta name="keywords" content="@yield('keywords', 'رؤية المستقبل, استشارات, تقنية, حلول برمجية, تحول رقمي')">
    <meta name="author" content="Roayat Al Mostaqbal">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', __('messages.header.title'))">
    <meta property="og:description" content="@yield('description', __('messages.header.subtitle'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og:image', asset('RoayatAlMostaqbal.svg'))">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('messages.header.title'))">
    <meta name="twitter:description" content="@yield('description', __('messages.header.subtitle'))">
    <meta name="twitter:image" content="@yield('og:image', asset('RoayatAlMostaqbal.svg'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">


    <!-- Styles -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('RoayatAlMostaqbal.svg') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <x-header />

    <!-- Main Content -->
    <main class="mt-24">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
