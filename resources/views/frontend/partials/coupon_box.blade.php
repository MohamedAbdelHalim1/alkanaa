@php
    $couponUserType = $coupon->user->user_type;
    $knCpnVariant = 'is-admin';
@endphp

@if ($couponUserType == 'admin' && $coupon->type != 'welcome_base')
    @php $knCpnVariant = 'is-admin'; @endphp
@elseif ($couponUserType == 'seller')
    @php $knCpnVariant = 'is-seller'; @endphp
@elseif ($coupon->type == 'welcome_base')
    @php $knCpnVariant = 'is-welcome'; @endphp
@endif


@if($coupon->type == 'product_base')
    @php
        $products = json_decode($coupon->details);
        $coupon_products = [];
        foreach($products as $product) {
            array_push($coupon_products, $product->product_id);
        }
    @endphp
@else
    @php
        $coupon_discount = json_decode($coupon->details);
    @endphp
@endif
@php
    if($coupon->user->user_type != 'admin') {
        $shop = $coupon->user->shop;
        $name = $shop->name;
    }
    else {
        $name = get_setting('website_name');
    }
@endphp

<article class="kn-cpn {{ $knCpnVariant }}">

    <!-- Shop Name -->
    <header class="kn-cpn-head">
        <p class="kn-cpn-shop">{{ $name }}</p>
        @if (\Request::route()->getName() == 'coupons.all')
            <a
                @if($coupon->user->user_type != 'admin')
                    href="{{ route('shop.visit', $shop->slug) }}"
                @else
                    href="{{ route('inhouse.all') }}"
                @endif
                class="kn-link kn-cpn-visit"
            >
                {{ translate('Visit Store') }}
            </a>
        @endif
    </header>

    <!-- Discount -->
    @if($coupon->discount_type == 'amount')
        <p class="kn-cpn-off">{{ single_price($coupon->discount) }} {{ translate('OFF') }}</p>
    @else
        <p class="kn-cpn-off">{{ $coupon->discount }}% {{ translate('OFF') }}</p>
    @endif

    <div class="kn-cpn-cut" aria-hidden="true"></div>

    <!-- Coupon Details -->
    <div class="kn-cpn-details">
        @if($coupon->type == 'product_base')
            <!-- Coupon Products -->
            @php $products = get_multiple_products($coupon_products); @endphp
            <div class="aiz-carousel slick-left gutters-16" data-items="6" data-lg-items="6"  data-md-items="4" data-sm-items="4" data-xs-items="4" data-arrows='false' data-infinite='true' data-autoplay="true">
                @foreach($products as $key => $product)
                    <a href="{{ route('product', $product->slug) }}" title="{{ $product->name }}" class="kn-cpn-thumb" target="_blank">
                        <img class="img-fit mx-auto h-48px w-48px"
                            src="{{ uploaded_asset($product->thumbnail_img) }}"
                            data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                            alt="{{ $product->name }}">
                    </a>
                @endforeach
            </div>
        @elseif($coupon->type == 'cart_base')
            <!-- Coupon Discount range -->
            <p class="mb-0">
                @if($coupon->discount_type == 'amount')
                    {{ translate('Min Spend ') }} <strong>{{ single_price($coupon_discount->min_buy) }}</strong> {{ translate('from') }} <strong>{{ $name }}</strong> {{ translate('to get') }} <strong>{{ single_price($coupon->discount) }}</strong> {{ translate('OFF on total orders') }}
                @else
                    {{ translate('Min Spend ') }} <strong>{{ single_price($coupon_discount->min_buy) }}</strong> {{ translate('from') }} <strong>{{ $name }}</strong> {{ translate('to get') }} <strong>{{ $coupon->discount }}%</strong> {{ translate('OFF on total orders') }}
                @endif
            </p>
        @else
            <p class="mb-0">
                @if($coupon->discount_type == 'amount')
                    <strong>{{ single_price($coupon->discount) }}</strong> {{ translate('OFF on total orders within').' '.$coupon_discount->validation_days.' '.translate('days of registration') }}
                @else
                    <strong>{{ $coupon->discount }}%</strong> {{ translate('OFF on total orders within').' '.$coupon_discount->validation_days.' '.translate('days of registration') }}
                @endif
            </p>
        @endif
    </div>

    <!-- Coupon Code -->
    <footer class="kn-cpn-foot">
        <span class="kn-cpn-code-wrap">
            {{ translate('Code') }}:
            <strong class="kn-cpn-code">{{ $coupon->code }}</strong>
        </span>
        <button type="button" class="kn-cpn-copy" onclick="copyCouponCode('{{ $coupon->code }}')"
            data-toggle="tooltip" data-title="{{ translate('Copy the Code') }}" data-placement="top"
            aria-label="{{ translate('Copy the Code') }}">
            <i class="las la-copy" aria-hidden="true"></i>
        </button>
    </footer>
</article>
