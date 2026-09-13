@extends('frontend.layouts.app')

@section('content')
    @php
        $locale = app()->getLocale();
        $localeMap = ['ar' => 'sa', 'zh' => 'cn'];
        $prefixLocale = $localeMap[$locale] ?? $locale;
        $isAr = in_array($locale, ['sa', 'ar', 'eg']);
        $isCn = in_array($locale, ['cn', 'zh']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;

        $localizedSliders = $sliders->filter(function ($slider) use ($prefixLocale) {
            return str_starts_with($slider->file_original_name ?? '', $prefixLocale . '-');
        });
        $localizedSliders2 = $sliders2->filter(function ($slider) use ($prefixLocale) {
            return str_starts_with($slider->file_original_name ?? '', $prefixLocale . '-');
        });

        $summerLinks = [
            route('products.category', 'ice-cream-makers-p8yjs'),
            route('products.category', 'beverage-equipment-5zoca'),
            route('products.category', 'ice-cream-display-refrigerators-g7b8h'),
        ];

        $firstFourBestSelling = $best_selling_products->slice(0, 4);
        $secondFourBestSelling = $best_selling_products->slice(4, 4);
        if ($secondFourBestSelling->count() < 4 && $new_products->isNotEmpty()) {
            // Top the row up with new arrivals so the grid never shows gaps.
            $shownIds = $best_selling_products->pluck('id');
            $secondFourBestSelling = $secondFourBestSelling
                ->concat($new_products->whereNotIn('id', $shownIds))
                ->take(4);
        }

        $placeholder = static_asset('assets/img/placeholder.jpg');
        $placeholderRect = static_asset('assets/img/placeholder-rect.jpg');

        // Each promo artwork exists per language in public/assets/front_img.
        // 'wide' marks the 2.1:1 stainless artwork whose headline sits on its left edge.
        $art = [
            'refrigeration' => ['sa' => 'sa-summer-product2.jpeg', 'en' => 'en-summer-product2.png', 'cn' => 'cn-summer-product2.png'],
            'icecream' => ['sa' => 'sa-summer-product.jpeg', 'en' => 'en-summer-product.png', 'cn' => 'cn-summer-product.png'],
            'slush' => ['sa' => 'sa-summer-product1.jpg', 'en' => 'en-summer-product1.png', 'cn' => 'cn-summer-product1.png'],
            'stainless' => ['sa' => 'stanlsbanner.jpeg', 'en' => 'stainless-en.jpg', 'cn' => 'stainless-cn.jpg', 'wide' => true],
            'bestseller' => ['sa' => 'best-seller-sa.png', 'en' => 'best-seller-en.png', 'cn' => 'best-seller-cn.png'],
            'bestseller2' => ['sa' => 'bestseller-sa.jpg', 'en' => 'bestseller-en.jpg', 'cn' => 'bestseller-cn.jpg'],
        ];
        $artLocale = $isAr ? 'sa' : ($isCn ? 'cn' : 'en');
        $img = fn ($key) => static_asset('assets/front_img/' . ($art[$key][$artLocale] ?? $art[$key]['sa']));
        $wide = fn ($key) => !empty($art[$key]['wide']) ? 'is-wide' : '';

        $solutionBanners = [
            ['title' => $t('ثلاجات عرض وتخزين', 'Display and storage refrigerators'), 'keyword' => 'ثلاجات', 'image' => 'refrigeration'],
            ['title' => $t('مكائن آيس كريم', 'Ice cream machines'), 'keyword' => 'آيس كريم', 'image' => 'icecream'],
            ['title' => $t('مطابخ ستانلس ستيل', 'Stainless steel kitchens'), 'keyword' => 'ستانلس', 'image' => 'stainless'],
            ['title' => $t('مكائن السلاش', 'Slush machines'), 'keyword' => 'سلاش', 'image' => 'slush'],
        ];

        $bestSellerPromos = [
            ['title' => $t('أسعار منافسة لثلاجات العرض', 'Competitive prices on display refrigerators'), 'image' => 'bestseller'],
            ['title' => $t('معدات تلبي احتياج مطبخك', 'Equipment built for your kitchen'), 'image' => 'bestseller2'],
        ];

        $projects = [
            ['title' => $t('معدات مطابخ مركزية', 'Central kitchen equipment'), 'keyword' => 'مطابخ مركزية', 'image' => 'stainless'],
            ['title' => $t('تجهيز سوبرماركت', 'Supermarket fit-out'), 'keyword' => 'سوبرماركت', 'image' => 'refrigeration'],
            ['title' => $t('تجهيز محلات الحلويات', 'Dessert shop fit-out'), 'keyword' => 'سلاش', 'image' => 'slush'],
            ['title' => $t('معدات كاترينج', 'Catering equipment'), 'keyword' => 'كاترينج', 'image' => 'icecream'],
        ];

        $offers = [
            ['title' => $t('خلاطات حلويات', 'Pastry mixers'), 'deal' => $t('خصم حتى 20%', 'Up to 20% off'), 'keyword' => 'خلاطات', 'image' => 'icecream'],
            ['title' => $t('قلايات تجارية', 'Commercial fryers'), 'deal' => $t('خصم حتى 20%', 'Up to 20% off'), 'keyword' => 'قلايات', 'image' => 'slush'],
            ['title' => $t('أفران هوائية', 'Convection ovens'), 'deal' => $t('خصم حتى 15%', 'Up to 15% off'), 'keyword' => 'أفران', 'image' => 'refrigeration'],
            ['title' => $t('ثلاجة باك بار', 'Back bar refrigerators'), 'deal' => $t('خصم حتى 15%', 'Up to 15% off'), 'keyword' => 'باك بار', 'image' => 'stainless'],
            ['title' => $t('فريزرات', 'Freezers'), 'deal' => $t('خصم حتى 15%', 'Up to 15% off'), 'keyword' => 'فريزر', 'image' => 'icecream'],
        ];

        $shopNow = $t('تسوق الآن', 'Shop now');
    @endphp

    <div class="kn-home">

        {{-- 1. Hero slider --}}
        <section class="kn-wrap kn-hero" aria-label="{{ $t('العروض الرئيسية', 'Featured promotions') }}">
            <x-carousel :pagination="true" :arrows="false" :options="[
                // rewind instead of loop: Swiper's loop mode re-orders cloned slides and
                // mis-translates them in RTL, leaving the active banner shifted off-screen.
                'rewind' => true,
                'spaceBetween' => 0,
                'autoplay' => ['delay' => 5000, 'disableOnInteraction' => false],
                'speed' => 600,
            ]">
                @if ($localizedSliders->count() > 0)
                    @foreach ($localizedSliders as $key => $slider)
                        <div class="swiper-slide">
                            <a href="{{ $sliderLinks[$key] ?? '#' }}" class="d-block">
                                <img src="{{ static_asset($slider->file_name) }}" class="kn-hero-img"
                                    alt="{{ get_setting('website_name') }}"
                                    @if (!$loop->first) loading="lazy" @endif
                                    onerror="this.onerror=null;this.src='{{ $img('icecream') }}';">
                            </a>
                        </div>
                    @endforeach
                @else
                    @foreach (['icecream', 'refrigeration'] as $fallbackSlide)
                        <div class="swiper-slide">
                            <a href="{{ route('search') }}" class="d-block">
                                <img src="{{ $img($fallbackSlide) }}" class="kn-hero-img"
                                    alt="{{ get_setting('website_name') }}">
                            </a>
                        </div>
                    @endforeach
                @endif
            </x-carousel>
        </section>

        {{-- 2. Categories --}}
        @if ($main_categories->count() > 0)
            <section class="kn-wrap kn-section" aria-labelledby="kn-cats-title">
                <div class="kn-section-head">
                    <h2 id="kn-cats-title" class="kn-section-title">
                        {{ $t('تسوق معدات مطاعم ومقاهي بأفضل الأسعار', 'Shop restaurant and café equipment at the best prices') }}
                    </h2>
                    <a href="{{ route('categories.all') }}" class="kn-link">{{ $t('كل الأقسام', 'All categories') }}</a>
                </div>

                <x-carousel :arrows="false" :options="[
                    'slidesPerView' => 3.2,
                    'spaceBetween' => 10,
                    'grabCursor' => true,
                    'breakpoints' => [
                        '576' => ['slidesPerView' => 4.3, 'spaceBetween' => 12],
                        '768' => ['slidesPerView' => 5.5, 'spaceBetween' => 16],
                        '992' => ['slidesPerView' => 7, 'spaceBetween' => 16],
                        '1200' => ['slidesPerView' => 8, 'spaceBetween' => 18],
                    ],
                ]">
                    @foreach ($main_categories as $category)
                        @php
                            // The category banner is the product photo artwork made for
                            // this category; product thumbnails are only a fallback.
                            $catImg = null;
                            $isArtwork = false;
                            if ($category->banner) {
                                $catImg = uploaded_asset($category->banner);
                                $isArtwork = true;
                            } elseif ($category->cover_image) {
                                $catImg = uploaded_asset($category->cover_image);
                                $isArtwork = true;
                            } else {
                                $firstProd = $category->products()->whereNotNull('thumbnail_img')->first();
                                if ($firstProd) {
                                    $catImg = uploaded_asset($firstProd->thumbnail_img);
                                } elseif ($category->icon) {
                                    $catImg = uploaded_asset($category->icon);
                                }
                            }

                            $nameLower = mb_strtolower($category->name);
                            $defaultIcon = 'fa-solid fa-kitchen-set';
                            if (str_contains($nameLower, 'تبريد') || str_contains($nameLower, 'ثلج') || str_contains($nameLower, 'ثلاج') || str_contains($nameLower, 'مجمد') || str_contains($nameLower, 'refrig') || str_contains($nameLower, 'ice')) {
                                $defaultIcon = 'fa-solid fa-snowflake';
                            } elseif (str_contains($nameLower, 'طهي') || str_contains($nameLower, 'أفران') || str_contains($nameLower, 'شوا') || str_contains($nameLower, 'cook') || str_contains($nameLower, 'oven') || str_contains($nameLower, 'grill')) {
                                $defaultIcon = 'fa-solid fa-fire-burner';
                            } elseif (str_contains($nameLower, 'مشروب') || str_contains($nameLower, 'قهوة') || str_contains($nameLower, 'عصير') || str_contains($nameLower, 'coffee') || str_contains($nameLower, 'beverage')) {
                                $defaultIcon = 'fa-solid fa-mug-hot';
                            }
                        @endphp

                        <div class="swiper-slide">
                            <a href="{{ route('products.category', $category->slug) }}" class="kn-cat">
                                <span class="kn-plinth {{ $isArtwork ? 'is-artwork' : '' }}">
                                    @if ($catImg)
                                        <img src="{{ $catImg }}" alt="" loading="lazy"
                                            onerror="this.replaceWith(Object.assign(document.createElement('i'),{className:'{{ $defaultIcon }}'}));">
                                    @else
                                        <i class="{{ $defaultIcon }}" aria-hidden="true"></i>
                                    @endif
                                </span>
                                <span class="kn-cat-name">{{ $category->getTranslation('name') }}</span>
                            </a>
                        </div>
                    @endforeach
                </x-carousel>
            </section>
        @endif

        {{-- 3. Kitchen solutions: the uploaded banners, shown as they are --}}
        <section class="kn-wrap kn-section" aria-labelledby="kn-solutions-title">
            <div class="kn-section-head">
                <h2 id="kn-solutions-title" class="kn-section-title">
                    {{ $t('حلول تجهيز المطاعم والمطابخ التجارية', 'Restaurant and commercial kitchen solutions') }}
                </h2>
            </div>

            <div class="kn-banners">
                @foreach ($solutionBanners as $banner)
                    <a href="{{ route('search') }}?keyword={{ urlencode($banner['keyword']) }}" class="kn-banner {{ $wide($banner['image']) }}">
                        <img src="{{ $img($banner['image']) }}" alt="{{ $banner['title'] }}" loading="lazy"
                            width="1363" height="818"
                            onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                    </a>
                @endforeach
            </div>
        </section>

        {{-- 4. Best sellers: 4 products + promo artwork per row --}}
        @if ($best_selling_products->count() > 0)
            <section class="kn-wrap kn-section" aria-labelledby="kn-bestsellers-title">
                <div class="kn-section-head">
                    <h2 id="kn-bestsellers-title" class="kn-section-title">
                        {{ $t('المنتجات الأكثر مبيعًا', 'Best sellers') }}
                    </h2>
                    <a href="{{ route('search') }}" class="kn-link">{{ $t('كل المنتجات', 'All products') }}</a>
                </div>

                @foreach ([$firstFourBestSelling, $secondFourBestSelling] as $rowIndex => $rowProducts)
                    @if ($rowProducts->isNotEmpty())
                        @php $promo = $bestSellerPromos[$rowIndex]; @endphp
                        <div class="kn-bs-row">
                            <div class="kn-products">
                                @foreach ($rowProducts as $product)
                                    <x-product-card :product="$product" />
                                @endforeach
                            </div>

                            <a href="{{ route('search') }}" class="kn-promo-art">
                                <img src="{{ $img($promo['image']) }}" alt="{{ $promo['title'] }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                            </a>
                        </div>
                    @endif
                @endforeach
            </section>
        @endif

        {{-- 5. Browse by project --}}
        <section class="kn-wrap kn-section" aria-labelledby="kn-projects-title">
            <div class="kn-section-head">
                <h2 id="kn-projects-title" class="kn-section-title">
                    {{ $t('تصفح حسب المشروع', 'Browse by project') }}
                </h2>
            </div>

            <div class="kn-bento">
                <div class="kn-bento-grid">
                    @foreach ($projects as $project)
                        <a href="{{ route('search') }}?keyword={{ urlencode($project['keyword']) }}" class="kn-tile {{ $wide($project['image']) }}">
                            <img src="{{ $img($project['image']) }}" alt="" loading="lazy"
                                onerror="this.onerror=null;this.src='{{ $placeholderRect }}';">
                            <span class="kn-tile-shade">
                                <span class="kn-tile-label">{{ $project['title'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- A real factory photo (also used on the About page) carries the 60-year story. --}}
                <div class="kn-tile kn-tile-feature">
                    <img src="{{ static_asset('assets/front_img/about8.jpeg') }}" alt="" loading="lazy"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/front_img/aboutus1.jpeg') }}';">
                    <div class="kn-tile-shade">
                        <span class="kn-years">{{ $t('60 عام', '60 years') }}</span>
                        <span class="kn-years-text">{{ $t('نساهم في دعم وتطوير قطاع الضيافة والمطاعم', 'Supporting and developing the hospitality and restaurant sector') }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 6. Latest offers --}}
        <section class="kn-wrap kn-section" aria-labelledby="kn-offers-title">
            <div class="kn-section-head">
                <h2 id="kn-offers-title" class="kn-section-title">
                    {{ $t('اكتشف أحدث العروض', 'Latest offers') }}
                </h2>
                <a href="{{ route('todays-deal') }}" class="kn-link">{{ $t('كل العروض', 'All offers') }}</a>
            </div>

            <div class="kn-offers">
                @foreach ($offers as $offer)
                    <a href="{{ route('search') }}?keyword={{ urlencode($offer['keyword']) }}" class="kn-offer">
                        <span class="kn-offer-img {{ $wide($offer['image']) }}">
                            <img src="{{ $img($offer['image']) }}" alt="" loading="lazy"
                                width="1363" height="818"
                                onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                        </span>
                        <span class="kn-offer-body">
                            <span class="kn-offer-title">{{ $offer['title'] }}</span>
                            <span class="kn-offer-deal">{{ $offer['deal'] }}</span>
                        </span>
                        <span class="kn-btn kn-btn-primary">{{ $shopNow }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    @include('frontend.partials.cart.cart_summary_toast')
@endsection
