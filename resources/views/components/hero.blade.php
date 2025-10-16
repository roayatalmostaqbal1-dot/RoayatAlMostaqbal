<!-- Hero Section Component -->
<section class="hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">{{ __('messages.hero.title') }}</h1>
            <p class="hero-subtitle">{{ __('messages.hero.subtitle') }}</p>
            <button class="btn btn-primary text-white" onclick="scrollToSection('about')">{{ __('messages.hero.button') }}</button>
        </div>

        <div class="hero-features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>{{ __('messages.hero.feature.research') }}</h3>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>{{ __('messages.hero.feature.strategy') }}</h3>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>{{ __('messages.hero.feature.resources') }}</h3>
            </div>
        </div>
    </div>
</section>


