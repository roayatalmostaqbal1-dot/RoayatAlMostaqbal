<!-- Service Card Component -->
@props(['icon', 'title', 'description', 'link' => '#'])

<div class="bg-[#162936] p-8 rounded-[20px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300 text-center">
    <div class="text-5xl text-[#27e9b5] mb-4">
        <i class="{{ $icon }}"></i>
    </div>
    <h3 class="text-xl font-bold text-white mb-4">{{ $title }}</h3>
    <p class="text-gray-300 mb-6">{{ $description }}</p>
    {{-- <a href="{{ $link }}" class="bg-transparent text-[#27e9b5] border-2 border-[#27e9b5] px-5 py-2.5 text-sm hover:bg-[#27e9b5] hover:text-[#051824] inline-block rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer">{{ __('messages.services.more') }}</a> --}}
</div>


