@extends('layouts.app')

@section('title', __('messages.nav.about') . ' - ' . __('messages.header.title'))
@section('description', __('messages.about.description'))

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.nav.about') }}</h1>
            <p>{{ __('messages.about.subtitle') }}</p>
        </div>
    </section>

    <!-- About Features -->
    <div class="about-features">
        <!-- Headquarters -->
        <div class="about-feature">
            <div class="feature-icon blue">
                <i class="fas fa-building"></i>
            </div>
            <div class="feature-content">
                <h3>{{ __('messages.about.feature.headquarters.title') }}</h3>
                <ul>
                    <li>{{ __('messages.about.feature.headquarters.description') }}</li>
                </ul>
            </div>
        </div>

        <!-- Vision -->
        <div class="about-feature">
            <div class="feature-icon green">
                <i class="fas fa-eye"></i>
            </div>
            <div class="feature-content">
                <h3>{{ __('messages.why.vision.title') }}</h3>
                <p>{{ __('messages.why.vision.description') }}</p>
            </div>
        </div>

        <!-- Mission -->
        <div class="about-feature">
            <div class="feature-icon yellow">
                <i class="fas fa-bullseye"></i>
            </div>
            <div class="feature-content">
                <h3>{{ __('messages.why.mission.title') }}</h3>
                <p>{{ __('messages.why.mission.description') }}</p>
            </div>
        </div>
    </div>


    <!-- Services Overview -->
    <section class="services-overview">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.about.services.title') }}</h2>
                <p>{{ __('messages.about.services.description') }}</p>
            </div>

            <div class="services-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active"
                        data-tab="security">{{ __('messages.about.services.security.VisionOfTheFutureSecuritySection') }}</button>
                    <button class="tab-btn"
                        data-tab="technology">{{ __('messages.about.services.technology.SaddDepartmentTechnology') }}</button>
                </div>

                <div class="tab-content">
                    <div class="tab-panel active" id="security">
                        <div class="services-grid">
                            <div class="service-item">
                                <i class="fas fa-video"></i>
                                <h3>{{ __('messages.about.servers.title.1') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-bell"></i>
                                <h3>{{ __('messages.about.servers.title.2') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-door-open"></i>
                                <h3>{{ __('messages.about.servers.title.3') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-key"></i>
                                <h3>{{ __('messages.about.servers.title.4') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-home"></i>
                                <h3>{{ __('messages.about.servers.title.5') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="tab-panel" id="technology">
                        <div class="services-grid">
                            <div class="service-item">
                                <i class="fas fa-brain"></i>
                                <h3>{{ __('messages.about.services.sadd.item.1') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-digital-tachograph"></i>
                                <h3>{{ __('messages.about.services.Sadd.item.2') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-robot"></i>
                                <h3>{{ __('messages.about.services.Sadd.item.3') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-desktop"></i>
                                <h3>{{ __('messages.about.services.Sadd.item.4') }}</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-user-check"></i>
                                <h3>{{ __('messages.about.services.Sadd.item.5') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose-us">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('messages.why.title') }}</h2>
            </div>

            <div class="why-grid">
                <div class="why-item">
                    <div class="why-number">01</div>
                    <h3>{{ __('messages.why.reason1') }}</h3>
                </div>
                <div class="why-item">
                    <div class="why-number">02</div>
                    <h3>{{ __('messages.why.reason2') }}</h3>
                </div>
                <div class="why-item">
                    <div class="why-number">03</div>
                    <h3>{{ __('messages.why.reason3') }}</h3>
                </div>
                <div class="why-item">
                    <div class="why-number">04</div>
                    <h3>{{ __('messages.why.reason4') }}</h3>
                </div>
                <div class="why-item">
                    <div class="why-number">05</div>
                    <h3>{{ __('messages.why.reason5') }}</h3>
                </div>
                <div class="why-item">
                    <div class="why-number">06</div>
                    <h3>{{ __('messages.why.reason6') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2>{{ __('messages.about.more.title') }}</h2>
                <p>{{ __('messages.about.more.description') }}</p>

                <a href="{{ route('contact', app()->getLocale()) }}"
                    class="btn btn-primary text-white">{{ __('messages.nav.contact') }}</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons and panels
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanels.forEach(panel => panel.classList.remove('active'));

                    // Add active class to clicked button and corresponding panel
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
    </script>
@endpush
