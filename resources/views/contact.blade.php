@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - ' . __('messages.header.title'))
@section('description', 'تواصلوا معنا للحصول على استشارة مجانية')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.nav.contact') }}</h1>
            <p>تواصلوا معنا للحصول على استشارة مجانية</p>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-info">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-details">
                    <h2>معلومات التواصل</h2>
                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <h3>العنوان</h3>
                                <p>{{ __('messages.header.address') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-content">
                                <h3>الهاتف</h3>
                                <p>{{ __('messages.header.phone') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-content">
                                <h3>البريد الإلكتروني</h3>
                                <p>{{ __('messages.header.email') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-content">
                                <h3>ساعات العمل</h3>
                                <p>الأحد - الخميس: 8:00 ص - 6:00 م<br>الجمعة - السبت: مغلق</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-section">
                    <h2>أرسلوا لنا رسالة</h2>
                    <form class="contact-form" id="contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">الاسم *</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني *</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <input type="tel" id="phone" name="phone">
                        </div>

                        <div class="form-group">
                            <label for="company">اسم الشركة</label>
                            <input type="text" id="company" name="company">
                        </div>

                        <div class="form-group">
                            <label for="service">نوع الخدمة المطلوبة</label>
                            <select id="service" name="service">
                                <option value="">اختر الخدمة</option>
                                <option value="surveillance">أنظمة المراقبة</option>
                                <option value="security">أنظمة الأمن</option>
                                <option value="ai">الذكاء الاصطناعي</option>
                                <option value="smart">المنزل الذكي</option>
                                <option value="consultation">استشارة</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">الرسالة *</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary text-white">إرسال الرسالة</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2>موقعنا على الخريطة</h2>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.123456789!2d55.123456789!3d24.123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDA3JzI0LjQiTiA1NcKwMDcnMjQuNCJF!5e0!3m2!1sen!2sae!4v1234567890123!5m2!1sen!2sae"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>الأسئلة الشائعة</h2>
                <p>إجابات على أكثر الأسئلة شيوعاً</p>
            </div>

            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>كم تستغرق عملية تركيب نظام المراقبة؟</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>تختلف مدة التركيب حسب حجم المشروع وتعقيده. عادة ما تستغرق المشاريع الصغيرة من يوم إلى 3 أيام، بينما المشاريع الكبيرة قد تستغرق من أسبوع إلى عدة أسابيع.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>هل تقدمون ضمان على الأنظمة؟</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>نعم، نقدم ضمان شامل على جميع الأنظمة لمدة سنتين، بالإضافة إلى دعم فني مجاني خلال فترة الضمان.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>هل يمكن تحديث الأنظمة لاحقاً؟</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>نعم، جميع أنظمتنا قابلة للتحديث والتطوير. نقدم خدمات التحديث والصيانة الدورية لضمان استمرارية العمل.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>ما هي تكلفة الاستشارة؟</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>نقدم استشارة مجانية لجميع العملاء. نقوم بتقييم احتياجاتكم وتقديم اقتراحات مخصصة دون أي رسوم.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Contact form handling
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);

            // Basic validation
            const name = formData.get('name');
            const email = formData.get('email');
            const message = formData.get('message');

            if (!name || !email || !message) {
                showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
                return;
            }

            if (!validateEmail(email)) {
                showNotification('يرجى إدخال بريد إلكتروني صحيح', 'error');
                return;
            }

            // Simulate form submission
            showNotification('تم إرسال رسالتكم بنجاح! سنتواصل معكم قريباً', 'success');
            this.reset();
        });

        // FAQ functionality
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');

            question.addEventListener('click', function() {
                const answer = item.querySelector('.faq-answer');
                const icon = this.querySelector('i');

                // Close other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-answer').style.maxHeight = null;
                        otherItem.querySelector('.faq-question i').style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle current item
                item.classList.toggle('active');

                if (item.classList.contains('active')) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    answer.style.maxHeight = null;
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
</script>
@endpush


