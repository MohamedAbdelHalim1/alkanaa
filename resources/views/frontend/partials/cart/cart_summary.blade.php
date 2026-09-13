@php
    $knSumIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $kst = fn ($ar, $en) => $knSumIsAr ? $ar : $en;
@endphp
<div class="kn-cart-sum-wrap">
    <section class="kn-cart-sum" aria-labelledby="kn-cart-sum-title">

        @php
            $subtotal_for_min_order_amount = 0;
            $subtotal = 0;
            $tax = 0;
            $product_shipping_cost = 0;
            $shipping = 0;
            $coupon_code = null;
            $coupon_discount = 0;
            $total_point = 0;
        @endphp
        @php
            $service_total = 0;
            foreach ($carts as $cartItem) {
                if ($cartItem->add_service == 1) {
                    $product = get_single_product($cartItem->product_id);
                    $service_total += $product->service_fee * $cartItem->quantity;
                }
            }
        @endphp

        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                $subtotal_for_min_order_amount +=
                    cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];
                $shipping += $product_shipping_cost;
                if (get_setting('coupon_system') == 1 && $cartItem->coupon_applied == 1) {
                    $coupon_code = $cartItem->coupon_code;
                    $coupon_discount = $carts->sum('discount');
                }
                if (addon_is_activated('club_point')) {
                    $total_point += $product->earn_point * $cartItem['quantity'];
                }
            @endphp
        @endforeach

        <header class="kn-cart-sum-head">
            <h2 class="kn-cart-sum-title" id="kn-cart-sum-title">{{ translate('Order Summary') }}</h2>
            <span class="kn-cart-sum-count">{{ count($carts) }} {{ translate('Products') }}</span>
        </header>

        <!-- Minimum Order Amount -->
        @if (get_setting('minimum_order_amount_check') == 1 &&
                $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
            <p class="kn-cart-sum-alert">
                {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
            </p>
        @endif

        <input type="hidden" id="sub_total" value="{{ $subtotal }}">

        <dl class="kn-cart-sum-rows">
            <!-- Subtotal -->
            <div class="kn-cart-sum-row cart-subtotal">
                <dt>{{ translate('Subtotal') }} ({{ sprintf('%02d', count($carts)) }} {{ translate('Products') }})</dt>
                <dd>{{ single_price($subtotal) }}</dd>
            </div>
            <!-- Tax -->
            <div class="kn-cart-sum-row cart-tax">
                <dt>{{ translate('Tax') }}</dt>
                <dd>{{ single_price($tax) }}</dd>
            </div>
            @if ($proceed != 1)
                <!-- Total Shipping -->
                <div class="kn-cart-sum-row cart-shipping">
                    <dt>{{ translate('Total Shipping') }}</dt>
                    <dd>{{ single_price($shipping) }}</dd>
                </div>
            @else
                <div class="kn-cart-sum-row is-muted">
                    <dt>{{ translate('Shipping') }}</dt>
                    <dd>{{ $kst('تُحسب عند إتمام الطلب', 'Calculated at checkout') }}</dd>
                </div>
            @endif
            <!-- Redeem point -->
            @if (Session::has('club_point'))
                <div class="kn-cart-sum-row is-discount cart-club-point">
                    <dt>{{ translate('Redeem point') }}</dt>
                    <dd>{{ single_price(Session::get('club_point')) }}</dd>
                </div>
            @endif
            <!-- Coupon Discount -->
            @if ($coupon_discount > 0)
                <div class="kn-cart-sum-row is-discount cart-coupon-discount">
                    <dt>{{ translate('Coupon Discount') }}</dt>
                    <dd>{{ single_price($coupon_discount) }}</dd>
                </div>
            @endif

            @if ($service_total > 0)
                <div class="kn-cart-sum-row cart-service-fee">
                    <dt>{{ translate('Service Fee') }}</dt>
                    <dd>{{ single_price($service_total) }}</dd>
                </div>
            @endif
        </dl>

        @php
            $total = $subtotal + $tax + $shipping + $service_total;
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
                        <input type="hidden" name="proceed" value="{{ $proceed }}">
                        <span class="kn-cart-coupon-label">{{ $kst('كود الخصم المطبّق', 'Applied coupon') }}</span>
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
                        <input type="hidden" name="proceed" value="{{ $proceed }}">
                        <label for="kn-coupon-code" class="kn-cart-coupon-label">{{ $kst('كود الخصم', 'Coupon code') }}</label>
                        <div class="kn-cart-coupon-row">
                            <input type="text" id="kn-coupon-code" class="form-control" name="code"
                                onkeydown="return event.key != 'Enter';"
                                placeholder="{{ translate('Have coupon code? Apply here') }}" required>
                            <button type="button" id="coupon-apply"
                                class="kn-btn kn-co-btn-outline">{{ translate('Apply') }}</button>
                        </div>
                        @if (!auth()->check())
                            <small class="kn-cart-coupon-hint">{{ translate('You must Login as customer to apply coupon') }}</small>
                        @endif
                    </form>
                @endif
            </div>
        @endif

        @if ($proceed == 1)
            <!-- Continue to Checkout -->
            <div class="kn-cart-sum-actions">
                <a href="{{ route('checkout') }}" class="kn-btn kn-btn-primary kn-co-btn-lg kn-co-btn-block">
                    {{ translate('Proceed to Checkout') }}
                </a>
                <span class="kn-cart-sum-or">{{ translate('OR') }}</span>
                <a href="{{ route('quotation.post') }}" class="kn-btn kn-co-btn-outline kn-co-btn-block">
                    <i class="fa-regular fa-file-lines" aria-hidden="true"></i>
                    {{ translate('get_a_quote') }}
                </a>
            </div>
        @endif
    </section>

    @if (count($carts) > 0)
        <!-- Mobile: sticky total + primary action, sits above the bottom nav -->
        <div class="kn-cart-sumbar d-lg-none">
            <div class="kn-cart-sumbar-total">
                <span>{{ translate('Total') }}</span>
                <strong>{{ single_price($total) }}</strong>
            </div>
            @if ($proceed == 1)
                <a href="{{ route('checkout') }}" class="kn-btn kn-btn-primary">{{ translate('Proceed to Checkout') }}</a>
            @else
                <a href="#kn-place-order" class="kn-btn kn-btn-primary">{{ $kst('المتابعة للدفع', 'Continue to payment') }}</a>
            @endif
        </div>
    @endif
</div>
