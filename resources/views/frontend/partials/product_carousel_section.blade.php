{{--
    Reusable "product category" section matching the reference homepage
    design: right-aligned title, a "view all" pill button, a card carousel
    (built on <x-carousel> + <x-product-card>) with pagination dots.
    Replaces the hand-rolled, duplicated product-card markup that used to
    live separately in each home section (best-selling, deal-of-week,
    newest, etc.) — each with its own slightly-different, sometimes-buggy
    price/discount math.

    Usage:
        @include('frontend.partials.product_carousel_section', [
            'title' => translate('best_selling'),
            'products' => $best_selling_products,
            'viewAllUrl' => route('search'), // optional
        ])
--}}
@if (isset($products) && count($products) > 0)
    <section class="px-4 md:px-6 py-6">
        <div class="flex items-center justify-between mb-4">
            @if (!empty($viewAllUrl))
                <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-1.5 rounded-full border border-primary px-4 py-1.5 text-sm font-semibold text-primary hover:bg-primary hover:text-white transition">
                    {{ translate('View All Products') }}
                    <i class="fa-solid fa-chevron-left rtl:-scale-x-100 text-xs"></i>
                </a>
            @else
                <span></span>
            @endif
            <h2 class="text-xl font-extrabold text-neutral-900">{{ $title }}</h2>
        </div>

        <x-carousel :options="['slidesPerView' => 2, 'spaceBetween' => 16, 'breakpoints' => ['640' => ['slidesPerView' => 3], '1024' => ['slidesPerView' => 4]]]">
            @foreach ($products as $product)
                <div class="swiper-slide h-auto">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </x-carousel>
    </section>
@endif
