// Main JavaScript file for Roayat Al Mostaqbal website
document.addEventListener('DOMContentLoaded', function () {

    // --- Header scroll effect ---
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 100) {
                header.style.background = 'rgba(5, 24, 36, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'linear-gradient(135deg, #051824 0%, #162936 100%)';
                header.style.backdropFilter = 'none';
            }
        });
    }

    // --- Scroll animations ---
    // initScrollEffects();

    // بقية الوظائف
    initLazyLoading();
    initNavigation();
    initAnimations();
    initNewsletter();
    initMobileMenu();
    initSmoothScrolling();
    initLanguageSwitcher();
    initFormHandlers();
    initProjectFilters();
    initTabs();
    initFAQ();
});


unction initNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section[id]');

    function updateActiveNav() {
        let current = '';
        const scrollPos = window.scrollY + 100;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');

            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                current = sectionId;
            }
        });

        navLinks.forEach(link => {
            link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
        });
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            scrollToSection(targetId); // استخدم الدالة العامة هنا
        });
    });

    window.addEventListener('scroll', updateActiveNav);
    updateActiveNav();
}
// Initialize animations
function initAnimations() {
    // Counter animation for experience badge
    const badgeNumber = document.querySelector('.badge-number');
    if (badgeNumber) {
        const targetNumber = parseInt(badgeNumber.textContent);
        let currentNumber = 0;
        const increment = targetNumber / 50;

        const counter = setInterval(() => {
            currentNumber += increment;
            if (currentNumber >= targetNumber) {
                currentNumber = targetNumber;
                clearInterval(counter);
            }
            badgeNumber.textContent = Math.floor(currentNumber) + '+';
        }, 30);
    }

    // Parallax effect for hero section
    window.addEventListener('scroll', function () {
        const scrolled = window.pageYOffset;
        const heroBackground = document.querySelector('.hero-background');
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
}

// Newsletter functionality
function initNewsletter() {
    const newsletterForms = document.querySelectorAll('.newsletter-form, .footer-newsletter');

    newsletterForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;

            if (validateEmail(email)) {
                // Simulate newsletter subscription
                showNotification('تم الاشتراك بنجاح! شكراً لاهتمامكم.', 'success');
                this.reset();
            } else {
                showNotification('يرجى إدخال بريد إلكتروني صحيح.', 'error');
            }
        });
    });
}



// Smooth scrolling for all internal links
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Utility functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27e9b5' : type === 'error' ? '#ff4444' : '#3b5265'};
        color: ${type === 'success' ? '#051824' : '#ffffff'};
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.3s ease-out;
    `;

    // Add to document
    document.body.appendChild(notification);

    // Add close functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', function () {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    });

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .notification-close {
        background: none;
        border: none;
        color: inherit;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-close:hover {
        opacity: 0.7;
    }
`;
document.head.appendChild(style);

// Header scroll effect
window.addEventListener('scroll', function () {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(5, 24, 36, 0.95)';
        header.style.backdropFilter = 'blur(10px)';
    } else {
        header.style.background = 'linear-gradient(135deg, #051824 0%, #162936 100%)';
        header.style.backdropFilter = 'none';
    }
});

// Loading animation
window.addEventListener('load', function () {
    document.body.classList.add('loaded');

    // Add loading animation styles
    const loadingStyle = document.createElement('style');
    loadingStyle.textContent = `
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        body.loaded {
            opacity: 1;
        }
    `;
    document.head.appendChild(loadingStyle);
});

// Service worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js')
            .then(function (registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function (err) {
                console.log('ServiceWorker registration failed');
            });
    });
}

// Performance optimization: Lazy loading for images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
initLazyLoading();

// Contact form validation (if contact form exists)
function initContactForm() {
    const contactForm = document.querySelector('#contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const message = formData.get('message');

            if (!name || !email || !message) {
                showNotification('يرجى ملء جميع الحقول المطلوبة.', 'error');
                return;
            }

            if (!validateEmail(email)) {
                showNotification('يرجى إدخال بريد إلكتروني صحيح.', 'error');
                return;
            }

            // Simulate form submission
            showNotification('تم إرسال رسالتكم بنجاح! سنتواصل معكم قريباً.', 'success');
            this.reset();
        });
    }
}

// Initialize contact form
initContactForm();

// Accessibility improvements
function initAccessibility() {
    // Add keyboard navigation for mobile menu
    const navToggle = document.querySelector('.nav-toggle');
    if (navToggle) {
        navToggle.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    }

    // Add focus indicators
    const focusableElements = document.querySelectorAll('a, button, input, textarea, select');
    focusableElements.forEach(element => {
        element.addEventListener('focus', function () {
            this.style.outline = '2px solid #27e9b5';
            this.style.outlineOffset = '2px';
        });

        element.addEventListener('blur', function () {
            this.style.outline = 'none';
        });
    });
}

// Initialize accessibility features
initAccessibility();

// Language Switcher
function initLanguageSwitcher() {
    const langLinks = document.querySelectorAll('.lang-link');

    langLinks.forEach(link => {
        link.addEventListener('click', async function () {
            const locale = this.dataset.locale;

            try {
                const response = await fetch('/language/switch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ locale })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('preferred-language', data.locale);

                    // تحديث حالة الأزرار
                    langLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    // إعادة تحميل الصفحة لتطبيق اللغة
                    location.reload();
                }
            } catch (error) {
                console.error('Language switch failed:', error);
            }
        });
    });
}


// Form Handlers
function initFormHandlers() {
    // Contact form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            handleContactForm(this);
        });
    }

    // Newsletter forms
    const newsletterForms = document.querySelectorAll('.newsletter-form, .footer-newsletter');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            handleNewsletterForm(this);
        });
    });
}
function initScrollEffects() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');

                // Staggered animation للعناصر المحددة
                const staggerClasses = ['service-card', 'project-card', 'why-card'];
                if (staggerClasses.some(cls => entry.target.classList.contains(cls))) {
                    const siblings = Array.from(entry.target.parentNode.children);
                    const index = siblings.indexOf(entry.target);
                    entry.target.style.transitionDelay = `${index * 0.1}s`;
                }

                observer.unobserve(entry.target); // توقف المراقبة بعد الظهور
            }
        });
    }, observerOptions);

    const animateElements = document.querySelectorAll(
        '.service-card, .project-card, .why-card, .tech-feature, .feature-card, .stat-item, .why-item'
    );

    animateElements.forEach(el => observer.observe(el));
}

function handleContactForm(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    // Basic validation
    if (!data.name || !data.email || !data.message) {
        showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
        return;
    }

    if (!validateEmail(data.email)) {
        showNotification('يرجى إدخال بريد إلكتروني صحيح', 'error');
        return;
    }

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'جاري الإرسال...';
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        showNotification('تم إرسال رسالتكم بنجاح! سنتواصل معكم قريباً', 'success');
        form.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
}

function handleNewsletterForm(form) {
    const email = form.querySelector('input[type="email"]').value;

    if (!email) {
        showNotification('يرجى إدخال بريد إلكتروني', 'error');
        return;
    }

    if (!validateEmail(email)) {
        showNotification('يرجى إدخال بريد إلكتروني صحيح', 'error');
        return;
    }

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'جاري الاشتراك...';
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        showNotification('تم الاشتراك بنجاح! شكراً لاهتمامكم', 'success');
        form.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 1500);
}

// Project Filters
function initProjectFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');

            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filter projects with animation
            projectCards.forEach((card, index) => {
                const category = card.getAttribute('data-category');
                const shouldShow = filter === 'all' || category === filter;

                if (shouldShow) {
                    card.style.display = 'block';
                    card.style.animation = `fadeInUp 0.5s ease ${index * 0.1}s both`;
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

// Tabs functionality
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Update active button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Update active panel
            tabPanels.forEach(panel => {
                panel.classList.remove('active');
                if (panel.id === targetTab) {
                    panel.classList.add('active');
                }
            });
        });
    });
}

// FAQ functionality
function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');

        question.addEventListener('click', function () {
            const answer = item.querySelector('.faq-answer');
            const icon = this.querySelector('i');

            // Close other FAQ items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.faq-question i');
                    otherAnswer.style.maxHeight = null;
                    otherIcon.style.transform = 'rotate(0deg)';
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
}

// Smooth scroll to section
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        const headerHeight = document.querySelector('.header').offsetHeight;
        const targetPosition = section.offsetTop - headerHeight;

        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }
}


// Mobile menu functionality
function initMobileMenu() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });

        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });
    }
}


window.scrollToSection = scrollToSection;
