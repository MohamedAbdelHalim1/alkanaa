@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
    @endphp

    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('flash-deals') }}">{{ translate('Flash Deals') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $flash_deal->title }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ $flash_deal->title }}</h1>
        </div>

        <div class="kn-flash-layout">
            <!-- Flash Deals Banner & Countdown -->
            <aside class="kn-flash-aside">
                <div class="kn-flash-banner">
                    <img src="{{ uploaded_asset($flash_deal->banner) }}" alt=""
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
                <div class="kn-flash-timer">
                    <p class="kn-flash-timer-label">{{ $t('ينتهي العرض خلال', 'Offer ends in') }}</p>
                    <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                </div>
            </aside>

            <!-- Flash Deals Products -->
            <div class="kn-listing-main">
                @if ($flash_deal->status == 1 && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
                    <div class="kn-listing-grid">
                        @foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product)
                            @php
                                $product = get_single_product($flash_deal_product->product_id);
                            @endphp
                            @if ($product != null && $product->published != 0)
                                @php
                                    $product_url = route('product', $product->slug);
                                    if ($product->auction_product == 1) {
                                        $product_url = route('auction-product', $product->slug);
                                    }
                                @endphp
                                <div class="kn-grid-item">
                                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="kn-empty">
                        <span class="kn-empty-icon" aria-hidden="true"><i class="las la-hourglass-end"></i></span>
                        <h2 class="kn-empty-title">{{ translate('This offer has been expired.') }}</h2>
                        <a href="{{ route('flash-deals') }}" class="kn-btn kn-btn-primary">{{ translate('Flash Deals') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
