<!-- Footer Component -->
<footer class="bg-[#051824] text-white py-20 border-t border-[#3b5265]">
    <div class="max-w-6xl mx-auto px-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div>
                <div class="mb-6">
                    <h3 class="text-xl font-bold">{{ __('messages.header.title') }}</h3>
                    <p class="text-xs text-gray-400">{{ __('messages.header.subtitle') }}</p>
                </div>

                <p class="text-gray-300 text-sm mb-6">{{ __('messages.footer.description') }}</p>
                <div class="flex gap-4">
                    <a href="#" aria-label="Facebook" class="text-[#27e9b5] hover:text-white transition-colors"><i class="fab fa-facebook text-lg"></i></a>
                    <a href="#" aria-label="Twitter" class="text-[#27e9b5] hover:text-white transition-colors"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" aria-label="Instagram" class="text-[#27e9b5] hover:text-white transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="https://www.linkedin.com/in/roayata-almostaqbal-211009397/" aria-label="LinkedIn" class="text-[#27e9b5] hover:text-white transition-colors"><i class="fab fa-linkedin text-lg"></i></a>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4">{{ __('messages.footer.other_pages') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home' , app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.home') }}</a></li>
                    <li><a href="{{ route('about', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.about') }}</a></li>
                    <li><a href="{{ route('services', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.services') }}</a></li>
                    <li><a href="{{ route('contact', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.contact') }}</a></li>
                 </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">{{ __('messages.footer.security_privacy') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('security.encryption', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.encryption') }}</a></li>
                    <li><a href="{{ route('security.privacy', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.nav.privacy') }}</a></li>
                    <li><a href="{{ route('security.data-protection', app()->getLocale()) }}" class="text-gray-300 hover:text-[#27e9b5] transition-colors">{{ __('messages.footer.data_protection') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4">{{ __('messages.footer.newsletter') }}</h4>
                <p class="text-gray-300 text-sm mb-4">{{ __('messages.footer.newsletter_subtitle') }}</p>
                <form class="flex flex-col gap-2" id="footer-newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('messages.newsletter.placeholder') }}" class="px-4 py-2 rounded-[10px] bg-[#162936] text-white border border-[#3b5265] focus:outline-none focus:border-[#27e9b5]" required>
                    <button type="submit" class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.common.subscribe') }}</button>
                </form>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center py-6 border-t border-[#3b5265]">
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-[#27e9b5]"></i>
                <span class="text-gray-300">{{ __('messages.header.address') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-[#27e9b5]"></i>
                <span class="text-gray-300">{{ __('messages.header.email') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-phone text-[#27e9b5]"></i>
                <span class="text-gray-300">{{ __('messages.header.phone') }}</span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 justify-center items-center py-6 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} {{ __('messages.header.title') }}. {{ __('messages.footer.copyright') }}</p>
            <p>{{ __('messages.footer.email_label') }} {{ __('messages.header.email') }}</p>
        </div>
    </div>
</footer>


