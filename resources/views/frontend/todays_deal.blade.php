@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $lang = get_system_language()->code;
        $todays_deal_banner = get_setting('todays_deal_banner', null, $lang);
        $todays_deal_banner_small = get_setting('todays_deal_banner_small', null, $lang);
    @endphp

    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Todays Deal') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('Todays Deal') }}</h1>
            <p class="kn-page-count">{{ $todays_deal_products->count() }} {{ $t('منتج', 'products') }}</p>
        </div>

        <!-- Banner -->
        @if ($todays_deal_banner != null || $todays_deal_banner_small != null)
            <div class="kn-page-banner d-none d-md-block">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ uploaded_asset($todays_deal_banner) }}"
                    alt="{{ env('APP_NAME') }} promo" class="lazyload"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
            <div class="kn-page-banner d-md-none">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ $todays_deal_banner_small != null ? uploaded_asset($todays_deal_banner_small) : uploaded_asset($todays_deal_banner) }}"
                    alt="{{ env('APP_NAME') }} promo" class="lazyload"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
        @endif

        <!-- Products Section -->
        @if ($todays_deal_products->count() > 0)
            <div class="kn-listing-grid">
                @foreach ($todays_deal_products as $key => $product)
                    <div class="kn-grid-item">
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                    </div>
                @endforeach
            </div>
        @else
            <div class="kn-empty">
                <span class="kn-empty-icon" aria-hidden="true"><i class="las la-tags"></i></span>
                <h2 class="kn-empty-title">{{ $t('لا توجد عروض اليوم', 'No deals today') }}</h2>
                <p class="kn-empty-text">{{ $t('تصفح أقسام المعدات أو عد لاحقًا لعروض جديدة.', 'Browse the equipment categories or check back soon for new deals.') }}</p>
                <a href="{{ route('categories.all') }}" class="kn-btn kn-btn-primary">{{ translate('All Categories') }}</a>
            </div>
        @endif
    </div>
@endsection
