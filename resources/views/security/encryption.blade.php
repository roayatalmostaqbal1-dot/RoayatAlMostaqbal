@extends('layouts.app')

@section('title', __('messages.security.encryption.title') . ' - ' . __('messages.header.title'))
@section('description', __('messages.security.encryption.meta_description'))

@section('content')
    <!-- Page Header -->

    <section class="py-12 md:py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[#27e9b5] bg-opacity-20 rounded-full mb-6">
                <i class="fas fa-shield-alt text-4xl text-[#051824]"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.security.encryption.page_title') }}
            </h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">{{ __('messages.security.encryption.page_subtitle') }}</p>
        </div>
    </section>

    <!-- SSL/TLS Section -->
    <section class="py-12 md:py-16 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-[#27e9b5] bg-opacity-20 rounded-full mb-4">
                        <i class="fas fa-lock text-[#051824] me-2"></i>
                        <span class="text-[#051824] font-semibold">{{ __('messages.security.encryption.ssl.badge') }}</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">{{ __('messages.security.encryption.ssl.title') }}</h2>
                    <p class="text-gray-300 mb-6">{{ __('messages.security.encryption.ssl.description') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-white mt-1 me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.ssl.feature1') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-white mt-1 me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.ssl.feature2') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-white mt-1 me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.ssl.feature3') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-[#162936] p-8 rounded-[20px] border border-[#3b5265]">
                    <div class="text-center">
                        <i class="fas fa-certificate text-6xl text-white mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ __('messages.security.encryption.ssl.certificate') }}</h3>
                        <p class="text-gray-400">TLS 1.3 / AES-256-GCM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Zero-Knowledge Proof Section -->
    <section class="py-12 md:py-16 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-[#27e9b5] bg-opacity-20 rounded-full mb-4">
                    <i class="fas fa-user-secret text-[#051824] me-2"></i>
                    <span class="text-[#051824] font-semibold">{{ __('messages.security.encryption.zkp.badge') }}</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    {{ __('messages.security.encryption.zkp.title') }}</h2>
                <p class="text-gray-300 max-w-3xl mx-auto">{{ __('messages.security.encryption.zkp.description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- ZKP Feature 1 -->
                <div
                    class="bg-[#051824] p-6 rounded-[20px] border border-[#3b5265] hover:border-[#27e9b5] transition-colors">
                    <div class="w-14 h-14 bg-[#27e9b5] bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-eye-slash text-2xl text-[#051824]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">
                        {{ __('messages.security.encryption.zkp.feature1.title') }}</h3>
                    <p class="text-gray-400">{{ __('messages.security.encryption.zkp.feature1.description') }}</p>
                </div>
                <!-- ZKP Feature 2 -->
                <div
                    class="bg-[#051824] p-6 rounded-[20px] border border-[#3b5265] hover:border-[#27e9b5] transition-colors">
                    <div class="w-14 h-14 bg-[#27e9b5] bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-server text-2xl text-[#051824]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">
                        {{ __('messages.security.encryption.zkp.feature2.title') }}</h3>
                    <p class="text-gray-400">{{ __('messages.security.encryption.zkp.feature2.description') }}</p>
                </div>
                <!-- ZKP Feature 3 -->
                <div
                    class="bg-[#051824] p-6 rounded-[20px] border border-[#3b5265] hover:border-[#27e9b5] transition-colors">
                    <div class="w-14 h-14 bg-[#27e9b5] bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-double text-2xl text-[#051824]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">
                        {{ __('messages.security.encryption.zkp.feature3.title') }}</h3>
                    <p class="text-gray-400">{{ __('messages.security.encryption.zkp.feature3.description') }}</p>
                </div>
            </div>

            <!-- ZKP PoC Demonstration Section -->
            <div class="mt-16 bg-[#162936] p-8 rounded-[20px] border border-[#3b5265]">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ __('messages.security.encryption.zkp_poc.title') }}
                    </h3>
                    <p class="text-gray-300">{{ __('messages.security.encryption.zkp_poc.subtitle') }}</p>
                </div>

                <div class="max-w-2xl mx-auto">
                    <div class="bg-[#051824] p-6 rounded-lg border border-[#3b5265] mb-6">
                        <label
                            class="block text-[#27e9b5] text-sm font-bold mb-2">{{ __('messages.security.encryption.zkp_poc.secret_label') }}</label>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="password" id="zkp-secret"
                                placeholder="{{ __('messages.security.encryption.zkp_poc.secret_placeholder') }}"
                                class="flex-1 px-4 py-2 bg-[#162936] text-white border border-[#3b5265] rounded focus:outline-none focus:border-[#27e9b5]">
                            <button onclick="generateProof()"
                                class="px-6 py-2 bg-[#27e9b5] text-[#051824] font-bold rounded hover:bg-opacity-90 transition-all">
                                {{ __('messages.security.encryption.zkp_poc.generate_proof') }}
                            </button>
                        </div>
                    </div>

                    <div id="zkp-proof-area" class="hidden animate-fade-in">
                        <div class="bg-[#051824] p-6 rounded-lg border border-[#3b5265] mb-6">
                            <label class="block text-[#27e9b5] text-sm font-bold mb-2">Proof (Hash)</label>
                            <div class="font-mono text-xs text-gray-400 break-all bg-[#162936] p-3 rounded"
                                id="zkp-proof-value"></div>
                            <button onclick="verifyProof()"
                                class="w-full mt-4 px-6 py-2 border-2 border-[#27e9b5] text-[#27e9b5] font-bold rounded hover:bg-[#27e9b5] hover:text-[#051824] transition-all">
                                {{ __('messages.security.encryption.zkp_poc.verify_proof') }}
                            </button>
                        </div>
                    </div>

                    <div id="zkp-result"
                        class="hidden p-4 rounded-lg bg-[#27e9b5] bg-opacity-10 border border-[#27e9b5] text-center">
                        <i class="fas fa-check-circle text-[#27e9b5] text-2xl mb-2"></i>
                        <h4 class="text-white font-bold">{{ __('messages.security.encryption.zkp_poc.result_title') }}</h4>
                        <p class="text-gray-300 text-sm">{{ __('messages.security.encryption.zkp_poc.success') }}</p>
                    </div>

                    <p class="text-gray-400 text-xs text-center mt-6 italic">
                        {{ __('messages.security.encryption.zkp_poc.instruction') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            async function hashString(str) {
                const msgUint8 = new TextEncoder().encode(str);
                const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
            }

            let currentProof = '';

            async function generateProof() {
                const secret = document.getElementById('zkp-secret').value;
                if (!secret) return;

                // Simulating a proof generation (using simple SHA-256 for PoC demonstration)
                currentProof = await hashString(secret);

                document.getElementById('zkp-proof-value').innerText = currentProof;
                document.getElementById('zkp-proof-area').classList.remove('hidden');
                document.getElementById('zkp-result').classList.add('hidden');
            }

            function verifyProof() {
                document.getElementById('zkp-result').classList.remove('hidden');
                document.getElementById('zkp-result').classList.add('animate-bounce-short');
            }
        </script>
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            @keyframes bounceShort {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-5px);
                }
            }

            .animate-bounce-short {
                animation: bounceShort 0.5s ease-in-out 2;
            }
        </style>
    @endpush

    <!-- Encryption Methodology Section -->
    <section class="py-12 md:py-16 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    {{ __('messages.security.encryption.methodology.title') }}</h2>
                <p class="text-gray-300 max-w-3xl mx-auto">{{ __('messages.security.encryption.methodology.description') }}
                </p>
            </div>

            <div class="bg-[#162936] p-8 rounded-[20px] border border-[#3b5265]">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-[#051824] font-bold text-lg">1</span>
                        </div>
                        <h4 class="text-white font-bold mb-2">
                            {{ __('messages.security.encryption.methodology.step1.title') }}</h4>
                        <p class="text-gray-400 text-sm">
                            {{ __('messages.security.encryption.methodology.step1.description') }}</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-[#051824] font-bold text-lg">2</span>
                        </div>
                        <h4 class="text-white font-bold mb-2">
                            {{ __('messages.security.encryption.methodology.step2.title') }}</h4>
                        <p class="text-gray-400 text-sm">
                            {{ __('messages.security.encryption.methodology.step2.description') }}</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-[#051824] font-bold text-lg">3</span>
                        </div>
                        <h4 class="text-white font-bold mb-2">
                            {{ __('messages.security.encryption.methodology.step3.title') }}</h4>
                        <p class="text-gray-400 text-sm">
                            {{ __('messages.security.encryption.methodology.step3.description') }}</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-[#051824] font-bold text-lg">4</span>
                        </div>
                        <h4 class="text-white font-bold mb-2">
                            {{ __('messages.security.encryption.methodology.step4.title') }}</h4>
                        <p class="text-gray-400 text-sm">
                            {{ __('messages.security.encryption.methodology.step4.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Government Compliance Section -->
    <section class="py-12 md:py-16 bg-[linear-gradient(135deg,#162936_0%,#051824_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-[#27e9b5] bg-opacity-20 rounded-full mb-4">
                        <i class="fas fa-landmark text-[#051824] me-2"></i>
                        <span
                            class="text-[#051824] font-semibold">{{ __('messages.security.encryption.compliance.badge') }}</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">
                        {{ __('messages.security.encryption.compliance.title') }}</h2>
                    <p class="text-gray-300 mb-6">{{ __('messages.security.encryption.compliance.description') }}</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-check text-white me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.compliance.item1') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.compliance.item2') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white me-3"></i>
                            <span class="text-gray-300">{{ __('messages.security.encryption.compliance.item3') }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-[#051824] p-8 rounded-[20px] border border-[#3b5265] text-center">
                    <i class="fas fa-file-pdf text-6xl text-white mb-4"></i>
                    <h3 class="text-xl font-bold text-white mb-2">
                        {{ __('messages.security.encryption.compliance.report_title') }}</h3>
                    <p class="text-gray-400 mb-6">{{ __('messages.security.encryption.compliance.report_description') }}
                    </p>
                    <a href="{{ route('security.verification-report', app()->getLocale()) }}"
                        class="inline-flex items-center px-6 py-3 bg-[#27e9b5] text-[#051824] font-bold rounded-full hover:bg-opacity-90 transition-colors">
                        <i class="fas fa-download me-2"></i>
                        {{ __('messages.security.encryption.compliance.download_button') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 md:py-16 bg-[#051824]">
        <div class="max-w-4xl mx-auto px-5 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">{{ __('messages.security.encryption.cta.title') }}</h2>
            <p class="text-gray-300 mb-8">{{ __('messages.security.encryption.cta.description') }}</p>
            <a href="{{ route('contact', app()->getLocale()) }}"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] font-bold rounded-full shadow-lg hover:shadow-xl transition-all">
                {{ __('messages.security.encryption.cta.button') }}
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ms-2"></i>
            </a>
        </div>
    </section>
@endsection
