@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - ' . __('messages.header.title'))
@section('description', __('messages.projects.title'))

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.nav.projects') }}</h1>
            <p>{{ __('messages.projects.subtitle') }}</p>
        </div>
    </section>

    <!-- Projects Filter -->
    <section class="projects-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">{{ __('messages.projects.filter.all') }}</button>
                <button class="filter-btn" data-filter="surveillance">{{ __('messages.projects.filter.surveillance') }}</button>
                <button class="filter-btn" data-filter="security">{{ __('messages.projects.filter.security') }}</button>
                <button class="filter-btn" data-filter="ai">{{ __('messages.projects.filter.ai') }}</button>
                <button class="filter-btn" data-filter="smart">{{ __('messages.projects.filter.smart') }}</button>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="projects-showcase">
        <div class="container">
            <div class="projects-grid">
                <!-- Surveillance Projects -->
                <div class="project-card" data-category="surveillance">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop" alt="نظام مراقبة متطور">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.surveillance.title.1') }}</h3>
                        <p>{{ __('messages.projects.surveillance.description.1') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.cameras') }}</span>
                            <span class="tag">{{ __('messages.projects.category.ai') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card" data-category="surveillance">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop" alt="مراقبة ذكية">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.surveillance.title.2') }}</h3>
                        <p>{{ __('messages.projects.surveillance.description.2') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.dataAnalysis') }}</span>
                            <span class="tag">{{ __('messages.projects.category.smartWatching') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Security Projects -->
                <div class="project-card" data-category="security">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&h=300&fit=crop" alt="أنظمة أمنية">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.security.title.1') }}</h3>
                        <p>{{ __('messages.projects.security.description.1') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.smartCards') }}</span>
                            <span class="tag">{{ __('messages.projects.category.advancedControl') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card" data-category="security">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&h=300&fit=crop" alt="أنظمة إنذار">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.security.title.2') }}</h3>
                        <p>{{ __('messages.projects.security.description.2') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.smartAlarm') }}</span>
                            <span class="tag">{{ __('messages.projects.category.comprehensiveProtection') }}</span>
                        </div>
                    </div>
                </div>

                <!-- AI Projects -->
                <div class="project-card" data-category="ai">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&h=300&fit=crop" alt="الذكاء الاصطناعي">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.ai.title.1') }}</h3>
                        <p>{{ __('messages.projects.ai.description.1') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.ai') }}</span>
                            <span class="tag">{{ __('messages.projects.category.dataAnalysis') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card" data-category="ai">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1555255707-c07966088b7b?w=400&h=300&fit=crop" alt="التعرف على الوجوه">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.ai.title.2') }}</h3>
                        <p>{{ __('messages.projects.ai.description.2') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.faceRecognition') }}</span>
                            <span class="tag">{{ __('messages.projects.category.vehicleAnalysis') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Smart Home Projects -->
                <div class="project-card" data-category="smart">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop" alt="المنزل الذكي">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.smart.title.1') }}</h3>
                        <p>{{ __('messages.projects.smart.description.1') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.smartHome') }}</span>
                            <span class="tag">{{ __('messages.projects.category.smartControl') }}</span>
                        </div>
                    </div>
                </div>

                <div class="project-card" data-category="smart">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop" alt="المساعد الافتراضي">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="#" class="action-btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="action-btn"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>{{ __('messages.projects.smart.title.2') }}</h3>
                        <p>{{ __('messages.projects.smart.description.2') }}</p>
                        <div class="project-tags">
                            <span class="tag">{{ __('messages.projects.category.virtualAssistant') }}</span>
                            <span class="tag">{{ __('messages.projects.category.ai') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Statistics -->
    <section class="project-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">{{ __('messages.projects.stats.completedProjects') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">{{ __('messages.projects.stats.happyClients') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">{{ __('messages.projects.stats.yearsExperience') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">{{ __('messages.projects.stats.technicalSupport') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2>{{ __('messages.projects.cta.title') }}</h2>
                <p>{{ __('messages.projects.cta.description') }}</p>
                <a href="{{ route('contact', app()->getLocale()) }}" class="btn btn-primary text-white">{{ __('messages.nav.contact') }}</a>
            </div>
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

                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

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


