<!-- Header Component -->
<header class="header">
    <div class="container">
        <!-- Top Contact Bar -->
        <div class="header-top">
            <div class="contact-info"></div>

            <!-- Language Switcher -->
            <div class="language-switcher">
                <a href="{{ route('language.switch', 'ar') }}"
                   class="lang-link {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    العربية
                </a>
                <span class="lang-separator">|</span>
                <a href="{{ route('language.switch', 'en') }}"
                   class="lang-link {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    English
                </a>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar">
            <div class="nav-brand">
                <!-- ✅ Add your logo here -->
                <a href="{{ route('home', app()->getLocale()) }}" class="logo-link">
                    <img src="{{ asset('RoayatAlMostaqbal.svg') }}" alt="Site Logo" class="logo">
                </a>

                <div class="brand-text">
                    <h1>{{ __('messages.header.title') }}</h1>
                    <p>{{ __('messages.header.subtitle') }}</p>
                </div>
            </div>

            <ul class="nav-menu">
                <li><a href="{{ route('home', app()->getLocale()) }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav.home') }}</a></li>
                <li><a href="{{ route('about', app()->getLocale()) }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('messages.nav.about') }}</a></li>
                <li><a href="{{ route('services', app()->getLocale()) }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">{{ __('messages.nav.services') }}</a></li>
                <li><a href="{{ route('projects', app()->getLocale()) }}" class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }}">{{ __('messages.nav.projects') }}</a></li>
                <li><a href="{{ route('contact', app()->getLocale()) }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('messages.nav.contact') }}</a></li>
            </ul>

            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </div>
</header>
