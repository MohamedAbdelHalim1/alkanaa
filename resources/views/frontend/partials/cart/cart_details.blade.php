@php
    $cart_count = count($carts);
    $active_carts = $cart_count > 0 ? $carts->toQuery()->active()->get() : [];
    $knIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $kt = fn ($ar, $en) => $knIsAr ? $ar : $en;
@endphp
<div class="kn-wrap">
    <div class="kn-co-head">
        <h1 class="kn-co-title">
            {{ $kt('سلة الشراء', 'Shopping cart') }}
            @if ($cart_count > 0)
                <small>({{ $cart_count }})</small>
            @endif
        </h1>
        <a href="{{ route('home') }}" class="kn-link">{{ $kt('متابعة التسوق', 'Continue shopping') }}</a>
    </div>

    @if ($cart_count > 0)
        @include('frontend.partials.cart.checkout_steps', ['current' => 1])

        <div class="kn-co-layout">
            <div class="kn-co-main">
                @if (auth()->check())
                    @php
                        $welcomeCoupon = ifUserHasWelcomeCouponAndNotUsed();
                    @endphp
                    @if ($welcomeCoupon)
                        @php
                            $discount = $welcomeCoupon->discount_type == 'amount' ? single_price($welcomeCoupon->discount) : $welcomeCoupon->discount . '%';
                        @endphp
                        <div class="kn-cart-note" role="note">
                            <p class="kn-cart-note-text">
                                {{ translate('Welcome Coupon') }} <strong>{{ $discount }}</strong>
                                {{ translate('Discount on your Purchase Within') }}
                                <strong>{{ $welcomeCoupon->validation_days }}</strong>
                                {{ translate('days of Registration') }}
                            </p>
                            <button type="button" class="kn-btn kn-co-btn-outline"
                                onclick="copyCouponCode('{{ $welcomeCoupon->coupon_code }}')">
                                <i class="las la-copy" aria-hidden="true"></i>
                                {{ translate('Copy coupon Code') }}
                            </button>
                        </div>
                    @endif
                @endif

                <div class="kn-cart-panel">
                    <div class="kn-cart-toolbar">
                        <label class="aiz-checkbox kn-cart-check">
                            <input type="checkbox" class="check-all" @if (count($active_carts) == $cart_count) checked @endif>
                            <span class="aiz-square-check"></span>
                            <span class="kn-cart-check-text">{{ translate('Select All') }} ({{ $cart_count }})</span>
                        </label>
                        <span class="kn-cart-toolbar-hint">{{ $kt('المنتجات المحددة فقط تدخل في الطلب', 'Only selected items are ordered') }}</span>
                    </div>

                    <!-- Cart Items -->
                    <ul class="kn-cart-lines">
                        @php
                            $total = 0;
                            $admin_products = array();
                            $seller_products = array();
                            $admin_product_variation = array();
                            $seller_product_variation = array();
                            foreach ($carts as $key => $cartItem){
                                $product = get_single_product($cartItem['product_id']);

                                if($product->added_by == 'admin'){
                                    array_push($admin_products, $cartItem['product_id']);
                                    $admin_product_variation[] = $cartItem['variation'];
                                }
                                else{
                                    $product_ids = array();
                                    if(isset($seller_products[$product->user_id])){
                                        $product_ids = $seller_products[$product->user_id];
                                    }
                                    array_push($product_ids, $cartItem['product_id']);
                                    $seller_products[$product->user_id] = $product_ids;
                                    $seller_product_variation[$product->user_id][] = $cartItem['variation'];
                                }
                            }
                        @endphp

                        <!-- Inhouse Products -->
                        @if (!empty($admin_products))
                            @php
                                $all_admin_products = true;
                                if(count($admin_products) != count($carts->toQuery()->active()->whereIn('product_id', $admin_products)->get())){
                                    $all_admin_products = false;
                                }
                            @endphp
                            <li class="kn-cart-group">
                                <label class="aiz-checkbox kn-cart-check">
                                    <input type="checkbox" class="check-one check-seller" value="admin" @if ($all_admin_products) checked @endif>
                                    <span class="aiz-square-check"></span>
                                    <span class="kn-cart-group-title">{{ translate('Inhouse Products') }} ({{ count($admin_products) }})</span>
                                </label>
                            </li>
                            @foreach ($admin_products as $key => $product_id)
                                @php
                                    $product = get_single_product($product_id);
                                    $cartItem = $carts->toQuery()->where('product_id', $product_id)->where('variation', $admin_product_variation[$key])->first();
                                    $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
                                    $total = $total + cart_product_price($cartItem, $product, false) * $cartItem->quantity;
                                @endphp
                                @include('frontend.partials.cart.cart_line', [
                                    'product' => $product,
                                    'cartItem' => $cartItem,
                                    'product_stock' => $product_stock,
                                    'product_id' => $product_id,
                                    'variation' => $admin_product_variation[$key],
                                    'checkClass' => 'check-one-admin',
                                    'kt' => $kt,
                                ])
                            @endforeach
                        @endif

                        <!-- Seller Products -->
                        @if (!empty($seller_products))
                            @foreach ($seller_products as $key => $seller_product)
                                @php
                                    $all_seller_products = true;
                                    if(count($seller_product) != count($carts->toQuery()->active()->whereIn('product_id', $seller_product)->get())){
                                        $all_seller_products = false;
                                    }
                                @endphp
                                <li class="kn-cart-group">
                                    <label class="aiz-checkbox kn-cart-check">
                                        <input type="checkbox" class="check-one check-seller" value="seller-{{ $key }}" @if ($all_seller_products) checked @endif>
                                        <span class="aiz-square-check"></span>
                                        <span class="kn-cart-group-title">{{ get_shop_by_user_id($key)->name }} {{ translate('Products') }} ({{ count($seller_product) }})</span>
                                    </label>
                                </li>
                                @foreach ($seller_product as $key2 => $product_id)
                                    @php
                                        $product = get_single_product($product_id);
                                        $cartItem = $carts->toQuery()->where('product_id', $product_id)->where('variation', $seller_product_variation[$key][$key2])->first();
                                        $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
                                        $total = $total + cart_product_price($cartItem, $product, false) * $cartItem->quantity;
                                    @endphp
                                    @include('frontend.partials.cart.cart_line', [
                                        'product' => $product,
                                        'cartItem' => $cartItem,
                                        'product_stock' => $product_stock,
                                        'product_id' => $product_id,
                                        'variation' => $seller_product_variation[$key][$key2],
                                        'checkClass' => 'check-one-seller-' . $key,
                                        'kt' => $kt,
                                    ])
                                @endforeach
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Cart Summary -->
            <aside class="kn-co-aside" id="cart_summary">
                @include('frontend.partials.cart.cart_summary', ['proceed' => 1, 'carts' => $active_carts])
            </aside>
        </div>
    @else
        <!-- Empty cart -->
        <div class="kn-cart-empty">
            <span class="kn-plinth kn-cart-empty-icon" aria-hidden="true">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>
            <h2 class="kn-cart-empty-title">{{ translate('Your Cart is empty') }}</h2>
            <p class="kn-cart-empty-text">
                {{ $kt('تصفح معدات المطابخ التجارية وأضف ما تحتاجه إلى السلة.', 'Browse our commercial kitchen equipment and add what you need to your cart.') }}
            </p>
            <a href="{{ route('home') }}" class="kn-btn kn-btn-primary kn-co-btn-lg">
                {{ $kt('متابعة التسوق', 'Continue shopping') }}
            </a>
        </div>
    @endif
</div>
