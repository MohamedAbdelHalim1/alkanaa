@extends('frontend.layouts.app')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="Shop" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')

    @php
        $total = 0;
        $rating = 0;
        foreach ($shop->user->products as $key => $seller_product) {
            $total += $seller_product->reviews->where('status', 1)->count();
            $rating += $seller_product->reviews->where('status', 1)->sum('rating');
        }
    @endphp

    <div class="kn-wrap kn-page">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $shop->name }}</li>
            </ol>
        </nav>

        <div class="kn-shop-info">
            <!-- Shop Logo -->
            <a href="{{ route('shop.visit', $shop->slug) }}" class="kn-shop-info-logo" tabindex="-1" aria-hidden="true">
                <img class="lazyload"
                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                    data-src="{{ uploaded_asset($shop->logo) }}" alt=""
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </a>
            <div class="kn-shop-info-main">
                <!-- Shop Name & Verification Status -->
                <h1 class="kn-shop-info-name">
                    <a href="{{ route('shop.visit', $shop->slug) }}">{{ $shop->name }}</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5" aria-hidden="true">
                        <g transform="translate(-537.249 -1042.75)">
                            <path d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#d92d20"/>
                        </g>
                    </svg>
                </h1>
                <!-- Ratting -->
                <div class="rating rating-mr-2">
                    {{ renderStarRating($shop->rating) }}
                    <span class="kn-shop-stat-label">({{ $shop->num_of_reviews }} {{ translate('Reviews') }})</span>
                </div>
                <!-- Address -->
                @if ($shop->address)
                    <p class="kn-shop-address">{{ $shop->address }}</p>
                @endif
            </div>
            <div class="kn-shop-info-side">
                <!-- Member Since -->
                <div class="kn-shop-stat">
                    <span class="kn-shop-stat-label">{{ translate('Member Since') }}</span>
                    <span class="kn-shop-stat-value">{{ date('d M Y',strtotime($shop->created_at)) }}</span>
                </div>
            </div>
        </div>

        <div class="alert alert-warning kn-shop-notice" role="status">
            {{$shop->user->name}} {{ translate('has not been verified yet.')}}
        </div>
    </div>
@endsection
