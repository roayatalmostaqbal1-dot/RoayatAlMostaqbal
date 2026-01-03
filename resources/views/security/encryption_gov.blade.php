@extends('layouts.app')

@section('title', __('messages.security.encryption.title') . ' - ' . __('messages.header.title'))
@section('description', __('messages.security.encryption.meta_description'))

@section('content')
    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
        <!-- القسم 1: المقدمة (Hero) -->
        <section class="py-16 md:py-24 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 text-center" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.hero.title') }}
                </h1>
                <div class="prose prose-lg max-w-none text-gray-700 text-lg leading-relaxed" style="font-family: 'Almarai', sans-serif;">
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.hero.paragraph1') }}
                    </p>
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.hero.paragraph2') }}
                    </p>
                    <p>
                        {{ __('messages.security.encryption.gov.hero.paragraph3') }}
                    </p>
                </div>
            </div>
        </section>

        <!-- القسم 2: الابتكار الأساسي -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-5xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8" style="color: #00732F; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.innovation.title') }}
                </h2>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed" style="font-family: 'Almarai', sans-serif;">
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph1') }}
                    </p>
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph2') }}
                    </p>
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph3') }}
                    </p>
                    <p class="mb-4 font-semibold" style="color: #00732F;">
                        {!! __('messages.security.encryption.gov.innovation.patent') !!}
                    </p>
                    <p>
                        {{ __('messages.security.encryption.gov.innovation.paragraph4') }}
                    </p>
                </div>
            </div>
        </section>

        <!-- القسم 3: مكونات النموذج (جدول) -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.components.title') }}
                </h3>
                <!-- جدول للشاشات الكبيرة -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 bg-white" style="font-family: 'Almarai', sans-serif;">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold text-gray-900" style="color: #00732F;">
                                    {{ __('messages.security.encryption.gov.components.table.component') }}
                                </th>
                                <th class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold text-gray-900" style="color: #00732F;">
                                    {{ __('messages.security.encryption.gov.components.table.function') }}
                                </th>
                                <th class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold text-gray-900" style="color: #00732F;">
                                    {{ __('messages.security.encryption.gov.components.table.benefit') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.name') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.function') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.benefit') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.name') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.function') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.benefit') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.name') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.function') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.benefit') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.name') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.function') }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.benefit') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- بطاقات للشاشات الصغيرة -->
                <div class="md:hidden space-y-4" style="font-family: 'Almarai', sans-serif;">
                    <div class="bg-white border border-gray-300 p-4">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">{{ __('messages.security.encryption.gov.components.cse.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2"><strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong> {{ __('messages.security.encryption.gov.components.cse.function') }}</p>
                        <p class="text-sm text-gray-600"><strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong> {{ __('messages.security.encryption.gov.components.cse.benefit') }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-300 p-4">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">{{ __('messages.security.encryption.gov.components.hybrid.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2"><strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong> {{ __('messages.security.encryption.gov.components.hybrid.function') }}</p>
                        <p class="text-sm text-gray-600"><strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong> {{ __('messages.security.encryption.gov.components.hybrid.benefit') }}</p>
                    </div>
                    <div class="bg-white border border-gray-300 p-4">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">{{ __('messages.security.encryption.gov.components.zkp.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2"><strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong> {{ __('messages.security.encryption.gov.components.zkp.function') }}</p>
                        <p class="text-sm text-gray-600"><strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong> {{ __('messages.security.encryption.gov.components.zkp.benefit') }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-300 p-4">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">{{ __('messages.security.encryption.gov.components.sse.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2"><strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong> {{ __('messages.security.encryption.gov.components.sse.function') }}</p>
                        <p class="text-sm text-gray-600"><strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong> {{ __('messages.security.encryption.gov.components.sse.benefit') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 4: خدمة المنظومة الوطنية -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.national.title') }}
                </h3>
                <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                    <div class="bg-white p-6 border-r-4" style="border-color: #00732F; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.national.citizens.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.citizens.text') }}
                        </p>
                    </div>
                    <div class="bg-white p-6 border-r-4" style="border-color: #00732F; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.national.government.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.government.text') }}
                        </p>
                    </div>
                    <div class="bg-white p-6 border-r-4" style="border-color: #00732F; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.national.cybersecurity.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.cybersecurity.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 5: التقنيات الداعمة -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.technologies.title') }}
                </h3>
                <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.technologies.tls.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.tls.text') }}
                        </p>
                    </div>
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.technologies.cse.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.cse.text') }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                            {{ __('messages.security.encryption.gov.technologies.zkp.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.zkp.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 6: الامتثال والمصداقية -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.compliance.title') }}
                </h3>
                <div class="space-y-4" style="font-family: 'Almarai', sans-serif;">
                    <div class="flex items-start">
                        <span class="text-gray-700 {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.data_law') }}</strong> {{ __('messages.security.encryption.gov.compliance.data_law.text') }}
                        </p>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-700 {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.patent') }}</strong> {{ __('messages.security.encryption.gov.compliance.patent.text') }}
                        </p>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-700 {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.hub71') }}</strong> {{ __('messages.security.encryption.gov.compliance.hub71.text') }}
                        </p>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-700 {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.khalifa') }}</strong> {{ __('messages.security.encryption.gov.compliance.khalifa.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 7: دعوة العمل (CTA) -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5 text-center">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.cta.title') }}
                </h3>
                <div class="flex justify-center">
                    <a href="{{ route('contact', app()->getLocale()) }}"
                       class="inline-block px-8 py-4 bg-white border-2 font-bold rounded text-lg transition-colors hover:bg-gray-50"
                       style="border-color: #00732F; color: #00732F; font-family: 'Almarai', sans-serif;">
                        {{ __('messages.security.encryption.gov.cta.button') }}
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
