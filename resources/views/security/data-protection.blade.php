@extends('layouts.app')

@section('title', __('messages.security.data_protection.title') . ' - ' . __('messages.header.title'))
@section('description', __('messages.security.data_protection.meta_description'))

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[#27e9b5] bg-opacity-20 rounded-full mb-6">
                <i class="fas fa-database text-4xl text-[#051824]"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('messages.security.data_protection.page_title') }}</h1>
            <p class="text-lg text-gray-300 max-w-3xl mx-auto">{{ __('messages.security.data_protection.page_subtitle') }}
            </p>
        </div>
    </section>

    <!-- Data Protection Content -->
    <section class="py-16 bg-[#051824]">
        <div class="max-w-4xl mx-auto px-5">
            <!-- UAE Compliance -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-landmark text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.data_protection.uae.title') }}</h2>
                </div>
                <p class="text-gray-300">{{ __('messages.security.data_protection.uae.description') }}</p>
            </div>

            <!-- Technical Measures -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('messages.security.data_protection.measures.title') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                        <i class="fas fa-lock text-[#27e9b5] text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ __('messages.security.data_protection.measures.encryption.title') }}</h3>
                        <p class="text-gray-400">
                            {{ __('messages.security.data_protection.measures.encryption.description') }}</p>
                    </div>
                    <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                        <i class="fas fa-server text-[#27e9b5] text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ __('messages.security.data_protection.measures.storage.title') }}</h3>
                        <p class="text-gray-400">{{ __('messages.security.data_protection.measures.storage.description') }}
                        </p>
                    </div>
                    <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                        <i class="fas fa-user-lock text-[#27e9b5] text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ __('messages.security.data_protection.measures.access.title') }}</h3>
                        <p class="text-gray-400">{{ __('messages.security.data_protection.measures.access.description') }}
                        </p>
                    </div>
                    <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                        <i class="fas fa-clipboard-check text-[#27e9b5] text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">
                            {{ __('messages.security.data_protection.measures.audit.title') }}</h3>
                        <p class="text-gray-400">{{ __('messages.security.data_protection.measures.audit.description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Retention -->
            <div class="mb-12 bg-[#162936] p-6 rounded-[20px] border border-[#3b5265]">
                <div class="flex items-center mb-4">
                    <i class="fas fa-clock text-[#27e9b5] text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.security.data_protection.retention.title') }}
                    </h2>
                </div>
                <p class="text-gray-300">{{ __('messages.security.data_protection.retention.description') }}</p>
            </div>

            <!-- Contact -->
            <div class="bg-[#162936] p-6 rounded-[20px] border border-[#3b5265] text-center">
                <h2 class="text-2xl font-bold text-white mb-4">{{ __('messages.security.data_protection.contact.title') }}
                </h2>
                <p class="text-gray-300 mb-6">{{ __('messages.security.data_protection.contact.description') }}</p>
                <a href="{{ route('contact', app()->getLocale()) }}"
                    class="inline-flex items-center px-6 py-3 bg-[#27e9b5] text-[#051824] font-bold rounded-full hover:bg-opacity-90 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    {{ __('messages.security.data_protection.contact.button') }}
                </a>
            </div>
        </div>
    </section>
@endsection
