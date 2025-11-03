@extends('layouts.app')

@section('title', __('messages.nav.about') . ' - ' . __('messages.header.title'))
@section('description', __('messages.about.description'))

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">{{ __('messages.nav.about') }}</h1>
            <p class="text-lg text-gray-300">{{ __('messages.about.subtitle') }}</p>
        </div>
    </section>

    <!-- About Features -->
    <div class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Headquarters -->
                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.about.feature.headquarters.title') }}</h3>
                        <ul class="text-gray-300">
                            <li>{{ __('messages.about.feature.headquarters.description') }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.why.vision.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.why.vision.description') }}</p>
                    </div>
                </div>

                <!-- Mission -->
                <div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl text-[#27e9b5] mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ __('messages.why.mission.title') }}</h3>
                        <p class="text-gray-300">{{ __('messages.why.mission.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Services Overview -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.services.title') }}</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">{{ __('messages.about.services.description') }}</p>
            </div>

            <div>
                <div class="flex flex-wrap gap-4 justify-center mb-12">
                    <button class="tab-btn active px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-[#27e9b5] text-[#051824]"
                        data-tab="security">{{ __('messages.about.services.security.VisionOfTheFutureSecuritySection') }}</button>
                    <button class="tab-btn px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824]"
                        data-tab="technology">{{ __('messages.about.services.technology.SaddDepartmentTechnology') }}</button>
                </div>

                <div>
                    <div class="tab-panel active" id="security">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-video"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.servers.title.1') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.servers.title.2') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.servers.title.3') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-key"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.servers.title.4') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-home"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.servers.title.5') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="tab-panel hidden" id="technology">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.services.sadd.item.1') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-digital-tachograph"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.services.Sadd.item.2') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.services.Sadd.item.3') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.services.Sadd.item.4') }}</h3>
                            </div>
                            <div class="bg-[#051824] p-6 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                                <div class="text-4xl text-[#27e9b5] mb-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <h3 class="text-white font-bold">{{ __('messages.about.services.Sadd.item.5') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.why.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">01</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason1') }}</h3>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">02</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason2') }}</h3>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">03</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason3') }}</h3>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">04</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason4') }}</h3>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">05</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason5') }}</h3>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-4">06</div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.why.reason6') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-[linear-gradient(135deg,#3b5265_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.about.more.title') }}</h2>
            <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">{{ __('messages.about.more.description') }}</p>

            <a href="{{ route('contact', app()->getLocale()) }}"
                class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.nav.contact') }}</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class and styles from all buttons and panels
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-[#27e9b5]', 'text-[#051824]');
                        btn.classList.add('bg-transparent', 'text-white', 'border-2', 'border-[#27e9b5]');
                    });
                    tabPanels.forEach(panel => panel.classList.add('hidden'));

                    // Add active class and styles to clicked button and corresponding panel
                    this.classList.add('active', 'bg-[#27e9b5]', 'text-[#051824]');
                    this.classList.remove('bg-transparent', 'text-white', 'border-2', 'border-[#27e9b5]');
                    document.getElementById(targetTab).classList.remove('hidden');
                });
            });
        });
    </script>
@endpush
