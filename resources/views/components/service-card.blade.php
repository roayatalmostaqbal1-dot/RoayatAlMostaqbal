<!-- Service Card Component -->
@props(['icon', 'title', 'description', 'link' => '#'])

<div class="service-card">
    <div class="service-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <h3>{{ $title }}</h3>
    <p>{{ $description }}</p>
    <a href="{{ $link }}" class="btn btn-outline">{{ __('messages.services.more') }}</a>
</div>


