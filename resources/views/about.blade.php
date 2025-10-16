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

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2>{{ __('messages.about.title') }}</h2>
                    <p>{{ __('messages.about.description') }}</p>

                    <div class="about-features">
                        <div class="about-feature">
                            <div class="feature-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="feature-content">
                                <h3>مقرنا الرسمي</h3>
                                <p>تمتلك الشركة مقراً رسمياً في مدينة العين بموجب عقد إيجار مسجل في مركز أبوظبي العقاري (رقم العقد: 202502506177)</p>
                                <ul>
                                    <li>العنوان: المنطقة الصناعية، شارع السهام ١، العين ٣٠٩١١</li>
                                    <li>نوع المقر: مكتب تجاري (5 Office)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="about-feature">
                            <div class="feature-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="feature-content">
                                <h3>{{ __('messages.why.vision.title') }}</h3>
                                <p>{{ __('messages.why.vision.description') }}</p>
                            </div>
                        </div>

                        <div class="about-feature">
                            <div class="feature-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="feature-content">
                                <h3>{{ __('messages.why.mission.title') }}</h3>
                                <p>{{ __('messages.why.mission.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop" alt="About Us">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h3>20+</h3>
                            <p>{{ __('messages.tech.experience') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="services-overview">
        <div class="container">
            <div class="section-header">
                <h2>خدماتنا المتكاملة</h2>
                <p>نقدم حلولاً شاملة في مجال الأمن والمراقبة</p>
            </div>

            <div class="services-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="security">قسم "رؤية المستقبل" (الأمن)</button>
                    <button class="tab-btn" data-tab="technology">قسم "سدد" (التقنية)</button>
                </div>

                <div class="tab-content">
                    <div class="tab-panel active" id="security">
                        <div class="services-grid">
                            <div class="service-item">
                                <i class="fas fa-video"></i>
                                <h3>تركيب أنظمة المراقبة بالكاميرات</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-bell"></i>
                                <h3>أنظمة الإنذار ضد السرقة</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-door-open"></i>
                                <h3>البوابات الإلكترونية الأمنية</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-key"></i>
                                <h3>أنظمة التحكم في الدخول</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-home"></i>
                                <h3>أنظمة المنزل الذكي</h3>
                            </div>
                        </div>
                    </div>

                    <div class="tab-panel" id="technology">
                        <div class="services-grid">
                            <div class="service-item">
                                <i class="fas fa-brain"></i>
                                <h3>حلول الذكاء الاصطناعي وتحليل البيانات</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-digital-tachograph"></i>
                                <h3>التحول الرقمي وتطوير البرمجيات</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-robot"></i>
                                <h3>المساعد الافتراضي (Chatbot) للخدمات</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-desktop"></i>
                                <h3>منصات المراقبة الذكية المتكاملة</h3>
                            </div>
                            <div class="service-item">
                                <i class="fas fa-user-check"></i>
                                <h3>أنظمة التعرف على الوجوه والمركبات</h3>
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
                <h2>هل تريد معرفة المزيد عنا؟</h2>
                <p>تواصلوا معنا اليوم لمعرفة كيف يمكننا مساعدتكم</p>
                <a href="{{ route('contact') }}" class="btn btn-primary text-white">{{ __('messages.nav.contact') }}</a>
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


