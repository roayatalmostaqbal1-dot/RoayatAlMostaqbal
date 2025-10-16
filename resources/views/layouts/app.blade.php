<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.header.title'))</title>
    <meta name="description" content="@yield('description', __('messages.header.subtitle'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!-- Styles -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('هوية رؤية المستقبل-04.svg') }}" />

     @vite(['resources/css/app.css', 'respath: ources/js/app.js'])

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <x-header />

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>


