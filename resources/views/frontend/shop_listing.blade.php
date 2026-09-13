@extends('frontend.layouts.app')

@section('content')
    <div class="kn-wrap kn-page">
        {{-- Kept for AIZ.plugins.particles(); hidden by listing.css --}}
        <div id="particles-js"></div>

        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('All Sellers') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('All Sellers') }}</h1>
        </div>

        <!-- All Sellers -->
        <div class="kn-shops">
            @foreach ($shops as $key => $shop)
                @if ($shop->user != null)
                    <article class="kn-shop">
                        <!-- Shop logo & Verification Status -->
                        <div class="kn-shop-logo-wrap">
                            <a href="{{ route('shop.visit', $shop->slug) }}" class="kn-shop-logo" tabindex="-1" aria-hidden="true">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($shop->logo) }}"
                                    alt="{{ $shop->name }}"
                                    class="lazyload"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                            <span class="kn-shop-badge">
                                @if ($shop->verification_status == 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24" viewBox="0 0 24.001 24" role="img" aria-label="{{ translate('Verified') }}">
                                        <g transform="translate(-480 -345)">
                                            <circle cx="12" cy="12" r="12" transform="translate(480 345)" fill="#fff"/>
                                            <g transform="translate(480 345)">
                                            <path d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z" transform="translate(0 0)" fill="#2346d1"/>
                                            </g>
                                        </g>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24" viewBox="0 0 24.001 24" aria-hidden="true">
                                        <g transform="translate(-480 -345)">
                                            <circle cx="12" cy="12" r="12" transform="translate(480 345)" fill="#fff"/>
                                            <g transform="translate(480 345)">
                                            <path d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z" transform="translate(0 0)" fill="#d92d20"/>
                                            </g>
                                        </g>
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <!-- Shop name -->
                        <h2 class="kn-shop-name">
                            <a href="{{ route('shop.visit', $shop->slug) }}">{{ $shop->name }}</a>
                        </h2>
                        <!-- Shop Rating -->
                        <div class="rating rating-mr-2 kn-shop-rating">
                            {{ renderStarRating($shop->rating) }}
                            <span>({{ $shop->num_of_reviews }} {{ translate('Reviews') }})</span>
                        </div>
                        <!-- Visit Button -->
                        <a href="{{ route('shop.visit', $shop->slug) }}" class="kn-btn kn-btn-line">
                            {{ translate('Visit Store') }}
                        </a>
                    </article>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="kn-pagination">
            <div class="aiz-pagination aiz-pagination-center">
                {{ $shops->links() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        AIZ.plugins.particles();
    </script>
@endsection
