@extends('layouts.app')

@section('title', 'النموذج الإماراتي للتشفير الهجين المتقدم - ' . __('messages.header.title'))
@section('description', 'النموذج الإماراتي الأول للتشفير الهجين السيادي - يجمع بين خصوصية المواطن والتحكم الوطني. مصمم
    خصيصاً للبيئة الرقمية الإماراتية ومتوافق مع القانون الاتحادي 45/2021.')
@section('keywords', 'تشفير هجين إماراتي, سيادة البيانات الرقمية, نموذج تشفير سيادي, UAE Pass أمن, براءة اختراع أمن
    إماراتي, حلول أمنية للحكومة الإماراتية, تشفير البيانات الإمارات, حماية الهوية الرقمية, أمن المعلومات الإمارات')

@section('content')
    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
        <!-- القسم 1: المقدمة (Hero) -->
        <section class="py-16 md:py-24 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-5xl mx-auto px-5">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center leading-tight"
                    style="font-family: 'Almarai', sans-serif; color: #0055A4;">
                    {{ __('messages.security.encryption.gov.hero.new_title') }}
                </h1>
                <h2 class="text-2xl md:text-3xl font-semibold mb-8 text-center"
                    style="font-family: 'Almarai', sans-serif; color: #1A365D;">
                    {{ __('messages.security.encryption.gov.hero.new_subtitle') }}
                </h2>
                <div class="prose prose-lg max-w-none text-gray-700 text-lg leading-relaxed"
                    style="font-family: 'Almarai', sans-serif;">
                    <p class="mb-4 text-center">
                        {{ __('messages.security.encryption.gov.hero.new_description') }}
                    </p>
                </div>
            </div>
        </section>

        <!-- القسم 2: المشكلة التي نحلها -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center"
                    style="color: #0055A4; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.problem.title') }}
                </h2>
                <div class="bg-red-50 border-r-4 border-[#FF0000] p-6 mb-6 rounded-lg"
                    style="font-family: 'Almarai', sans-serif; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                    <p class="text-lg font-semibold text-gray-900 mb-4">
                        {{ __('messages.security.encryption.gov.problem.traditional_issue') }}
                    </p>
                    <ul class="space-y-3 text-gray-700 leading-relaxed">
                        <li class="flex items-start">
                            <span
                                class="text-[#FF0000] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                            <span><strong>{{ __('messages.security.encryption.gov.problem.cse_only') }}</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="text-[#FF0000] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                            <span><strong>{{ __('messages.security.encryption.gov.problem.sse_only') }}</strong></span>
                        </li>
                    </ul>
                </div>
                <div class="bg-[#0055A4] text-white p-6 rounded-lg text-center">
                    <p class="text-xl font-bold mb-2" style="font-family: 'Almarai', sans-serif;">
                        {{ __('messages.security.encryption.gov.problem.solution') }}
                    </p>
                    <p class="text-lg" style="font-family: 'Almarai', sans-serif;">
                        {{ __('messages.security.encryption.gov.problem.solution_subtitle') }}
                    </p>
                </div>
            </div>
        </section>

        <!-- القسم 3: كيف يعمل النموذج الهجين -->
        <section class="py-16 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-5xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center"
                    style="color: #0055A4; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.how_it_works.title') }}
                </h2>
                <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                    <!-- المستخدم -->
                    <div class="bg-white border-2 border-[#0055A4] rounded-lg p-6 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-3 h-3 bg-[#0055A4] rounded-full {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">
                            </div>
                            <h3 class="text-2xl font-bold" style="color: #0055A4;">
                                {{ __('messages.security.encryption.gov.how_it_works.user.title') }}</h3>
                        </div>
                        <ul class="space-y-2 text-gray-700 {{ app()->getLocale() == 'ar' ? 'pr-6' : 'pl-6' }}">
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.user.control') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.user.encrypt') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- السهم -->
                    <div class="flex justify-center">
                        <div class="w-1 h-12 bg-[#0055A4]"></div>
                    </div>

                    <!-- النظام السيادي -->
                    <div class="bg-gradient-to-r from-[#0055A4] to-[#1A365D] text-white rounded-lg p-6 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-3 h-3 bg-white rounded-full {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">
                            </div>
                            <h3 class="text-2xl font-bold">
                                {{ __('messages.security.encryption.gov.how_it_works.sovereign.title') }}</h3>
                        </div>
                        <ul class="space-y-2 {{ app()->getLocale() == 'ar' ? 'pr-6' : 'pl-6' }}">
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.sovereign.auth') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.sovereign.recovery') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.sovereign.compliance') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- السهم -->
                    <div class="flex justify-center">
                        <div class="w-1 h-12 bg-[#0055A4]"></div>
                    </div>

                    <!-- التطبيقات الحكومية -->
                    <div class="bg-white border-2 border-[#00B4B4] rounded-lg p-6 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-3 h-3 bg-[#00B4B4] rounded-full {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">
                            </div>
                            <h3 class="text-2xl font-bold" style="color: #0055A4;">
                                {{ __('messages.security.encryption.gov.how_it_works.apps.title') }}</h3>
                        </div>
                        <ul class="space-y-2 text-gray-700 {{ app()->getLocale() == 'ar' ? 'pr-6' : 'pl-6' }}">
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.apps.uae_pass') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.apps.smart_services') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="text-[#00B4B4] {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }} mt-1">•</span>
                                <span>{{ __('messages.security.encryption.gov.how_it_works.apps.health') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 4: الابتكار الأساسي -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center"
                    style="color: #0055A4; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.innovation.title') }}
                </h2>
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed"
                    style="font-family: 'Almarai', sans-serif;">
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph1') }}
                    </p>
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph2') }}
                    </p>
                    <p class="mb-4">
                        {{ __('messages.security.encryption.gov.innovation.paragraph3') }}
                    </p>
                    <p class="mb-4 font-semibold" style="color: #0055A4;">
                        {!! __('messages.security.encryption.gov.innovation.patent') !!}
                    </p>
                    <p>
                        {{ __('messages.security.encryption.gov.innovation.paragraph4') }}
                    </p>
                </div>
            </div>
        </section>

        <!-- القسم 5: مقارنة مع الحلول التقليدية والدولية -->
        <section class="py-16 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-6xl mx-auto px-5">
                <div class="comparison-section {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <h2 class="comparison-title">
                        @if (app()->getLocale() == 'ar')
                            لماذا نموذجنا الهجين هو القرار الأمثل للمؤسسات الإماراتية؟
                        @else
                            Why is Our Hybrid Model the Optimal Choice for UAE Organizations?
                        @endif
                    </h2>
                    <p class="section-subtitle">
                        @if (app()->getLocale() == 'ar')
                            مقارنة استراتيجية توضّح الفارق بين الحلول التقنية البحتة والحلول السيادية المصممة خصيصاً للبيئة
                            الإماراتية.
                        @else
                            A strategic comparison showing the difference between pure technical solutions and sovereign
                            solutions designed specifically for the UAE environment.
                        @endif
                    </p>

                    <div class="table-wrapper">
                        <table class="comparison-table">
                            <thead>
                                <tr>
                                    <th>
                                        @if (app()->getLocale() == 'ar')
                                            المعيار
                                        @else
                                            Criteria
                                        @endif
                                    </th>
                                    <th>
                                        @if (app()->getLocale() == 'ar')
                                            التشفير التقليدي<br><span class="subtitle">(CSE البحت)</span>
                                        @else
                                            Traditional Encryption<br><span class="subtitle">(Pure CSE)</span>
                                        @endif
                                    </th>
                                    <th>
                                        @if (app()->getLocale() == 'ar')
                                            النماذج الدولية التقليدية<br><span class="subtitle">(مثل TransferChain)</span>
                                        @else
                                            Traditional International Models<br><span class="subtitle">(like
                                                TransferChain)</span>
                                        @endif
                                    </th>
                                    <th class="sovereign">
                                        @if (app()->getLocale() == 'ar')
                                            نموذجنا الهجين السيادي
                                        @else
                                            Our Sovereign Hybrid Model
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="@if (app()->getLocale() == 'ar') المعيار@else Criteria @endif">
                                        @if (app()->getLocale() == 'ar')
                                            الخصوصية والأمان
                                        @else
                                            Privacy and Security
                                        @endif
                                    </td>
                                    <td class="danger"
                                        data-label="@if (app()->getLocale() == 'ar') التشفير التقليدي@else Traditional Encryption @endif">
                                        @if (app()->getLocale() == 'ar')
                                            خصوصية مطلقة<br>❌ بدون استرداد
                                        @else
                                            Absolute Privacy<br>❌ No Recovery
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') النماذج الدولية@else International Models @endif">
                                        @if (app()->getLocale() == 'ar')
                                            توازن محدود<br>⚠️ مراقبة مركزية
                                        @else
                                            Limited Balance<br>⚠️ Centralized Monitoring
                                        @endif
                                    </td>
                                    <td class="sovereign success"
                                        data-label="@if (app()->getLocale() == 'ar') نموذجنا@else Our Model @endif">
                                        @if (app()->getLocale() == 'ar')
                                            خصوصية عالية<br>✅ تحكم سيادي كامل
                                        @else
                                            High Privacy<br>✅ Full Sovereign Control
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td data-label="@if (app()->getLocale() == 'ar') المعيار@else Criteria @endif">
                                        @if (app()->getLocale() == 'ar')
                                            استرداد البيانات / المفاتيح
                                        @else
                                            Data / Key Recovery
                                        @endif
                                    </td>
                                    <td class="danger"
                                        data-label="@if (app()->getLocale() == 'ar') التشفير التقليدي@else Traditional Encryption @endif">
                                        @if (app()->getLocale() == 'ar')
                                            ⛔ غير ممكن<br>فقدان دائم
                                        @else
                                            ⛔ Impossible<br>Permanent Loss
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') النماذج الدولية@else International Models @endif">
                                        @if (app()->getLocale() == 'ar')
                                            ✅ ممكن<br>لكن عبر جهة أجنبية
                                        @else
                                            ✅ Possible<br>But through foreign entity
                                        @endif
                                    </td>
                                    <td class="sovereign success"
                                        data-label="@if (app()->getLocale() == 'ar') نموذجنا@else Our Model @endif">
                                        @if (app()->getLocale() == 'ar')
                                            ✅ ممكن وآمن<br>تحت سيطرة وطنية
                                        @else
                                            ✅ Possible & Secure<br>Under national control
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td data-label="@if (app()->getLocale() == 'ar') المعيار@else Criteria @endif">
                                        @if (app()->getLocale() == 'ar')
                                            التكامل مع الأنظمة الحكومية
                                        @else
                                            Government Systems Integration
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') التشفير التقليدي@else Traditional Encryption @endif">
                                        @if (app()->getLocale() == 'ar')
                                            ⚠️ محدود<br>غير مخصص للحكومة
                                        @else
                                            ⚠️ Limited<br>Not designed for government
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') النماذج الدولية@else International Models @endif">
                                        @if (app()->getLocale() == 'ar')
                                            🌍 مصمم لبيئات أجنبية
                                        @else
                                            🌍 Designed for foreign environments
                                        @endif
                                    </td>
                                    <td class="sovereign success"
                                        data-label="@if (app()->getLocale() == 'ar') نموذجنا@else Our Model @endif">
                                        @if (app()->getLocale() == 'ar')
                                            🇦🇪 مصمم للتكامل مع<br>UAE Pass والمنصات الوطنية
                                        @else
                                            🇦🇪 Designed for integration with<br>UAE Pass & national platforms
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td data-label="@if (app()->getLocale() == 'ar') المعيار@else Criteria @endif">
                                        @if (app()->getLocale() == 'ar')
                                            الامتثال للوائح المحلية
                                        @else
                                            Local Regulations Compliance
                                        @endif
                                    </td>
                                    <td class="danger"
                                        data-label="@if (app()->getLocale() == 'ar') التشفير التقليدي@else Traditional Encryption @endif">
                                        @if (app()->getLocale() == 'ar')
                                            غير مضمون
                                        @else
                                            Not Guaranteed
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') النماذج الدولية@else International Models @endif">
                                        @if (app()->getLocale() == 'ar')
                                            غير مكتمل<br>أو غير مخصص للمنطقة
                                        @else
                                            Incomplete<br>or not region-specific
                                        @endif
                                    </td>
                                    <td class="sovereign success"
                                        data-label="@if (app()->getLocale() == 'ar') نموذجنا@else Our Model @endif">
                                        @if (app()->getLocale() == 'ar')
                                            متوافق 100%<br>مع لوائح الإمارات
                                        @else
                                            100% Compliant<br>with UAE regulations
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td data-label="@if (app()->getLocale() == 'ar') المعيار@else Criteria @endif">
                                        @if (app()->getLocale() == 'ar')
                                            الخلاصة الاستراتيجية
                                        @else
                                            Strategic Summary
                                        @endif
                                    </td>
                                    <td class="danger"
                                        data-label="@if (app()->getLocale() == 'ar') التشفير التقليدي@else Traditional Encryption @endif">
                                        @if (app()->getLocale() == 'ar')
                                            حل تقني بحت<br>⚠️ مخاطرة تشغيلية
                                        @else
                                            Pure Technical Solution<br>⚠️ Operational Risk
                                        @endif
                                    </td>
                                    <td class="warning"
                                        data-label="@if (app()->getLocale() == 'ar') النماذج الدولية@else International Models @endif">
                                        @if (app()->getLocale() == 'ar')
                                            حل عالمي<br>❌ لا يراعي السيادة
                                        @else
                                            Global Solution<br>❌ Doesn't respect sovereignty
                                        @endif
                                    </td>
                                    <td class="sovereign success"
                                        data-label="@if (app()->getLocale() == 'ar') نموذجنا@else Our Model @endif">
                                        @if (app()->getLocale() == 'ar')
                                            قرار سيادي استراتيجي<br>⚖️ توازن الخصوصية والسيادة
                                        @else
                                            Strategic Sovereign Decision<br>⚖️ Privacy & Sovereignty Balance
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .comparison-section {
                margin: 40px 0;
                padding: 30px;
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            }

            .comparison-section.rtl {
                direction: rtl;
            }

            .comparison-section.ltr {
                direction: ltr;
            }

            .comparison-title {
                color: #0A3D62;
                font-size: 26px;
                margin-bottom: 10px;
                font-weight: bold;
            }

            .section-subtitle {
                color: #555;
                margin-bottom: 25px;
                font-size: 16px;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            .comparison-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 15px;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 15px;
                border: 1px solid #e5e7eb;
                vertical-align: top;
            }

            .comparison-table th {
                background: #f1f5f9;
                color: #0A3D62;
                font-weight: bold;
                text-align: center;
            }

            .comparison-table th .subtitle {
                font-size: 13px;
                font-weight: normal;
                opacity: 0.8;
            }

            .comparison-table td:first-child {
                font-weight: 600;
                background: #f8fafc;
                width: 22%;
            }

            .comparison-table td {
                text-align: center;
            }

            .comparison-table .sovereign {
                background: #ecfdf5;
                border: 2px solid #10b981;
                font-weight: 600;
            }

            .comparison-table .danger {
                color: #b91c1c;
                background: #fef2f2;
            }

            .comparison-table .warning {
                color: #92400e;
                background: #fffbeb;
            }

            .comparison-table .success {
                color: #065f46;
            }

            @media (max-width: 768px) {

                .comparison-table,
                .comparison-table thead,
                .comparison-table tbody,
                .comparison-table th,
                .comparison-table td,
                .comparison-table tr {
                    display: block;
                }

                .comparison-table thead {
                    display: none;
                }

                .comparison-table tr {
                    margin-bottom: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    overflow: hidden;
                }

                .comparison-table td {
                    text-align: right;
                    padding: 12px;
                    position: relative;
                    border-bottom: 1px solid #e5e7eb;
                }

                .comparison-table.ltr td {
                    text-align: left;
                }

                .comparison-table td::before {
                    content: attr(data-label);
                    font-weight: bold;
                    display: block;
                    margin-bottom: 6px;
                    color: #0A3D62;
                }

                .comparison-table td:first-child {
                    background: #f1f5f9;
                    font-size: 16px;
                }
            }
        </style>

        <!-- القسم 7: مكونات النموذج (جدول) -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8"
                    style="font-family: 'Almarai', sans-serif; color: #0055A4;">
                    {{ __('messages.security.encryption.gov.components.title') }}
                </h3>
                <!-- جدول للشاشات الكبيرة -->
                <div class="hidden md:block overflow-x-auto">
                    <table
                        class="w-full border-collapse border border-gray-300 bg-white shadow-lg rounded-lg overflow-hidden"
                        style="font-family: 'Almarai', sans-serif;">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#0055A4] to-[#1A365D] text-white">
                                <th
                                    class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold">
                                    {{ __('messages.security.encryption.gov.components.table.component') }}
                                </th>
                                <th
                                    class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold">
                                    {{ __('messages.security.encryption.gov.components.table.function') }}
                                </th>
                                <th
                                    class="border border-gray-300 px-6 py-4 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} font-bold">
                                    {{ __('messages.security.encryption.gov.components.table.benefit') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.name') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.function') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.cse.benefit') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.name') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.function') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.hybrid.benefit') }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.name') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.function') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.zkp.benefit') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.name') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.function') }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    {{ __('messages.security.encryption.gov.components.sse.benefit') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- بطاقات للشاشات الصغيرة -->
                <div class="md:hidden space-y-4" style="font-family: 'Almarai', sans-serif;">
                    <div class="bg-white border-2 border-[#0055A4] rounded-lg p-4 shadow">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.components.cse.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong>
                            {{ __('messages.security.encryption.gov.components.cse.function') }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong>
                            {{ __('messages.security.encryption.gov.components.cse.benefit') }}</p>
                    </div>
                    <div class="bg-gray-50 border-2 border-[#00B4B4] rounded-lg p-4 shadow">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.components.hybrid.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong>
                            {{ __('messages.security.encryption.gov.components.hybrid.function') }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong>
                            {{ __('messages.security.encryption.gov.components.hybrid.benefit') }}</p>
                    </div>
                    <div class="bg-white border-2 border-[#0055A4] rounded-lg p-4 shadow">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.components.zkp.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong>
                            {{ __('messages.security.encryption.gov.components.zkp.function') }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong>
                            {{ __('messages.security.encryption.gov.components.zkp.benefit') }}</p>
                    </div>
                    <div class="bg-gray-50 border-2 border-[#00B4B4] rounded-lg p-4 shadow">
                        <h4 class="font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.components.sse.name') }}</h4>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.function') }}</strong>
                            {{ __('messages.security.encryption.gov.components.sse.function') }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>{{ __('messages.security.encryption.gov.components.mobile.benefit') }}</strong>
                            {{ __('messages.security.encryption.gov.components.sse.benefit') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 8: خدمة المنظومة الوطنية -->
        <section class="py-16 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center"
                    style="font-family: 'Almarai', sans-serif; color: #0055A4;">
                    {{ __('messages.security.encryption.gov.national.title') }}
                </h3>
                <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                    <div class="bg-white p-6 border-r-4 rounded-lg shadow-lg"
                        style="border-color: #0055A4; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.national.citizens.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.citizens.text') }}
                        </p>
                    </div>
                    <div class="bg-white p-6 border-r-4 rounded-lg shadow-lg"
                        style="border-color: #00B4B4; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.national.government.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.government.text') }}
                        </p>
                    </div>
                    <div class="bg-white p-6 border-r-4 rounded-lg shadow-lg"
                        style="border-color: #FF0000; {{ app()->getLocale() == 'ar' ? 'border-right-width: 4px;' : 'border-left-width: 4px; border-right-width: 0;' }}">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.national.cybersecurity.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.national.cybersecurity.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 10: التقنيات الداعمة -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-5">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8"
                    style="font-family: 'Almarai', sans-serif; color: #0055A4;">
                    {{ __('messages.security.encryption.gov.technologies.title') }}
                </h3>
                <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.technologies.tls.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.tls.text') }}
                        </p>
                    </div>
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.technologies.cse.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.cse.text') }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.technologies.zkp.title') }}
                        </h4>
                        <p class="text-gray-700 leading-relaxed">
                            {{ __('messages.security.encryption.gov.technologies.zkp.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 10.5: التطبيقات العملية في القطاع الإماراتي -->
        <section class="py-16 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-6xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center"
                    style="color: #0055A4; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.applications.title') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="font-family: 'Almarai', sans-serif;">
                    <div
                        class="bg-white border-2 border-[#0055A4] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-3xl mb-4 text-center">🆔</div>
                        <h3 class="text-xl font-bold mb-3 text-center" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.applications.uae_pass.title') }}
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-center">
                            {{ __('messages.security.encryption.gov.applications.uae_pass.description') }}
                        </p>
                    </div>
                    <div
                        class="bg-white border-2 border-[#00B4B4] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-3xl mb-4 text-center">🏥</div>
                        <h3 class="text-xl font-bold mb-3 text-center" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.applications.health.title') }}
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-center">
                            {{ __('messages.security.encryption.gov.applications.health.description') }}
                        </p>
                    </div>
                    <div
                        class="bg-white border-2 border-[#FF0000] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="text-3xl mb-4 text-center">💼</div>
                        <h3 class="text-xl font-bold mb-3 text-center" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.applications.smart_services.title') }}
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-center">
                            {{ __('messages.security.encryption.gov.applications.smart_services.description') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 11: الاعتراف الرسمي والشراكات -->
        <section class="py-16 bg-gradient-to-b from-[#F8FAFC] to-white">
            <div class="max-w-6xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center"
                    style="color: #0055A4; font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.recognition.title') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                    style="font-family: 'Almarai', sans-serif;">
                    <div
                        class="bg-white border-2 border-[#0055A4] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                        <div class="text-4xl mb-4">✅</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.recognition.funding.title') }}
                        </h3>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ __('messages.security.encryption.gov.recognition.funding.description') }}
                        </p>
                    </div>
                    <div
                        class="bg-white border-2 border-[#00B4B4] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                        <div class="text-4xl mb-4">🏆</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.recognition.member.title') }}
                        </h3>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ __('messages.security.encryption.gov.recognition.member.description') }}
                        </p>
                    </div>
                    <div
                        class="bg-white border-2 border-[#FF0000] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                        <div class="text-4xl mb-4">🤝</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.recognition.partner.title') }}
                        </h3>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ __('messages.security.encryption.gov.recognition.partner.description') }}
                        </p>
                    </div>
                    <div
                        class="bg-white border-2 border-[#1A365D] rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                        <div class="text-4xl mb-4">⚖️</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.recognition.legal.title') }}
                        </h3>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ __('messages.security.encryption.gov.recognition.legal.description') }}
                        </p>
                    </div>
                </div>
                <div class="mt-8 space-y-4" style="font-family: 'Almarai', sans-serif;">
                    <div class="flex items-start bg-white p-4 rounded-lg shadow">
                        <span
                            class="text-[#0055A4] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong
                                class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.data_law') }}</strong>
                            {{ __('messages.security.encryption.gov.compliance.data_law.text') }}
                        </p>
                    </div>
                    <div class="flex items-start bg-white p-4 rounded-lg shadow">
                        <span
                            class="text-[#0055A4] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong
                                class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.patent') }}</strong>
                            {{ __('messages.security.encryption.gov.compliance.patent.text') }}
                        </p>
                    </div>
                    <div class="flex items-start bg-white p-4 rounded-lg shadow">
                        <span
                            class="text-[#0055A4] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong
                                class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.hub71') }}</strong>
                            {{ __('messages.security.encryption.gov.compliance.hub71.text') }}
                        </p>
                    </div>
                    <div class="flex items-start bg-white p-4 rounded-lg shadow">
                        <span
                            class="text-[#0055A4] {{ app()->getLocale() == 'ar' ? 'mr-3' : 'ml-3' }} mt-1 font-bold">•</span>
                        <p class="text-gray-700 leading-relaxed flex-1">
                            <strong
                                class="text-gray-900">{{ __('messages.security.encryption.gov.compliance.khalifa') }}</strong>
                            {{ __('messages.security.encryption.gov.compliance.khalifa.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- القسم 12: دعوة العمل (CTA) -->
        <section class="py-16 bg-gradient-to-b from-[#0055A4] to-[#1A365D] text-white">
            <div class="max-w-6xl mx-auto px-5">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-center" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.cta.enhanced.title') }}
                </h2>
                <p class="text-xl text-center mb-12 text-gray-200" style="font-family: 'Almarai', sans-serif;">
                    {{ __('messages.security.encryption.gov.cta.enhanced.subtitle') }}
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="font-family: 'Almarai', sans-serif;">
                    <!-- للجهات الحكومية -->
                    <div class="bg-white text-gray-900 rounded-lg p-6 shadow-xl hover:shadow-2xl transition-shadow">
                        <h3 class="text-2xl font-bold mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.cta.government.title') }}
                        </h3>
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ __('messages.security.encryption.gov.cta.government.description') }}
                        </p>
                        <a href="{{ route('contact', app()->getLocale()) }}?type=government"
                            class="inline-block w-full text-center px-6 py-3 bg-[#0055A4] text-white font-bold rounded-lg transition-colors hover:bg-[#1A365D]">
                            {{ __('messages.security.encryption.gov.cta.government.button') }}
                        </a>
                    </div>

                    <!-- للشركاء التقنيين -->
                    <div class="bg-white text-gray-900 rounded-lg p-6 shadow-xl hover:shadow-2xl transition-shadow">
                        <h3 class="text-2xl font-bold mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.cta.partner.title') }}
                        </h3>
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ __('messages.security.encryption.gov.cta.partner.description') }}
                        </p>
                        <a href="{{ route('contact', app()->getLocale()) }}?type=partner"
                            class="inline-block w-full text-center px-6 py-3 bg-[#00B4B4] text-white font-bold rounded-lg transition-colors hover:bg-[#008B8B]">
                            {{ __('messages.security.encryption.gov.cta.partner.button') }}
                        </a>
                    </div>

                    <!-- للمستثمرين المؤسسيين -->
                    <div class="bg-white text-gray-900 rounded-lg p-6 shadow-xl hover:shadow-2xl transition-shadow">
                        <h3 class="text-2xl font-bold mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.cta.investor.title') }}
                        </h3>
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ __('messages.security.encryption.gov.cta.investor.description') }}
                        </p>
                        <a href="{{ route('contact', app()->getLocale()) }}?type=investor"
                            class="inline-block w-full text-center px-6 py-3 bg-[#FF0000] text-white font-bold rounded-lg transition-colors hover:bg-[#CC0000]">
                            {{ __('messages.security.encryption.gov.cta.investor.button') }}
                        </a>
                    </div>

                    <!-- للباحثين والمطورين -->
                    <div class="bg-white text-gray-900 rounded-lg p-6 shadow-xl hover:shadow-2xl transition-shadow">
                        <h3 class="text-2xl font-bold mb-3" style="color: #0055A4;">
                            {{ __('messages.security.encryption.gov.cta.developer.title') }}
                        </h3>
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            {{ __('messages.security.encryption.gov.cta.developer.description') }}
                        </p>
                        <a href="{{ route('contact', app()->getLocale()) }}?type=developer"
                            class="inline-block w-full text-center px-6 py-3 bg-[#1A365D] text-white font-bold rounded-lg transition-colors hover:bg-[#0F2537]">
                            {{ __('messages.security.encryption.gov.cta.developer.button') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
