<!-- Footer Component -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">

                <div class="footer-logo">
                    <h3>{{ __('messages.header.title') }}</h3>
                    <p>{{ __('messages.header.subtitle') }}</p>

                </div>

                <p class="footer-description">{{ __('messages.footer.description') }}</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>{{ __('messages.footer.other_pages') }}</h4>
                <ul>
                    <li><a href="{{ route('home' , app()->getLocale()) }}">{{ __('messages.nav.home') }}</a></li>
                    <li><a href="{{ route('about', app()->getLocale()) }}">{{ __('messages.nav.about') }}</a></li>
                    <li><a href="{{ route('services', app()->getLocale()) }}">{{ __('messages.nav.services') }}</a></li>
                    <li><a href="{{ route('contact', app()->getLocale()) }}">{{ __('messages.nav.contact') }}</a></li>
                 </ul>
            </div>
            <div class="footer-section">
                <h4>{{ __('messages.footer.quick_links') }}</h4>
                <ul>
                    <li><a href="#faq">{{ __('messages.footer.faq') }}</a></li>
                    <li><a href="#news">{{ __('messages.footer.news') }}</a></li>
                    <li><a href="#coming-soon">{{ __('messages.footer.coming_soon') }}</a></li>
                    <li><a href="#404">404</a></li>
                    <li><a href="#credits">{{ __('messages.footer.credits') }}</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>{{ __('messages.footer.newsletter') }}</h4>
                <p>{{ __('messages.footer.newsletter_subtitle') }}</p>
                <form class="footer-newsletter" id="footer-newsletter-form">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('messages.newsletter.placeholder') }}" required>
                    <button type="submit" class="btn btn-primary text-white">{{ __('messages.common.subscribe') }}</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-bottom-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ __('messages.header.address') }}</span>
            </div>
            <div class="footer-bottom-item">
                <i class="fas fa-envelope"></i>
                <span>{{ __('messages.header.email') }}</span>
            </div>
            <div class="footer-bottom-item">
                <i class="fas fa-phone"></i>
                <span>{{ __('messages.header.phone') }}</span>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ __('messages.header.title') }}. {{ __('messages.footer.copyright') }}</p>
            <p>{{ __('messages.footer.email_label') }} {{ __('messages.header.email') }}</p>
        </div>
    </div>
</footer>


