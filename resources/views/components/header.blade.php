<!-- Header Component -->
<header
    class="fixed top-0   left-0 right-0 z-[1000] bg-gradient-to-r from-[#051824] to-[#162936] shadow-[0_2px_20px_rgba(0,0,0,0.3)] transition-all duration-300">
    <div class="max-w-6xl mx-auto px-5">
        <!-- Top Contact Bar -->
        <div class="bg-[#162936] py-2.5 border-b border-[#3b5265]">
            <div class="contact-info"></div>

            <!-- Language Switcher (Desktop only) -->
            <div class="hidden md:flex gap-2 items-center">
                <a href="{{ route('language.switch', 'ar') }}" rel="nofollow"
                    class="text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 text-sm {{ app()->getLocale() == 'ar' ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">
                    العربية
                </a>
                <span class="text-white">|</span>
                <a href="{{ route('language.switch', 'en') }}" rel="nofollow"
                    class="text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 text-sm {{ app()->getLocale() == 'en' ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">
                    English
                </a>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="flex list-none gap-5 items-center justify-between py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('home', app()->getLocale()) }}" class="no-underline">
                    <img src="{{ asset('RoayatAlMostaqbal.svg') }}" alt="Site Logo"
                        class="h-[50px] w-auto object-contain">
                </a>

                <div>
                    <h1 class="text-xl font-bold text-white">{{ __('messages.header.title') }}</h1>
                    <p class="text-xs text-gray-400">{{ __('messages.header.subtitle') }}</p>
                </div>
            </div>

            <ul class="nav-menu hidden md:flex list-none gap-5 items-center m-0 p-0">
                <li><a href="{{ route('home', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 relative text-sm whitespace-nowrap {{ request()->routeIs('home') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">{{ __('messages.nav.home') }}</a>
                </li>
                <li><a href="{{ route('about', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 relative text-sm whitespace-nowrap {{ request()->routeIs('about') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">{{ __('messages.nav.about') }}</a>
                </li>
                <li><a href="{{ route('services', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 relative text-sm whitespace-nowrap {{ request()->routeIs('services') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">{{ __('messages.nav.services') }}</a>
                </li>
                <li><a href="{{ route('projects', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 relative text-sm whitespace-nowrap {{ request()->routeIs('projects') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">{{ __('messages.nav.projects') }}</a>
                </li>
                <li><a href="{{ route('contact', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 relative text-sm whitespace-nowrap {{ request()->routeIs('contact') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">{{ __('messages.nav.contact') }}</a>
                </li>
            </ul>

            <div class="md:hidden flex flex-col gap-1 cursor-pointer nav-toggle" id="nav-toggle">
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
            </div>
        </nav>

        <div
            class="nav-menu hidden md:hidden bg-[#051824] border-t border-[#3b5265] max-h-0 overflow-hidden transition-all duration-300">
            <ul class="list-none m-0 p-4 space-y-2">
                <li><a href="{{ route('home', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-3 rounded-lg transition-all duration-300 block {{ request()->routeIs('home') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#162936]' }}">{{ __('messages.nav.home') }}</a>
                </li>
                <li><a href="{{ route('about', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-3 rounded-lg transition-all duration-300 block {{ request()->routeIs('about') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#162936]' }}">{{ __('messages.nav.about') }}</a>
                </li>
                <li><a href="{{ route('services', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-3 rounded-lg transition-all duration-300 block {{ request()->routeIs('services') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#162936]' }}">{{ __('messages.nav.services') }}</a>
                </li>
                <li><a href="{{ route('projects', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-3 rounded-lg transition-all duration-300 block {{ request()->routeIs('projects') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#162936]' }}">{{ __('messages.nav.projects') }}</a>
                </li>
                <li><a href="{{ route('contact', app()->getLocale()) }}"
                        class="nav-link text-white no-underline font-semibold px-4 py-3 rounded-lg transition-all duration-300 block {{ request()->routeIs('contact') ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#162936]' }}">{{ __('messages.nav.contact') }}</a>
                </li>

                <!-- Mobile Language Switcher -->
                <li class="border-t border-[#3b5265] pt-2 mt-2">
                    <div class="flex gap-2 items-center justify-center">
                        <a href="{{ route('language.switch', 'ar') }}" rel="nofollow"
                            class="text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 text-sm {{ app()->getLocale() == 'ar' ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">
                            العربية
                        </a>
                        <span class="text-white">|</span>
                        <a href="{{ route('language.switch', 'en') }}" rel="nofollow"
                            class="text-white no-underline font-semibold px-4 py-2 rounded-full transition-all duration-300 text-sm {{ app()->getLocale() == 'en' ? 'bg-[#27e9b5] text-[#051824]' : 'hover:bg-[#27e9b5] hover:text-[#051824]' }}">
                            English
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
