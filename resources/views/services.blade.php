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
                <h2>قسم "رؤية المستقبل" (الأمن)</h2>
                <p>حلول أمنية متكاملة ومتطورة</p>
            </div>

            <div class="services-grid">
                <x-service-card
                    icon="fas fa-video"
                    title="تركيب أنظمة المراقبة بالكاميرات"
                    description="تركيب أنظمة المراقبة بالكاميرات المتطورة مع تقنيات الذكاء الاصطناعي للرصد الذكي والمراقبة المستمرة"
                />

                <x-service-card
                    icon="fas fa-bell"
                    title="أنظمة الإنذار ضد السرقة"
                    description="أنظمة الإنذار ضد السرقة مع تقنيات متقدمة للكشف المبكر والتنبيه الفوري للحالات الطارئة"
                />

                <x-service-card
                    icon="fas fa-door-open"
                    title="البوابات الإلكترونية الأمنية"
                    description="البوابات الإلكترونية الأمنية مع أنظمة التحكم في الدخول المتطورة والمراقبة الذكية"
                />

                <x-service-card
                    icon="fas fa-key"
                    title="أنظمة التحكم في الدخول"
                    description="أنظمة التحكم في الدخول المتقدمة مع تقنيات التعرف البيومتري والبطاقات الذكية"
                />

                <x-service-card
                    icon="fas fa-home"
                    title="أنظمة المنزل الذكي"
                    description="أنظمة المنزل الذكي المتكاملة مع حلول الذكاء الاصطناعي والتحكم عن بُعد"
                />
            </div>
        </div>
    </section>

    <!-- Technology Services -->
    <section class="technology-services">
        <div class="container">
            <div class="section-header">
                <h2>قسم "سدد" (التقنية)</h2>
                <p>حلول تقنية متطورة وذكية</p>
            </div>

            <div class="services-grid">
                <x-service-card
                    icon="fas fa-brain"
                    title="حلول الذكاء الاصطناعي وتحليل البيانات"
                    description="حلول الذكاء الاصطناعي وتحليل البيانات المتقدمة لتحسين الأداء واتخاذ القرارات الذكية"
                />

                <x-service-card
                    icon="fas fa-digital-tachograph"
                    title="التحول الرقمي وتطوير البرمجيات"
                    description="التحول الرقمي وتطوير البرمجيات المخصصة لتحسين العمليات وزيادة الكفاءة"
                />

                <x-service-card
                    icon="fas fa-robot"
                    title="المساعد الافتراضي (Chatbot) للخدمات"
                    description="المساعد الافتراضي (Chatbot) للخدمات مع تقنيات الذكاء الاصطناعي المتطورة"
                />

                <x-service-card
                    icon="fas fa-desktop"
                    title="منصات المراقبة الذكية المتكاملة"
                    description="منصات المراقبة الذكية المتكاملة مع واجهات سهلة الاستخدام ومراقبة شاملة"
                />

                <x-service-card
                    icon="fas fa-user-check"
                    title="أنظمة التعرف على الوجوه والمركبات"
                    description="أنظمة التعرف على الوجوه والمركبات مع تقنيات الذكاء الاصطناعي المتقدمة"
                />
            </div>
        </div>
    </section>

    <!-- Service Process -->
    <section class="service-process">
        <div class="container">
            <div class="section-header">
                <h2>كيف نعمل</h2>
                <p>عملية منظمة ومدروسة لضمان أفضل النتائج</p>
            </div>

            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>الاستشارة والتقييم</h3>
                        <p>نقوم بفهم احتياجاتكم وتقييم الموقع الحالي</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>التصميم والتخطيط</h3>
                        <p>نصمم حلولاً مخصصة تناسب احتياجاتكم</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>التنفيذ والتركيب</h3>
                        <p>نقوم بتركيب الأنظمة بأعلى معايير الجودة</p>
                    </div>
                </div>

                <div class="process-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>الاختبار والتسليم</h3>
                        <p>نختبر الأنظمة ونسلمها مع التدريب والدعم</p>
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
                    <h3>أمان عالي</h3>
                    <p>نستخدم أحدث التقنيات لضمان أعلى مستويات الأمان</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>سهولة الاستخدام</h3>
                    <p>واجهات سهلة ومفهومة لجميع المستخدمين</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>دعم فني 24/7</h3>
                    <p>دعم فني متواصل على مدار الساعة</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>صيانة دورية</h3>
                    <p>صيانة دورية لضمان استمرارية العمل</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2>هل تريد معرفة المزيد عن خدماتنا؟</h2>
                <p>تواصلوا معنا اليوم للحصول على استشارة مجانية</p>
                <a href="{{ route('contact') }}" class="btn btn-primary text-white">{{ __('messages.nav.contact') }}</a>
            </div>
        </div>
    </section>
@endsection


