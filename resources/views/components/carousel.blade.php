@props(['options' => [], 'arrows' => false, 'pagination' => false])

<div x-data="carousel(@js($options))" class="relative">
    <div class="swiper" x-ref="swiper">
        <div class="swiper-wrapper">
            {{ $slot }}
        </div>
    </div>

    @if ($pagination)
        <div class="swiper-pagination mt-3 flex justify-center gap-1.5" x-ref="pagination"></div>
    @endif

    @if ($arrows)
        <button type="button" x-ref="prev" aria-label="{{ translate('Previous') }}"
            class="absolute top-1/2 -translate-y-1/2 -start-3 sm:-start-4 z-10 flex size-7 sm:size-8 items-center justify-center rounded-full border border-neutral-200 bg-white text-neutral-600 shadow-sm transition hover:border-[#2346d1] hover:bg-[#2346d1] hover:text-white">
            <i class="fa-solid fa-chevron-right text-[10px] sm:text-xs"></i>
        </button>
        <button type="button" x-ref="next" aria-label="{{ translate('Next') }}"
            class="absolute top-1/2 -translate-y-1/2 -end-3 sm:-end-4 z-10 flex size-7 sm:size-8 items-center justify-center rounded-full border border-neutral-200 bg-white text-neutral-600 shadow-sm transition hover:border-[#2346d1] hover:bg-[#2346d1] hover:text-white">
            <i class="fa-solid fa-chevron-left text-[10px] sm:text-xs"></i>
        </button>
    @endif
</div>
