<!-- Project Card Component -->
@props(['image', 'title', 'description' => '', 'link' => '#'])

<div class="bg-[#162936] rounded-[20px] overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] transition-all duration-300">
    <div class="overflow-hidden h-48">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" width="400" height="300" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
    </div>
    <div class="p-6">
        <h3 class="text-xl font-bold text-white mb-2">{{ $title }}</h3>
        @if($description)
            <p class="text-gray-300 mb-4">{{ $description }}</p>
        @endif
        {{-- <a href="{{ $link }}" class="bg-transparent text-[#27e9b5] border-2 border-[#27e9b5] px-5 py-2.5 text-sm hover:bg-[#27e9b5] hover:text-[#051824] inline-block rounded-[30px] no-underline font-bold text-center transition-all duration-300 border-none cursor-pointer">{{ __('messages.common.more') }}</a> --}}
    </div>
</div>


