@props(['product'])

@php
    $hasDiscount = $product->discount > 0;
    $newPrice = $hasDiscount
        ? ($product->discount_type == 'percent'
            ? $product->unit_price - ($product->unit_price * $product->discount) / 100
            : $product->unit_price - $product->discount)
        : $product->unit_price;
    $inStock = $product->current_stock > 0;
    $sku = $product->stocks->first()->sku ?? '';
    $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $name = $product->getTranslation('name');
    $url = route('product', $product->slug);
@endphp

<article class="kn-card">
    <div class="kn-card-media">
        <a href="{{ $url }}" class="kn-plinth" tabindex="-1" aria-hidden="true">
            <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" loading="lazy"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        @if ($hasDiscount)
            <span class="kn-card-discount">
                <bdi dir="ltr">-{{ $product->discount_type == 'percent' ? round($product->discount) . '%' : number_format($product->discount, 0) }}</bdi>
            </span>
        @endif

        <button type="button" class="kn-card-wish" onclick="addToWishList({{ $product->id }})"
            aria-label="{{ translate('Add to wishlist') }}">
            <i class="fa-regular fa-heart" aria-hidden="true"></i>
        </button>
    </div>

    <div class="kn-card-body">
        <div class="kn-card-meta">
            <span class="kn-stock {{ $inStock ? '' : 'is-out' }}">
                {{ $inStock ? ($isAr ? 'متوفر' : translate('Available')) : ($isAr ? 'نفد من المخزون' : translate('Out of stock')) }}
            </span>
            <span class="kn-ship">{{ $isAr ? 'شحن مجاني' : translate('Free Shipping') }}</span>
        </div>

        <a href="{{ $url }}" class="kn-card-title">{{ $name }}</a>

        @if ($sku)
            <p class="kn-sku">{{ $sku }}</p>
        @endif

        <div class="kn-card-foot">
            <div class="kn-price">
                <span class="kn-price-now">
                    {{ number_format($newPrice, 0) }}
                    <small>{{ $isAr ? 'ر.س' : 'SAR' }}</small>
                </span>
                @if ($hasDiscount)
                    <span class="kn-price-was">{{ number_format($product->unit_price, 0) }}</span>
                @endif
            </div>
            <p class="kn-vat">{{ translate('inclusive_of_vat') }}</p>

            <button type="button" class="kn-btn kn-btn-primary add-to-cart-btn" data-id="{{ $product->id }}"
                @if (!$inStock) disabled @endif>
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                <span>{{ $inStock ? ($isAr ? 'أضف للسلة' : translate('Add to Cart')) : ($isAr ? 'غير متوفر' : translate('Out of stock')) }}</span>
            </button>
        </div>
    </div>
</article>
