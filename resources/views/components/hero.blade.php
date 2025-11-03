<!-- Hero Section Component -->
<section class="py-20 relative bg-[linear-gradient(135deg,#051824_0%,#162936_50%,#3b5265_100%)] overflow-hidden">
    <div class="absolute inset-0 opacity-50"></div>
    <div class="max-w-6xl mx-auto px-5 relative z-10">
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 animate-fadeInUp">{{ __('messages.hero.title') }}</h1>
            <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-2xl mx-auto animate-fadeInUp">{{ __('messages.hero.subtitle') }}</p>
            <button class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px] animate-fadeInUp" onclick="scrollToSection('about')">{{ __('messages.hero.button') }}</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300 animate-fadeInUp">
                <div class="text-5xl text-[#27e9b5] mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-white">{{ __('messages.hero.feature.research') }}</h3>
            </div>
            <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300 animate-fadeInUp">
                <div class="text-5xl text-[#27e9b5] mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-white">{{ __('messages.hero.feature.strategy') }}</h3>
            </div>
            <div class="bg-[#162936] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300 animate-fadeInUp">
                <div class="text-5xl text-[#27e9b5] mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-bold text-white">{{ __('messages.hero.feature.resources') }}</h3>
            </div>
        </div>
    </div>
</section>


