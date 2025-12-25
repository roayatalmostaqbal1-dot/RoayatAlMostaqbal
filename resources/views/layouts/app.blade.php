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
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Language Alternate Links -->
    @if(app()->getLocale() === 'ar')
        <link rel="alternate" hreflang="en" href="{{ route(Route::current()->getName(), [...Route::current()->parameters(), 'locale' => 'en']) }}" />
    @else
        <link rel="alternate" hreflang="ar" href="{{ route(Route::current()->getName(), [...Route::current()->parameters(), 'locale' => 'ar']) }}" />
    @endif
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />

    <!-- Open Graph Meta Tags (محسّن للعربية والإنجليزية) -->
    <meta property="og:title" content="@yield('title', __('messages.header.title')) - رؤية المستقبل">
    <meta property="og:description" content="@yield('description', __('messages.header.subtitle'))">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
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
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="apple-mobile-web-app-title" content="رؤية المستقبل">
    <meta name="application-name" content="رؤية المستقبل">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "رؤية المستقبل - Roayat Al Mostaqbal",
        "alternateName": "Roayat Al Mostaqbal",
        "url": "{{ url('/') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('RoayatAlMostaqbal.svg') }}",
            "width": 256,
            "height": 256
        },
        "description": "@yield('description', __('messages.header.subtitle'))",
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Support",
            "availableLanguage": ["ar", "en"]
        },
        "sameAs": [
            @yield('social_media', '')
        ]
    }
    </script>
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
