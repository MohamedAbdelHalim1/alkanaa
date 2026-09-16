@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title ?? $detailedProduct->getTranslation('name') }}@stop
@section('meta_description'){{ $detailedProduct->meta_description }}@stop
@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    @php
        $availability = 'out of stock';
        $qty = 0;
        if ($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        } else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if ($qty > 0) {
            $availability = 'in stock';
        }
    @endphp
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:currency" content="{{ get_system_default_currency()->code }}" />
@endsection

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $cur = $t('ر.س', 'SAR');

        $productName = $detailedProduct->getTranslation('name');
        $photos = explode(',', $detailedProduct->photos);
        $hasDiscount = $detailedProduct->discount > 0;
        $old_price = $detailedProduct->unit_price;
        $discounted_price = $hasDiscount
            ? ($detailedProduct->discount_type == 'percent'
                ? $old_price - ($old_price * $detailedProduct->discount / 100)
                : $old_price - $detailedProduct->discount)
            : $old_price;
        $saving = $old_price - $discounted_price;
        $firstStock = $detailedProduct->stocks->first();
        $sku = $firstStock && $firstStock->sku ? $firstStock->sku : null;
        $inStock = $detailedProduct->stocks->sum('qty') > 0;
        $hasSpecs = $detailedProduct->weight || $detailedProduct->unit || $detailedProduct->is_stainless;

        $phone = get_setting('contact_phone') ?? '966565124444';
        $waText = $t('مرحباً، أستفسر عن منتج: ', 'Hello, I would like a quote for: ') . $productName . ' ' . route('product', $detailedProduct->slug);

        $relatedProducts = \App\Models\Product::with('stocks')
            ->where('category_id', $detailedProduct->category_id)
            ->where('id', '!=', $detailedProduct->id)
            ->where('published', 1)
            ->where('auction_product', 0)
            ->latest()
            ->take(4)
            ->get();
    @endphp

    <div class="kn-pd">
        {{-- Breadcrumb --}}
        <nav class="kn-pd-crumbs" aria-label="{{ $t('مسار التنقل', 'Breadcrumb') }}">
            <ol class="kn-wrap">
                <li>
                    <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                </li>
                @if ($detailedProduct->category)
                    <li>
                        <i class="fa-solid fa-chevron-right kn-pd-crumb-sep" aria-hidden="true"></i>
                        <a href="{{ route('products.category', $detailedProduct->category->slug) }}">
                            {{ $detailedProduct->category->getTranslation('name') }}
                        </a>
                    </li>
                @endif
                <li aria-current="page">
                    <i class="fa-solid fa-chevron-right kn-pd-crumb-sep" aria-hidden="true"></i>
                    <span>{{ $productName }}</span>
                </li>
            </ol>
        </nav>

        <section class="kn-wrap kn-pd-main">
            <div class="kn-pd-grid">

                {{-- Gallery --}}
                <div class="kn-pd-gallery">
                    <div class="kn-plinth kn-pd-stage">
                        <div class="kn-pd-badges">
                            @if ($hasDiscount)
                                <span class="kn-pd-badge is-sale">
                                    -{{ $detailedProduct->discount_type == 'percent' ? round($detailedProduct->discount) . '%' : number_format($detailedProduct->discount) . ' ' . $cur }}
                                </span>
                            @endif
                            @if ($detailedProduct->is_stainless)
                                <span class="kn-pd-badge">
                                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>{{ $t('ستانلس ستيل', 'Stainless steel') }}
                                </span>
                            @endif
                        </div>

                        <img id="mainProductImage" src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                            alt="{{ $productName }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>

                    @if (count($photos) > 1 && $photos[0] != '')
                        <div class="kn-pd-thumbs">
                            <button type="button" class="kn-pd-thumb thumb-btn is-active"
                                aria-label="{{ $t('عرض الصورة', 'View image') }} 1" aria-pressed="true"
                                onclick="changeMainImage('{{ uploaded_asset($detailedProduct->thumbnail_img) }}', this)">
                                <img src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}" alt="" loading="lazy">
                            </button>
                            @foreach ($photos as $photo)
                                @if ($photo)
                                    <button type="button" class="kn-pd-thumb thumb-btn"
                                        aria-label="{{ $t('عرض الصورة', 'View image') }} {{ $loop->iteration + 1 }}" aria-pressed="false"
                                        onclick="changeMainImage('{{ uploaded_asset($photo) }}', this)">
                                        <img src="{{ uploaded_asset($photo) }}" alt="" loading="lazy">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Purchase box --}}
                <div class="kn-pd-buy">
                    <div class="kn-pd-head">
                        <h1 class="kn-pd-title">{{ $productName }}</h1>
                        <button type="button" class="kn-pd-wish" onclick="addToWishList({{ $detailedProduct->id }})"
                            aria-label="{{ translate('Add to Wishlist') }}" title="{{ translate('Add to Wishlist') }}">
                            <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="kn-pd-meta">
                        @if ($sku)
                            <span class="kn-pd-sku">{{ translate('SKU') }}: <bdi>{{ $sku }}</bdi></span>
                        @endif
                        <span class="kn-stock {{ $inStock ? '' : 'is-out' }}">
                            {{ $inStock ? translate('available') : translate('not_available') }}
                        </span>
                        @if ($detailedProduct->shipping_type == 'free')
                            <span class="kn-pd-ship">
                                <i class="fa-solid fa-truck-fast" aria-hidden="true"></i>{{ translate('free_shipping') }}
                            </span>
                        @endif
                    </div>

                    <div class="kn-pd-price">
                        <div class="kn-pd-price-row">
                            <span class="kn-pd-price-now">
                                {{ number_format($discounted_price, 2) }} <small>{{ $cur }}</small>
                            </span>
                            @if ($hasDiscount)
                                <del class="kn-pd-price-was">{{ number_format($old_price, 2) }} {{ $cur }}</del>
                            @endif
                            {{-- unit_price is stored VAT-inclusive (the cart re-adds 15% on the base stock price). --}}
                            <span class="kn-pd-vat" title="{{ $t('ضريبة القيمة المضافة 15%', 'VAT 15%') }}">
                                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>{{ $t('شامل الضريبة', 'VAT included') }}
                            </span>
                        </div>
                        @if ($hasDiscount)
                            <p class="kn-pd-saving">{{ translate('save') }} {{ number_format($saving, 2) }} {{ $cur }}</p>
                        @endif
                    </div>

                    <div class="kn-pd-actions">
                        <button type="button" class="kn-btn kn-btn-primary kn-pd-cart add-to-cart-btn"
                            data-id="{{ $detailedProduct->id }}"
                            @if ($detailedProduct->stocks->sum('qty') == 0) disabled @endif>
                            <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                            <span>{{ $inStock ? translate('add_to_cart') : translate('not_available') }}</span>
                        </button>

                        <a href="https://wa.me/{{ $phone }}?text={{ urlencode($waText) }}" target="_blank" rel="noopener"
                            class="kn-btn kn-pd-wa">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            <span>{{ $t('طلب تسعير', 'Request a quote') }}</span>
                        </a>
                    </div>

                    <ul class="kn-pd-trust">
                        <li><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><span>{{ translate('product_guarantee') }}</span></li>
                        <li><i class="fa-solid fa-truck-fast" aria-hidden="true"></i><span>{{ translate('fast_shipping') }}</span></li>
                        <li><i class="fa-solid fa-headset" aria-hidden="true"></i><span>{{ translate('customer_service') }}</span></li>
                        <li><i class="fa-solid fa-lock" aria-hidden="true"></i><span>{{ translate('secure_payment') }}</span></li>
                    </ul>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="kn-pd-tabs">
                <div class="kn-pd-tablist" id="productDetailTabs" role="tablist">
                    <button type="button" class="kn-pd-tab tab-trigger is-active" role="tab" id="tab-desc-btn"
                        aria-controls="tab-desc" aria-selected="true" onclick="switchTab('tab-desc', this)">
                        {{ translate('Description') }}
                    </button>
                    @if ($hasSpecs)
                        <button type="button" class="kn-pd-tab tab-trigger" role="tab" id="tab-specs-btn"
                            aria-controls="tab-specs" aria-selected="false" onclick="switchTab('tab-specs', this)">
                            {{ translate('specifications') }}
                        </button>
                    @endif
                    <button type="button" class="kn-pd-tab tab-trigger" role="tab" id="tab-shipping-btn"
                        aria-controls="tab-shipping" aria-selected="false" onclick="switchTab('tab-shipping', this)">
                        {{ $t('الشحن والتوصيل', 'Shipping & delivery') }}
                    </button>
                </div>

                {{-- Description --}}
                <div id="tab-desc" class="kn-pd-panel tab-content-panel is-active" role="tabpanel" aria-labelledby="tab-desc-btn">
                    <div class="kn-pd-prose">
                        {!! $detailedProduct->getTranslation('description') !!}
                    </div>

                    @if (in_array((int) $detailedProduct->id, [86, 87, 88, 89, 90, 91]))
                        <div class="kn-pd-note">
                            <h3 class="kn-pd-note-title">
                                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                                {{ $t('متطلبات الخدمة', 'Service requirements') }}
                            </h3>
                            <ul>
                                <li>{{ $t('توضيح المساحة الحالية للمطبخ بالرسم.', 'A drawing of the current kitchen space.') }}</li>
                                <li>{{ $t('تحديد أماكن الأبواب والنوافذ.', 'The locations of doors and windows.') }}</li>
                                <li>{{ $t('تحديد مواقع توصيلات الكهرباء والمياه.', 'The locations of electrical and water connections.') }}</li>
                                <li>{{ $t('تحديد أماكن الأجهزة والمعدات بالمطبخ.', 'The positions of appliances and equipment in the kitchen.') }}</li>
                                <li>{{ $t('تقديم الملفات بصيغة صورة أو PDF واضحة ومقروءة.', 'Files provided as clear, legible images or PDF.') }}</li>
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Specifications --}}
                <div id="tab-specs" class="kn-pd-panel tab-content-panel" role="tabpanel" aria-labelledby="tab-specs-btn">
                    <table class="kn-pd-specs">
                        <tbody>
                            @if ($sku)
                                <tr>
                                    <th scope="row">{{ translate('SKU') }}</th>
                                    <td><bdi>{{ $sku }}</bdi></td>
                                </tr>
                            @endif
                            @if ($detailedProduct->weight)
                                <tr>
                                    <th scope="row">{{ translate('weight') }}</th>
                                    <td>{{ $detailedProduct->weight }} {{ translate('kg') }}</td>
                                </tr>
                            @endif
                            @if ($detailedProduct->unit)
                                <tr>
                                    <th scope="row">{{ translate('unit') }}</th>
                                    <td>{{ $detailedProduct->unit }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">{{ translate('material') }}</th>
                                <td>
                                    @if ($detailedProduct->is_stainless)
                                        {{ $t('ستانلس ستيل عالي الجودة', 'High-grade stainless steel') }}
                                    @else
                                        {{ translate('Standard') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">{{ $t('بلد الصنع', 'Country of origin') }}</th>
                                <td>{{ $t('المملكة العربية السعودية', 'Saudi Arabia') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Shipping --}}
                <div id="tab-shipping" class="kn-pd-panel tab-content-panel" role="tabpanel" aria-labelledby="tab-shipping-btn">
                    <div class="kn-pd-info">
                        <div class="kn-pd-info-item">
                            <i class="fa-solid fa-truck-fast" aria-hidden="true"></i>
                            <div>
                                <h3>{{ $t('التوصيل السريع لكافة مدن المملكة', 'Fast delivery to every city in the Kingdom') }}</h3>
                                <p>{{ $t('يتم توصيل وتجهيز طلبات المعدات التجارية خلال 2-5 أيام عمل مع خيارات الشحن الآمن والتركيب المباشر من قبل فريقنا المختص.', 'Commercial equipment orders are delivered and prepared within 2–5 business days, with secure shipping options and direct installation by our specialist team.') }}</p>
                            </div>
                        </div>
                        <div class="kn-pd-info-item">
                            <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                            <div>
                                <h3>{{ $t('الضمان والصيانة', 'Warranty & maintenance') }}</h3>
                                <p>{{ $t('تشمل جميع معداتنا ضماناً شاملاً ضد عيوب التصنيع مع توفير قطع الغيار الأصلية والدعم الفني المباشر.', 'All our equipment carries a comprehensive warranty against manufacturing defects, with genuine spare parts and direct technical support.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Related products --}}
        @if ($relatedProducts->isNotEmpty())
            <section class="kn-wrap kn-section" aria-labelledby="kn-pd-related-title">
                <div class="kn-section-head">
                    <h2 class="kn-section-title" id="kn-pd-related-title">{{ $t('منتجات ذات صلة', 'Related products') }}</h2>
                    @if ($detailedProduct->category)
                        <a href="{{ route('products.category', $detailedProduct->category->slug) }}" class="kn-link">{{ $t('عرض الكل', 'View all') }}</a>
                    @endif
                </div>
                <div class="kn-products">
                    @foreach ($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Mobile sticky action bar (sits above .mobile-bottom-nav) --}}
        <div class="kn-pd-bar" role="region" aria-label="{{ $t('إضافة سريعة للسلة', 'Quick add to cart') }}">
            <div class="kn-pd-bar-price">
                <span class="kn-pd-bar-now">{{ number_format($discounted_price, 2) }} <small>{{ $cur }}</small></span>
                @if ($hasDiscount)
                    <del class="kn-pd-bar-was">{{ number_format($old_price, 2) }}</del>
                @endif
            </div>
            <button type="button" class="kn-btn kn-btn-primary add-to-cart-btn" data-id="{{ $detailedProduct->id }}"
                @if ($detailedProduct->stocks->sum('qty') == 0) disabled @endif>
                <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                <span>{{ $inStock ? translate('add_to_cart') : translate('not_available') }}</span>
            </button>
        </div>
    </div>

    <script>
        function changeMainImage(url, el) {
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg && url) {
                mainImg.src = url;
            }
            document.querySelectorAll('.thumb-btn').forEach(b => {
                b.classList.remove('is-active');
                b.setAttribute('aria-pressed', 'false');
            });
            if (el) {
                el.classList.add('is-active');
                el.setAttribute('aria-pressed', 'true');
                if (el.scrollIntoView) {
                    el.scrollIntoView({ block: 'nearest', inline: 'nearest' });
                }
            }
        }

        function switchTab(tabId, el) {
            document.querySelectorAll('.tab-content-panel').forEach(panel => panel.classList.remove('is-active'));
            document.querySelectorAll('.tab-trigger').forEach(btn => {
                btn.classList.remove('is-active');
                btn.setAttribute('aria-selected', 'false');
            });

            const targetPanel = document.getElementById(tabId);
            if (targetPanel) {
                targetPanel.classList.add('is-active');
            }
            if (el) {
                el.classList.add('is-active');
                el.setAttribute('aria-selected', 'true');
                if (el.scrollIntoView) {
                    el.scrollIntoView({ block: 'nearest', inline: 'nearest' });
                }
            }
        }
    </script>
@endsection
