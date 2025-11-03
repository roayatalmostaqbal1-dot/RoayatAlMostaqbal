@extends('layouts.app')

@section('title', __('messages.nav.home') . ' - ' . __('messages.header.title'))
@section('description', __('messages.hero.subtitle'))

@section('content')
    <!-- Hero Section -->
    <x-hero />

    <!-- About Section -->
    <section id="about" class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.title') }}</h2>
                <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">{{ __('messages.about.subtitle') }}</p>
                <a href="{{ route('services', app()->getLocale()) }}" class="bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824] inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base">{{ __('messages.about.button') }}</a>
            </div>
        </div>
    </section>

    <!-- Services Preview Section -->
    <section id="services" class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.services.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <x-service-card
                    icon="fas fa-shield-alt"
                    :title="__('messages.services.surveillance.title')"
                    :description="__('messages.services.surveillance.description')"
                    :link="route('services', app()->getLocale())"
                />

                <x-service-card
                    icon="fas fa-bell"
                    :title="__('messages.services.alarm.title')"
                    :description="__('messages.services.alarm.description')"
                    :link="route('services', app()->getLocale())"
                />

                <x-service-card
                    icon="fas fa-door-open"
                    :title="__('messages.services.gates.title')"
                    :description="__('messages.services.gates.description')"
                    :link="route('services', app()->getLocale())"
                />

                <x-service-card
                    icon="fas fa-home"
                    :title="__('messages.services.smart_home.title')"
                    :description="__('messages.services.smart_home.description')"
                    :link="route('services', app()->getLocale())"
                />
            </div>
        </div>
    </section>

    <!-- Technology Section -->
    <section class="py-20 bg-[linear-gradient(135deg,#3b5265_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">{{ __('messages.tech.title') }}</h2>
                    <p class="text-gray-300 mb-8">{{ __('messages.about.description') }}</p>

                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="text-3xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-shield-virus"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white mb-2">{{ __('messages.tech.feature.family.title') }}</h4>
                                <p class="text-gray-300">{{ __('messages.tech.feature.family.description') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="text-3xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white mb-2">{{ __('messages.tech.feature.business.title') }}</h4>
                                <p class="text-gray-300">{{ __('messages.tech.feature.business.description') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="text-3xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-server"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white mb-2">{{ __('messages.tech.feature.servers.title') }}</h4>
                                <p class="text-gray-300">{{ __('messages.tech.feature.servers.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-gradient-to-r from-[#27e9b5] to-[#27eb5] p-8 rounded-[20px] text-center">
                        <span class="text-5xl font-bold text-[#051824] block mb-2">20+</span>
                        <span class="text-lg font-bold text-[#051824]">{{ __('messages.tech.experience') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Preview Section -->
    <section id="projects" class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.projects.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <x-project-card
                    image="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project1.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project2.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project3.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project4.title')"
                />
            </div>

            <div class="text-center">
                <a href="{{ route('projects', app()->getLocale()) }}" class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.common.learn_more') }}</a>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">{{ __('messages.why.title') }}</h2>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason1') }}</li>
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason2') }}</li>
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason3') }}</li>
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason4') }}</li>
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason5') }}</li>
                        <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check text-[#27e9b5]"></i> {{ __('messages.why.reason6') }}</li>
                    </ul>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-[#051824] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                        <div class="text-4xl text-[#27e9b5] mb-4">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.why.vision.title') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.why.vision.description') }}</p>
                        <a href="{{ route('about', app()->getLocale()) }}" class="text-[#27e9b5] hover:text-white transition-colors font-semibold">{{ __('messages.why.read_more') }} →</a>
                    </div>

                    <div class="bg-[#051824] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                        <div class="text-4xl text-[#27e9b5] mb-4">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.why.mission.title') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.why.mission.description') }}</p>
                        <a href="{{ route('about', app()->getLocale()) }}" class="text-[#27e9b5] hover:text-white transition-colors font-semibold">{{ __('messages.why.read_more') }} →</a>
                    </div>

                    <div class="bg-[#051824] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                        <div class="text-4xl text-[#27e9b5] mb-4">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.why.awards.title') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.why.awards.description') }}</p>
                        <a href="{{ route('about', app()->getLocale()) }}" class="text-[#27e9b5] hover:text-white transition-colors font-semibold">{{ __('messages.why.read_more') }} →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-[linear-gradient(135deg,#3b5265_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.newsletter.title') }}</h2>
                <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">{{ __('messages.newsletter.subtitle') }}</p>
                <form class="flex flex-col md:flex-row gap-4 justify-center items-center mb-6" id="newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('messages.newsletter.placeholder') }}" class="px-4 py-3 rounded-[10px] bg-white text-[#051824] border-none focus:outline-none w-full md:w-64" required>
                    <button type="submit" class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.newsletter.button') }}</button>
                </form>
                <p class="text-gray-300">{{ __('messages.newsletter.website') }}</p>
            </div>
        </div>
    </section>
@endsection


