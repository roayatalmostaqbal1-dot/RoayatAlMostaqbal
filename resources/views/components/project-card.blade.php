<!-- Project Card Component -->
@props(['image', 'title', 'description' => '', 'link' => '#'])

<div class="project-card">
    <div class="project-image">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
    </div>
    <h3>{{ $title }}</h3>
    @if($description)
        <p>{{ $description }}</p>
    @endif
    <a href="{{ $link }}" class="btn btn-outline">{{ __('messages.common.more') }}</a>
</div>


