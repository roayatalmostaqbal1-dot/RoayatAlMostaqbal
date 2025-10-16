@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - ' . __('messages.header.title'))
@section('description', __('messages.projects.title'))

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.nav.projects') }}</h1>
            <p>مشاريعنا الأخيرة في مجال الأمن والمراقبة</p>
        </div>
    </section>

    <!-- Projects Filter -->
    <section class="projects-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">جميع المشاريع</button>
                <button class="filter-btn" data-filter="surveillance">أنظمة المراقبة</button>
                <button class="filter-btn" data-filter="security">الأمن والحماية</button>
                <button class="filter-btn" data-filter="ai">الذكاء الاصطناعي</button>
                <button class="filter-btn" data-filter="smart">المنزل الذكي</button>
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
                        <h3>نظام مراقبة متطور</h3>
                        <p>تركيب نظام مراقبة شامل لمجمع سكني كبير</p>
                        <div class="project-tags">
                            <span class="tag">كاميرات HD</span>
                            <span class="tag">ذكاء اصطناعي</span>
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
                        <h3>مراقبة ذكية للمراكز التجارية</h3>
                        <p>نظام مراقبة متكامل مع تحليل سلوك العملاء</p>
                        <div class="project-tags">
                            <span class="tag">تحليل البيانات</span>
                            <span class="tag">مراقبة ذكية</span>
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
                        <h3>أنظمة التحكم في الدخول</h3>
                        <p>نظام تحكم متقدم لمبنى إداري حكومي</p>
                        <div class="project-tags">
                            <span class="tag">بطاقات ذكية</span>
                            <span class="tag">تحكم متقدم</span>
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
                        <h3>أنظمة الإنذار المتطورة</h3>
                        <p>نظام إنذار شامل لمستودعات صناعية</p>
                        <div class="project-tags">
                            <span class="tag">إنذار ذكي</span>
                            <span class="tag">حماية شاملة</span>
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
                        <h3>حلول الذكاء الاصطناعي</h3>
                        <p>منصة ذكية لتحليل البيانات الأمنية</p>
                        <div class="project-tags">
                            <span class="tag">ذكاء اصطناعي</span>
                            <span class="tag">تحليل البيانات</span>
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
                        <h3>التعرف على الوجوه والمركبات</h3>
                        <p>نظام متقدم للتعرف على الأشخاص والمركبات</p>
                        <div class="project-tags">
                            <span class="tag">تعرف على الوجوه</span>
                            <span class="tag">تحليل المركبات</span>
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
                        <h3>المنزل الذكي المتكامل</h3>
                        <p>تحويل منزل تقليدي إلى منزل ذكي متكامل</p>
                        <div class="project-tags">
                            <span class="tag">منزل ذكي</span>
                            <span class="tag">تحكم ذكي</span>
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
                        <h3>المساعد الافتراضي الذكي</h3>
                        <p>تطوير مساعد افتراضي للخدمات الأمنية</p>
                        <div class="project-tags">
                            <span class="tag">مساعد افتراضي</span>
                            <span class="tag">ذكاء اصطناعي</span>
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
                    <div class="stat-label">مشروع مكتمل</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">عميل راضي</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">سنوات خبرة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">دعم فني</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2>هل تريد رؤية مشروعك هنا؟</h2>
                <p>تواصلوا معنا لبدء مشروعكم القادم</p>
                <a href="{{ route('contact') }}" class="btn btn-primary text-white">{{ __('messages.nav.contact') }}</a>
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


