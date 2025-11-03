@extends('layouts.app')

@section('title', __('messages.nav.services') . ' - ' . __('messages.header.title'))
@section('description', __('messages.services.title'))

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">{{ __('messages.nav.services') }}</h1>
            <p class="text-lg text-gray-300">{{ __('messages.services.title') }}</p>
        </div>
    </section>

    <!-- Security Services -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.services.security.install_cameras.title') }}</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">{{ __('messages.about.services.description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <x-service-card
                    icon="fas fa-video"
                    title="{{ __('messages.about.services.security.install_cameras.title') }}"
                    description="{{ __('messages.about.services.security.install_cameras.description') }}"
                />

                <x-service-card
                    icon="fas fa-bell"
                    title="{{ __('messages.about.services.security.alarm_systems.title') }}"
                    description="{{ __('messages.about.services.security.alarm_systems.description') }}"
                />

                <x-service-card
                    icon="fas fa-door-open"
                    title="{{ __('messages.about.services.security.security_gates.title') }}"
                    description="{{ __('messages.about.services.security.security_gates.description') }}"
                />

                <x-service-card
                    icon="fas fa-key"
                    title="{{ __('messages.about.services.security.access_control.title') }}"
                    description="{{ __('messages.about.services.security.access_control.description') }}"
                />

                <x-service-card
                    icon="fas fa-home"
                    title="{{ __('messages.about.services.security.smart_home.title') }}"
                    description="{{ __('messages.about.services.security.smart_home.description') }}"
                />
            </div>
        </div>
    </section>

    <!-- Technology Services -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.services.technology.ai_solutions.title') }}</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">{{ __('messages.about.services.description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <x-service-card
                    icon="fas fa-brain"
                    title="{{ __('messages.about.services.technology.ai_solutions.title') }}"
                    description="{{ __('messages.about.services.technology.ai_solutions.description') }}"
                />

                <x-service-card
                    icon="fas fa-digital-tachograph"
                    title="{{ __('messages.about.services.technology.digital_transformation.title') }}"
                    description="{{ __('messages.about.services.technology.digital_transformation.description') }}"
                />

                <x-service-card
                    icon="fas fa-robot"
                    title="{{ __('messages.about.services.technology.chatbot.title') }}"
                    description="{{ __('messages.about.services.technology.chatbot.description') }}"
                />

                <x-service-card
                    icon="fas fa-desktop"
                    title="{{ __('messages.about.services.technology.monitoring_platforms.title') }}"
                    description="{{ __('messages.about.services.technology.monitoring_platforms.description') }}"
                />

                <x-service-card
                    icon="fas fa-user-check"
                    title="{{ __('messages.about.services.technology.recognition_systems.title') }}"
                    description="{{ __('messages.about.services.technology.recognition_systems.description') }}"
                />
            </div>
        </div>
    </section>

    <!-- Service Process -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.process.title') }}</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">{{ __('messages.about.process.description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">1</div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.process.step1.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.about.process.step1.description') }}</p>
                    </div>
                </div>

                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">2</div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.process.step2.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.about.process.step2.description') }}</p>
                    </div>
                </div>

                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">3</div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.process.step3.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.about.process.step3.description') }}</p>
                    </div>
                </div>

                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">4</div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.process.step4.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.about.process.step4.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Features -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.features.security.title') }}</h3>
                    <p class="text-gray-300">{{ __('messages.about.features.security.description') }}</p>
                </div>

                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.features.usability.title') }}</h3>
                    <p class="text-gray-300">{{ __('messages.about.features.usability.description') }}</p>
                </div>

                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.features.support.title') }}</h3>
                    <p class="text-gray-300">{{ __('messages.about.features.support.description') }}</p>
                </div>

                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">{{ __('messages.about.features.maintenance.title') }}</h3>
                    <p class="text-gray-300">{{ __('messages.about.features.maintenance.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-[linear-gradient(135deg,#3b5265_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.contact_cta.title') }}</h2>
            <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">{{ __('messages.about.contact_cta.description') }}</p>
            <a href="{{ route('contact', app()->getLocale()) }}" class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.about.contact_cta.button') }}</a>
        </div>
    </section>
@endsection


