<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title>@yield('title', __('messages.header.title')) - رؤية المستقبل</title>
    <meta name="description" content="@yield('description', __('messages.header.subtitle'))">
    <meta name="keywords" content="@yield('keywords', 'رؤية المستقبل, استشارات, تقنية, حلول برمجية, تحول رقمي, استشارات تقنية, خدمات رقمية, تطوير التطبيقات')">
    <meta name="author" content="Roayat Al Mostaqbal">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#ffffff">

    <!-- Canonical URL -->
    @php
        $canonicalUrl = url()->current();

        // Ensure canonical URL always uses www and https
        $canonicalUrl = preg_replace('#^https?://(www\.)?#', 'https://www.', $canonicalUrl);
        // If domain doesn't have www, add it
if (!str_contains($canonicalUrl, 'www.')) {
    $canonicalUrl = str_replace('https://', 'https://www.', $canonicalUrl);
        }
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Language Alternate Links -->
    @php
        $routeName = Route::currentRouteName();
        $routeParams = Route::current() ? Route::current()->parameters() : [];

        // Helper function to ensure URL has www
        $ensureWww = function ($url) {
            $url = preg_replace('#^https?://(www\.)?#', 'https://www.', $url);
            if (!str_contains($url, 'www.')) {
                $url = str_replace('https://', 'https://www.', $url);
            }
            return $url;
        };
    @endphp
    @if (app()->getLocale() === 'ar' && $routeName)
        <link rel="alternate" hreflang="en"
            href="{{ $ensureWww(route($routeName, [...$routeParams, 'locale' => 'en'])) }}" />
    @elseif($routeName)
        <link rel="alternate" hreflang="ar"
            href="{{ $ensureWww(route($routeName, [...$routeParams, 'locale' => 'ar'])) }}" />
    @endif
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}" />

    <!-- Open Graph Meta Tags (محسّن للعربية والإنجليزية) -->
    <meta property="og:title" content="@yield('title', __('messages.header.title')) - رؤية المستقبل">
    <meta property="og:description" content="@yield('description', __('messages.header.subtitle'))">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="@yield('og:image', asset('RoayatAlMostaqbal.svg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ app()->getLocale() == 'ar' ? 'ar_AR' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() == 'ar' ? 'en_US' : 'ar_AR' }}">
    <meta property="og:site_name" content="رؤية المستقبل - Roayat Al Mostaqbal">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('messages.header.title')) - رؤية المستقبل">
    <meta name="twitter:description" content="@yield('description', __('messages.header.subtitle'))">
    <meta name="twitter:image" content="@yield('og:image', asset('RoayatAlMostaqbal.svg'))">
    <meta name="twitter:creator" content="@RoayatAlMostaqbal">

    <!-- Additional SEO Meta Tags -->
    <meta name="google-site-verification" content="@yield('google-verification', '')">
    <meta name="msvalidate.01" content="@yield('bing-verification', '')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('RoayatAlMostaqbal.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('RoayatAlMostaqbal.svg') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="apple-mobile-web-app-title" content="رؤية المستقبل">
    <meta name="application-name" content="رؤية المستقبل">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- Structured Data (JSON-LD) -->

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
