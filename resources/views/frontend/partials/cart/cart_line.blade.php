{{--
    One cart line item. Rendered by partials/cart/cart_details for in-house and seller groups.
    Expects: $product, $cartItem, $product_stock, $product_id, $variation, $checkClass, $kt
    JS hooks kept: .check-one + group class, name="id[]", .aiz-plus-minus (buttons are siblings of the
    input, data-type/data-field), name="quantity[id]" + onchange updateQuantity(), removeFromCartView().
--}}
@php
    $knLineName = $product->getTranslation('name');
    $knLineUrl = route('product', $product->slug);
@endphp
<li class="kn-cart-line">
    <label class="aiz-checkbox kn-cart-line-check">
        <input type="checkbox" class="check-one {{ $checkClass }}" name="id[]" value="{{ $product_id }}"
            @if ($cartItem->status == 1) checked @endif>
        <span class="aiz-square-check"></span>
        <span class="kn-sr-only">{{ $kt('تحديد', 'Select') }}: {{ $knLineName }}</span>
    </label>

    <a href="{{ $knLineUrl }}" class="kn-plinth kn-cart-line-media" tabindex="-1" aria-hidden="true">
        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" loading="lazy"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
    </a>

    <div class="kn-cart-line-body">
        <a href="{{ $knLineUrl }}" class="kn-cart-line-name">{{ $knLineName }}</a>
        @if ($variation != '')
            <p class="kn-cart-line-meta">{{ translate('Variation') }}: {{ $variation }}</p>
        @endif
        <p class="kn-cart-line-unit">
            {{ cart_product_price($cartItem, $product, true, false) }}
            <span>/ {{ $kt('للوحدة', 'unit') }}</span>
        </p>
        <p class="kn-cart-line-meta">{{ translate('Tax') }}: {{ cart_product_tax($cartItem, $product) }}</p>
    </div>

    <div class="kn-cart-line-qty">
        @if ($product->digital != 1 && $product->auction_product == 0)
            <div class="aiz-plus-minus kn-cart-stepper">
                <button type="button" class="kn-cart-stepper-btn" data-type="minus"
                    data-field="quantity[{{ $cartItem->id }}]" aria-label="{{ $kt('إنقاص الكمية', 'Decrease quantity') }}">
                    <i class="las la-minus" aria-hidden="true"></i>
                </button>
                <input type="number" name="quantity[{{ $cartItem->id }}]"
                    class="kn-cart-stepper-input input-number"
                    placeholder="1" value="{{ $cartItem['quantity'] }}"
                    min="{{ $product->min_qty }}"
                    max="{{ $product_stock->qty ?? 0 }}"
                    onchange="updateQuantity({{ $cartItem->id }}, this)"
                    aria-label="{{ translate('Quantity') }}: {{ $knLineName }}">
                <button type="button" class="kn-cart-stepper-btn" data-type="plus"
                    data-field="quantity[{{ $cartItem->id }}]" aria-label="{{ $kt('زيادة الكمية', 'Increase quantity') }}">
                    <i class="las la-plus" aria-hidden="true"></i>
                </button>
            </div>
        @elseif ($product->auction_product == 1)
            <span class="kn-cart-qty-static">1</span>
        @endif
    </div>

    <div class="kn-cart-line-total">
        <span>{{ translate('Total') }}</span>
        <strong>{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem->quantity) }}</strong>
    </div>

    <button type="button" class="kn-cart-remove" onclick="removeFromCartView(event, {{ $cartItem->id }})"
        aria-label="{{ translate('Remove') }}: {{ $knLineName }}" title="{{ translate('Remove') }}">
        <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
    </button>
</li>
