@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - ' . __('messages.header.title'))
@section('description', __('messages.projects.title'))

@section('content')
    <!-- Page Header -->
    <section class="py-20 bg-[linear-gradient(135deg,#051824_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">{{ __('messages.nav.projects') }}</h1>
            <p class="text-lg text-gray-300">{{ __('messages.projects.subtitle') }}</p>
        </div>
    </section>

    <!-- Projects Filter -->
    <section class="py-12 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="filter-btn active px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-[#27e9b5] text-[#051824]" data-filter="all">{{ __('messages.projects.filter.all') }}</button>
                <button class="filter-btn px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824]" data-filter="surveillance">{{ __('messages.projects.filter.surveillance') }}</button>
                <button class="filter-btn px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824]" data-filter="security">{{ __('messages.projects.filter.security') }}</button>
                <button class="filter-btn px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824]" data-filter="ai">{{ __('messages.projects.filter.ai') }}</button>
                <button class="filter-btn px-6 py-3 rounded-[30px] font-bold transition-all duration-300 bg-transparent text-white border-2 border-[#27e9b5] hover:bg-[#27e9b5] hover:text-[#051824]" data-filter="smart">{{ __('messages.projects.filter.smart') }}</button>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-20 bg-[#051824]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Surveillance Projects -->
                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="surveillance">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop" alt="نظام مراقبة متطور" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.surveillance.title.1') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.surveillance.description.1') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.cameras') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.ai') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="surveillance">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop" alt="مراقبة ذكية" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.surveillance.title.2') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.surveillance.description.2') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.dataAnalysis') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.smartWatching') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Security Projects -->
                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="security">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&h=300&fit=crop" alt="أنظمة أمنية" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.security.title.1') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.security.description.1') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.smartCards') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.advancedControl') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="security">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&h=300&fit=crop" alt="أنظمة إنذار" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.security.title.2') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.security.description.2') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.smartAlarm') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.comprehensiveProtection') }}</span>
                        </div>
                    </div>
                </div>

                <!-- AI Projects -->
                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="ai">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&h=300&fit=crop" alt="الذكاء الاصطناعي" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.ai.title.1') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.ai.description.1') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.ai') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.dataAnalysis') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="ai">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1555255707-c07966088b7b?w=400&h=300&fit=crop" alt="التعرف على الوجوه" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.ai.title.2') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.ai.description.2') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.faceRecognition') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.vehicleAnalysis') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Smart Home Projects -->
                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="smart">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop" alt="المنزل الذكي" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.smart.title.1') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.smart.description.1') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.smartHome') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.smartControl') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300" data-category="smart">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop" alt="المساعد الافتراضي" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-eye"></i></a>
                            <a href="#" class="w-12 h-12 bg-[#27e9b5] rounded-full flex items-center justify-center text-[#051824] hover:bg-white transition-all duration-300"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('messages.projects.smart.title.2') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('messages.projects.smart.description.2') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.virtualAssistant') }}</span>
                            <span class="px-3 py-1 bg-[#27e9b5] text-[#051824] rounded-full text-sm font-bold">{{ __('messages.projects.category.ai') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Statistics -->
    <section class="py-20 bg-[linear-gradient(135deg,#162936_0%,#3b5265_100%)]">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-3">150+</div>
                    <div class="text-lg text-gray-300">{{ __('messages.projects.stats.completedProjects') }}</div>
                </div>
                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-3">50+</div>
                    <div class="text-lg text-gray-300">{{ __('messages.projects.stats.happyClients') }}</div>
                </div>
                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-3">10+</div>
                    <div class="text-lg text-gray-300">{{ __('messages.projects.stats.yearsExperience') }}</div>
                </div>
                <div class="bg-[#051824] p-8 rounded-[20px] text-center hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
                    <div class="text-5xl font-bold text-[#27e9b5] mb-3">24/7</div>
                    <div class="text-lg text-gray-300">{{ __('messages.projects.stats.technicalSupport') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-[linear-gradient(135deg,#3b5265_0%,#162936_100%)]">
        <div class="max-w-6xl mx-auto px-5 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('messages.projects.cta.title') }}</h2>
            <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">{{ __('messages.projects.cta.description') }}</p>
            <a href="{{ route('contact', app()->getLocale()) }}" class="inline-block px-8 py-4 rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer text-base bg-gradient-to-r from-[#27e9b5] to-[#27eb5] text-[#051824] shadow-[0_4px_15px_rgba(39,233,181,0.3)] hover:shadow-[0_6px_20px_rgba(39,233,181,0.4)] hover:translate-y-[-2px]">{{ __('messages.nav.contact') }}</a>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Project filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Remove active class and styles from all buttons
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-[#27e9b5]', 'text-[#051824]');
                    btn.classList.add('bg-transparent', 'text-white', 'border-2', 'border-[#27e9b5]');
                });
                // Add active class and styles to clicked button
                this.classList.add('active', 'bg-[#27e9b5]', 'text-[#051824]');
                this.classList.remove('bg-transparent', 'text-white', 'border-2', 'border-[#27e9b5]');

                // Filter projects
                projectCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                        card.classList.add('fade-in');
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('fade-in');
                    }
                });
            });
        });
    });
</script>
@endpush


