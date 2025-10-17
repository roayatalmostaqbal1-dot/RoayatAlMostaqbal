@extends('layouts.app')

@section('title', __('messages.nav.services') . ' - ' . __('messages.header.title'))
@section('description', __('messages.services.title'))

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.nav.services') }}</h1>
            <p>{{ __('messages.services.title') }}</p>
        </div>
    </section>

    <!-- Security Services -->
    <section class="security-services">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.about.services.security.install_cameras.title') }}</h2>
                <p>{{ __('messages.about.services.description') }}</p>
            </div>

            <div class="services-grid">
                <x-service-card
                    icon="fas fa-video"
                    title="{{ __('messages.about.services.security.install_cameras.title') }}"
                    description="{{ __('messages.about.services.security.install_cameras.description') }}"
                />

                <x-service-card
                    icon="fas fa-bell"
                    title="{{ __('messages.about.services.security.alarm_systems.title') }}"
                    description="{{ __('messages.about.services.security.alarm_systems.description') }}"
                />

                <x-service-card
                    icon="fas fa-door-open"
                    title="{{ __('messages.about.services.security.security_gates.title') }}"
                    description="{{ __('messages.about.services.security.security_gates.description') }}"
                />

                <x-service-card
                    icon="fas fa-key"
                    title="{{ __('messages.about.services.security.access_control.title') }}"
                    description="{{ __('messages.about.services.security.access_control.description') }}"
                />

                <x-service-card
                    icon="fas fa-home"
                    title="{{ __('messages.about.services.security.smart_home.title') }}"
                    description="{{ __('messages.about.services.security.smart_home.description') }}"
                />
            </div>
        </div>
    </section>

    <!-- Technology Services -->
    <section class="technology-services">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.about.services.technology.ai_solutions.title') }}</h2>
                <p>{{ __('messages.about.services.description') }}</p>
            </div>

            <div class="services-grid">
                <x-service-card
                    icon="fas fa-brain"
                    title="{{ __('messages.about.services.technology.ai_solutions.title') }}"
                    description="{{ __('messages.about.services.technology.ai_solutions.description') }}"
                />

                <x-service-card
                    icon="fas fa-digital-tachograph"
                    title="{{ __('messages.about.services.technology.digital_transformation.title') }}"
                    description="{{ __('messages.about.services.technology.digital_transformation.description') }}"
                />

                <x-service-card
                    icon="fas fa-robot"
                    title="{{ __('messages.about.services.technology.chatbot.title') }}"
                    description="{{ __('messages.about.services.technology.chatbot.description') }}"
                />

                <x-service-card
                    icon="fas fa-desktop"
                    title="{{ __('messages.about.services.technology.monitoring_platforms.title') }}"
                    description="{{ __('messages.about.services.technology.monitoring_platforms.description') }}"
                />

                <x-service-card
                    icon="fas fa-user-check"
                    title="{{ __('messages.about.services.technology.recognition_systems.title') }}"
                    description="{{ __('messages.about.services.technology.recognition_systems.description') }}"
                />
            </div>
        </div>
    </section>

    <!-- Service Process -->
    <section class="service-process">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.about.process.title') }}</h2>
                <p>{{ __('messages.about.process.description') }}</p>
            </div>

            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>{{ __('messages.about.process.step1.title') }}</h3>
                        <p>{{ __('messages.about.process.step1.description') }}</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>{{ __('messages.about.process.step2.title') }}</h3>
                        <p>{{ __('messages.about.process.step2.description') }}</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>{{ __('messages.about.process.step3.title') }}</h3>
                        <p>{{ __('messages.about.process.step3.description') }}</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>{{ __('messages.about.process.step4.title') }}</h3>
                        <p>{{ __('messages.about.process.step4.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Features -->
    <section class="service-features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('messages.about.features.security.title') }}</h3>
                    <p>{{ __('messages.about.features.security.description') }}</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>{{ __('messages.about.features.usability.title') }}</h3>
                    <p>{{ __('messages.about.features.usability.description') }}</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>{{ __('messages.about.features.support.title') }}</h3>
                    <p>{{ __('messages.about.features.support.description') }}</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>{{ __('messages.about.features.maintenance.title') }}</h3>
                    <p>{{ __('messages.about.features.maintenance.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2>{{ __('messages.about.contact_cta.title') }}</h2>
                <p>{{ __('messages.about.contact_cta.description') }}</p>
                <a href="{{ route('contact', app()->getLocale()) }}" class="btn btn-primary text-white">{{ __('messages.about.contact_cta.button') }}</a>
            </div>
        </div>
    </section>
@endsection


