@extends('layouts.app')

@section('title', __('messages.nav.home') . ' - ' . __('messages.header.title'))
@section('description', __('messages.hero.subtitle'))

@section('content')
    <!-- Hero Section -->
    <x-hero />

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.about.title') }}</h2>
                <p>{{ __('messages.about.subtitle') }}</p>
                <a href="{{ route('services') }}" class="btn btn-secondary">{{ __('messages.about.button') }}</a>
            </div>
        </div>
    </section>

    <!-- Services Preview Section -->
    <section id="services" class="services">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.services.title') }}</h2>
            </div>

            <div class="services-grid">
                <x-service-card
                    icon="fas fa-shield-alt"
                    :title="__('messages.services.surveillance.title')"
                    :description="__('messages.services.surveillance.description')"
                    :link="route('services')"
                />

                <x-service-card
                    icon="fas fa-bell"
                    :title="__('messages.services.alarm.title')"
                    :description="__('messages.services.alarm.description')"
                    :link="route('services')"
                />

                <x-service-card
                    icon="fas fa-door-open"
                    :title="__('messages.services.gates.title')"
                    :description="__('messages.services.gates.description')"
                    :link="route('services')"
                />

                <x-service-card
                    icon="fas fa-home"
                    :title="__('messages.services.smart_home.title')"
                    :description="__('messages.services.smart_home.description')"
                    :link="route('services')"
                />
            </div>
        </div>
    </section>

    <!-- Technology Section -->
    <section class="technology">
        <div class="container">
            <div class="tech-content">
                <div class="tech-text">
                    <h2>{{ __('messages.tech.title') }}</h2>
                    <p>{{ __('messages.about.description') }}</p>

                    <div class="tech-features">
                        <div class="tech-feature">
                            <div class="tech-feature-icon">
                                <i class="fas fa-shield-virus"></i>
                            </div>
                            <div class="tech-feature-text">
                                <h4>{{ __('messages.tech.feature.family.title') }}</h4>
                                <p>{{ __('messages.tech.feature.family.description') }}</p>
                            </div>
                        </div>

                        <div class="tech-feature">
                            <div class="tech-feature-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="tech-feature-text">
                                <h4>{{ __('messages.tech.feature.business.title') }}</h4>
                                <p>{{ __('messages.tech.feature.business.description') }}</p>
                            </div>
                        </div>

                        <div class="tech-feature">
                            <div class="tech-feature-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <div class="tech-feature-text">
                                <h4>{{ __('messages.tech.feature.servers.title') }}</h4>
                                <p>{{ __('messages.tech.feature.servers.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tech-image">
                    <div class="experience-badge">
                        <span class="badge-number">20+</span>
                        <span class="badge-text">{{ __('messages.tech.experience') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Preview Section -->
    <section id="projects" class="projects">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.projects.title') }}</h2>
            </div>

            <div class="projects-grid">
                <x-project-card
                    image="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project1.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project2.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project3.title')"
                />

                <x-project-card
                    image="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&h=300&fit=crop"
                    :title="__('messages.projects.project4.title')"
                />
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('projects') }}" class="btn btn-primary text-white">{{ __('messages.common.learn_more') }}</a>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="why-us">
        <div class="container">
            <div class="why-content">
                <div class="why-text">
                    <h2>{{ __('messages.why.title') }}</h2>
                    <ul class="why-list">
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason1') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason2') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason3') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason4') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason5') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('messages.why.reason6') }}</li>
                    </ul>
                </div>

                <div class="why-cards">
                    <div class="why-card">
                        <div class="why-card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>{{ __('messages.why.vision.title') }}</h3>
                        <p>{{ __('messages.why.vision.description') }}</p>
                        <a href="{{ route('about') }}" class="read-more">{{ __('messages.why.read_more') }}</a>
                    </div>

                    <div class="why-card">
                        <div class="why-card-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>{{ __('messages.why.mission.title') }}</h3>
                        <p>{{ __('messages.why.mission.description') }}</p>
                        <a href="{{ route('about') }}" class="read-more">{{ __('messages.why.read_more') }}</a>
                    </div>

                    <div class="why-card">
                        <div class="why-card-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>{{ __('messages.why.awards.title') }}</h3>
                        <p>{{ __('messages.why.awards.description') }}</p>
                        <a href="{{ route('about') }}" class="read-more">{{ __('messages.why.read_more') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>{{ __('messages.newsletter.title') }}</h2>
                <p>{{ __('messages.newsletter.subtitle') }}</p>
                <form class="newsletter-form" id="newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('messages.newsletter.placeholder') }}" required>
                    <button type="submit" class="btn btn-primary text-white">{{ __('messages.newsletter.button') }}</button>
                </form>
                <p class="newsletter-link">{{ __('messages.newsletter.website') }}</p>
            </div>
        </div>
    </section>
@endsection
