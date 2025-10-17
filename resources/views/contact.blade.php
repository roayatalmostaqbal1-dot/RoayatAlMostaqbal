@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - ' . __('messages.header.title'))
@section('description', 'تواصلوا معنا للحصول على استشارة مجانية')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>{{ __('messages.contact.page_header.title') }}</h1>
            <p>{{ __('messages.contact.page_header.description') }}</p>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-info">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-details">
                    <h2>{{ __('messages.contact.info.title') }}</h2>
                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <h3>{{ __('messages.contact.info.address.title') }}</h3>
                                <p>{{ __('messages.contact.info.address.description') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-content">
                                <h3>{{ __('messages.contact.info.phone.title') }}</h3>
                                <p>{{ __('messages.contact.info.phone.description') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-content">
                                <h3>{{ __('messages.contact.info.email.title') }}</h3>
                                <p>{{ __('messages.contact.info.email.description') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-content">
                                <h3>{{ __('messages.contact.info.working_hours.title') }}</h3>
                                <p>{{ __('messages.contact.info.working_hours.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-section">
                    <h2>{{ __('messages.contact.form.title') }}</h2>
                    <form class="contact-form" id="contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('messages.contact.form.name') }}</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('messages.contact.form.email') }}</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ __('messages.contact.form.phone') }}</label>
                            <input type="tel" id="phone" name="phone">
                        </div>

                        <div class="form-group">
                            <label for="company">{{ __('messages.contact.form.company') }}</label>
                            <input type="text" id="company" name="company">
                        </div>

                        <div class="form-group">
                            <label for="service">{{ __('messages.contact.form.service') }}</label>
                            <select id="service" name="service">
                                <option value="">{{ __('messages.contact.form.service.placeholder') }}</option>
                                <option value="surveillance">{{ __('messages.contact.form.service.option.surveillance') }}</option>
                                <option value="security">{{ __('messages.contact.form.service.option.security') }}</option>
                                <option value="ai">{{ __('messages.contact.form.service.option.ai') }}</option>
                                <option value="smart">{{ __('messages.contact.form.service.option.smart') }}</option>
                                <option value="consultation">{{ __('messages.contact.form.service.option.consultation') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">{{ __('messages.contact.form.message') }}</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary text-white">{{ __('messages.contact.form.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2>{{ __('messages.contact.map.title') }}</h2>
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
                <h2>{{ __('messages.contact.faq.title') }}</h2>
                <p>{{ __('messages.contact.faq.description') }}</p>
            </div>

            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ __('messages.contact.faq.item1.question') }}</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ __('messages.contact.faq.item1.answer') }}</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ __('messages.contact.faq.item2.question') }}</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ __('messages.contact.faq.item2.answer') }}</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ __('messages.contact.faq.item3.question') }}</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ __('messages.contact.faq.item3.answer') }}</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ __('messages.contact.faq.item4.question') }}</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ __('messages.contact.faq.item4.answer') }}</p>
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


