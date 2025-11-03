@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - ' . __('messages.header.title'))
@section('description', 'تواصلوا معنا للحصول على استشارة مجانية')

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">{{ __('messages.contact.page_header.title') }}</h1>
            <p class="text-lg text-gray-300">{{ __('messages.contact.page_header.description') }}</p>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-4xl font-bold text-white mb-8">{{ __('messages.contact.info.title') }}</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="text-4xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.contact.info.address.title') }}</h3>
                                <p class="text-gray-300">{{ __('messages.contact.info.address.description') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="text-4xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.contact.info.phone.title') }}</h3>
                                <p class="text-gray-300">{{ __('messages.contact.info.phone.description') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="text-4xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.contact.info.email.title') }}</h3>
                                <p class="text-gray-300">{{ __('messages.contact.info.email.description') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="text-4xl text-[#27e9b5] flex-shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.contact.info.working_hours.title') }}</h3>
                                <p class="text-gray-300">{{ __('messages.contact.info.working_hours.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#162936] p-8 rounded-[20px]">
                    <h2 class="text-4xl font-bold text-white mb-8">{{ __('messages.contact.form.title') }}</h2>
                    <form class="space-y-6" id="contact-form" action="{{ route('contact.store', app()->getLocale()) }}" method="POST">
                        @csrf

                        <div>
                            <label for="name" class="block text-white font-bold mb-2">{{ __('messages.contact.form.name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-white font-bold mb-2">{{ __('messages.contact.form.email') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-white font-bold mb-2">{{ __('messages.contact.form.phone') }}</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company" class="block text-white font-bold mb-2">{{ __('messages.contact.form.company') }}</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}" class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">
                            @error('company')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="service" class="block text-white font-bold mb-2">{{ __('messages.contact.form.service') }}</label>
                            <select id="service" name="service" class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">
                                <option value="">{{ __('messages.contact.form.service.placeholder') }}</option>
                                <option value="surveillance" {{ old('service') == 'surveillance' ? 'selected' : '' }}>
                                    {{ __('messages.contact.form.service.option.surveillance') }}
                                </option>
                                <option value="security" {{ old('service') == 'security' ? 'selected' : '' }}>
                                    {{ __('messages.contact.form.service.option.security') }}
                                </option>
                                <option value="ai" {{ old('service') == 'ai' ? 'selected' : '' }}>
                                    {{ __('messages.contact.form.service.option.ai') }}
                                </option>
                                <option value="smart" {{ old('service') == 'smart' ? 'selected' : '' }}>
                                    {{ __('messages.contact.form.service.option.smart') }}
                                </option>
                                <option value="consultation" {{ old('service') == 'consultation' ? 'selected' : '' }}>
                                    {{ __('messages.contact.form.service.option.consultation') }}
                                </option>
                            </select>
                            @error('service')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-white font-bold mb-2">{{ __('messages.contact.form.message') }}</label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 bg-[#051824] text-white border-2 border-[#27e9b5] rounded-lg focus:outline-none focus:border-[#27eb5]">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">
                            {{ __('messages.contact.form.submit') }}
                        </button>

                        @if(session('success'))
                            <p class="text-green-500 mt-2">{{ session('success') }}</p>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-4xl font-bold text-white mb-8 text-center">{{ __('messages.contact.map.title') }}</h2>
            <div class="rounded-[20px] overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.3)]">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.123456789!2d55.123456789!3d24.123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDA3JzI0LjQiTiA1NcKwMDcnMjQuNCJF!5e0!3m2!1sen!2sae!4v1234567890123!5m2!1sen!2sae"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.contact.faq.title') }}</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">{{ __('messages.contact.faq.description') }}</p>
            </div>

            <div class="space-y-4 max-w-3xl mx-auto">
                <div class="bg-[#051824] rounded-[20px] overflow-hidden">
                    <div class="faq-question cursor-pointer p-6 flex justify-between items-center hover:bg-[#162936] transition-colors duration-300">
                        <h3 class="text-lg font-bold text-white">{{ __('messages.contact.faq.item1.question') }}</h3>
                        <i class="fas fa-chevron-down text-[#27e9b5] transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-gray-300">{{ __('messages.contact.faq.item1.answer') }}</p>
                    </div>
                </div>

                <div class="bg-[#051824] rounded-[20px] overflow-hidden">
                    <div class="faq-question cursor-pointer p-6 flex justify-between items-center hover:bg-[#162936] transition-colors duration-300">
                        <h3 class="text-lg font-bold text-white">{{ __('messages.contact.faq.item2.question') }}</h3>
                        <i class="fas fa-chevron-down text-[#27e9b5] transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-gray-300">{{ __('messages.contact.faq.item2.answer') }}</p>
                    </div>
                </div>

                <div class="bg-[#051824] rounded-[20px] overflow-hidden">
                    <div class="faq-question cursor-pointer p-6 flex justify-between items-center hover:bg-[#162936] transition-colors duration-300">
                        <h3 class="text-lg font-bold text-white">{{ __('messages.contact.faq.item3.question') }}</h3>
                        <i class="fas fa-chevron-down text-[#27e9b5] transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-gray-300">{{ __('messages.contact.faq.item3.answer') }}</p>
                    </div>
                </div>

                <div class="bg-[#051824] rounded-[20px] overflow-hidden">
                    <div class="faq-question cursor-pointer p-6 flex justify-between items-center hover:bg-[#162936] transition-colors duration-300">
                        <h3 class="text-lg font-bold text-white">{{ __('messages.contact.faq.item4.question') }}</h3>
                        <i class="fas fa-chevron-down text-[#27e9b5] transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-gray-300">{{ __('messages.contact.faq.item4.answer') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // FAQ accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('i');

                    // Close other open FAQs
                    faqQuestions.forEach(q => {
                        if (q !== question) {
                            q.nextElementSibling.classList.add('hidden');
                            q.querySelector('i').style.transform = 'rotate(0deg)';
                        }
                    });

                    // Toggle current FAQ
                    answer.classList.toggle('hidden');
                    icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            });
        });
    </script>
    @endpush
@endsection

