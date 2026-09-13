{{-- Legacy multi-step summary (used by frontend/payment_select). Styled with the cart summary classes. --}}
<section class="kn-cart-sum" aria-labelledby="kn-cart-sum-title">

    <header class="kn-cart-sum-head">
        <h2 class="kn-cart-sum-title" id="kn-cart-sum-title">{{ translate('Summary') }}</h2>
        <!-- Items Count -->
        <span class="kn-cart-sum-count">
            {{ count($carts) }}
            {{ translate('Items') }}
        </span>
    </header>

    <!-- Minimum Order Amount -->
    @php
        $coupon_discount = 0;
    @endphp
    @if (get_setting('coupon_system') == 1)
        @php
            $coupon_code = null;
        @endphp

        @foreach ($carts as $key => $cartItem)
            @if ($cartItem->coupon_applied == 1)
                @php
                    $coupon_code = $cartItem->coupon_code;
                    break;
                @endphp
            @endif
        @endforeach

        @php
            $coupon_discount = $carts->sum('discount');
        @endphp
    @endif

    @php $subtotal_for_min_order_amount = 0; @endphp
    @foreach ($carts as $key => $cartItem)
        @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
    @endforeach
    @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
        <p class="kn-cart-sum-alert">
            {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
        </p>
    @endif

    <!-- Club point -->
    @if (addon_is_activated('club_point'))
        @php
            $total_point = 0;
        @endphp
        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                $total_point += $product->earn_point * $cartItem['quantity'];
            @endphp
        @endforeach
        <div class="kn-cart-sum-row">
            <span>{{ translate('Total Clubpoint') }}</span>
            <strong>{{ $total_point }}</strong>
        </div>
    @endif

    <!-- Products Info -->
    <ul class="kn-cart-sum-items">
        @php
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $product_shipping_cost = 0;
        @endphp
        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];

                $shipping += $product_shipping_cost;

                $product_name_with_choice = $product->getTranslation('name');
                if ($cartItem['variant'] != null) {
                    $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                }
            @endphp
            <li class="kn-cart-sum-item cart_item">
                <span class="kn-cart-sum-item-name">
                    {{ $product_name_with_choice }}
                    <strong class="product-quantity">× {{ $cartItem['quantity'] }}</strong>
                </span>
                <span class="kn-cart-sum-item-price">{{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}</span>
            </li>
        @endforeach
    </ul>

    <input type="hidden" id="sub_total" value="{{ $subtotal }}">

    <dl class="kn-cart-sum-rows">
        <!-- Subtotal -->
        <div class="kn-cart-sum-row cart-subtotal">
            <dt>{{ translate('Subtotal') }}</dt>
            <dd>{{ single_price($subtotal) }}</dd>
        </div>
        <!-- Tax -->
        <div class="kn-cart-sum-row cart-shipping">
            <dt>{{ translate('Tax') }}</dt>
            <dd>{{ single_price($tax) }}</dd>
        </div>
        <!-- Total Shipping -->
        <div class="kn-cart-sum-row cart-shipping">
            <dt>{{ translate('Total Shipping') }}</dt>
            <dd>{{ single_price($shipping) }}</dd>
        </div>
        <!-- Redeem point -->
        @if (Session::has('club_point'))
            <div class="kn-cart-sum-row is-discount cart-shipping">
                <dt>{{ translate('Redeem point') }}</dt>
                <dd>{{ single_price(Session::get('club_point')) }}</dd>
            </div>
        @endif
        <!-- Coupon Discount -->
        @if ($coupon_discount > 0)
            <div class="kn-cart-sum-row is-discount cart-shipping">
                <dt>{{ translate('Coupon Discount') }}</dt>
                <dd>{{ single_price($coupon_discount) }}</dd>
            </div>
        @endif
    </dl>

    @php
        $total = $subtotal + $tax + $shipping;
        if (Session::has('club_point')) {
            $total -= Session::get('club_point');
        }
        if ($coupon_discount > 0) {
            $total -= $coupon_discount;
        }
    @endphp
    <!-- Total -->
    <div class="kn-cart-sum-total cart-total">
        <span>{{ translate('Total') }}</span>
        <strong>{{ single_price($total) }}</strong>
    </div>

    <!-- Coupon System -->
    @if (get_setting('coupon_system') == 1)
        <div class="kn-cart-coupon">
            @if ($coupon_discount > 0 && $coupon_code)
                <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <div class="kn-cart-coupon-row">
                        <div class="kn-cart-coupon-applied">
                            <i class="las la-ticket-alt" aria-hidden="true"></i>
                            <span>{{ $coupon_code }}</span>
                        </div>
                        <button type="button" id="coupon-remove"
                            class="kn-btn kn-co-btn-outline">{{ translate('Change Coupon') }}</button>
                    </div>
                </form>
            @else
                <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                    <div class="kn-cart-coupon-row">
                        <input type="text" class="form-control" name="code"
                            onkeydown="return event.key != 'Enter';"
                            aria-label="{{ translate('Have coupon code? Apply here') }}"
                            placeholder="{{ translate('Have coupon code? Apply here') }}" required>
                        <button type="button" id="coupon-apply"
                            class="kn-btn kn-co-btn-outline">{{ translate('Apply') }}</button>
                    </div>
                </form>
            @endif
        </div>
    @endif

</section>
