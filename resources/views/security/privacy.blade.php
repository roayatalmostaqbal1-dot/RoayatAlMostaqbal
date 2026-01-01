@extends('layouts.app')

@section('title', __('messages.security.privacy.title') . ' - ' . __('messages.header.title'))
@section('description', __('messages.security.privacy.meta_description'))

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[#27e9b5] bg-opacity-20 rounded-full mb-6">
                <i class="fas fa-user-shield text-4xl text-[#f0f0f0]"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.security.privacy.page_title') }}</h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">{{ __('messages.security.privacy.page_subtitle') }}</p>
            <p class="text-sm text-gray-400 mt-4">{{ __('messages.security.privacy.last_updated') }}: {{ date('Y-m-d') }}</p>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-16 bg-[#051824]">
        <div class="max-w-4xl mx-auto px-5">
            <!-- Introduction -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-white mb-4">{{ __('messages.security.privacy.intro.title') }}</h2>
                <p class="text-gray-300 leading-relaxed">{{ __('messages.security.privacy.intro.description') }}</p>
            </div>

            <!-- Data Collection -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-database text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.privacy.collection.title') }}</h2>
                </div>
                <p class="text-gray-300 mb-4">{{ __('messages.security.privacy.collection.description') }}</p>
                <ul class="space-y-2">
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.collection.item1') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.collection.item2') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.collection.item3') }}
                    </li>
                </ul>
            </div>

            <!-- Data Usage -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-cogs text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.privacy.usage.title') }}</h2>
                </div>
                <p class="text-gray-300 mb-4">{{ __('messages.security.privacy.usage.description') }}</p>
                <ul class="space-y-2">
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.usage.item1') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.usage.item2') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.usage.item3') }}
                    </li>
                </ul>
            </div>

            <!-- Data Protection -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-shield-alt text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.privacy.protection.title') }}</h2>
                </div>
                <p class="text-gray-300">{{ __('messages.security.privacy.protection.description') }}</p>
            </div>

            <!-- Your Rights -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-user-check text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.privacy.rights.title') }}</h2>
                </div>
                <p class="text-gray-300 mb-4">{{ __('messages.security.privacy.rights.description') }}</p>
                <ul class="space-y-2">
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.rights.item1') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.rights.item2') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.rights.item3') }}
                    </li>
                    <li class="flex items-start text-gray-300">
                        <i class="fas fa-check text-[#27e9b5] mt-1 mr-2"></i>
                        {{ __('messages.security.privacy.rights.item4') }}
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265] text-center">
                <h2 class="text-2xl font-bold text-white mb-4">{{ __('messages.security.privacy.contact.title') }}</h2>
                <p class="text-gray-300 mb-6">{{ __('messages.security.privacy.contact.description') }}</p>
                <a href="{{ route('contact', app()->getLocale()) }}"
                   class="inline-flex items-center px-6 py-3 bg-[#27e9b5] text-[#051824] font-bold rounded-full hover:bg-opacity-90 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    {{ __('messages.security.privacy.contact.button') }}
                </a>
            </div>
        </div>
    </section>
@endsection

