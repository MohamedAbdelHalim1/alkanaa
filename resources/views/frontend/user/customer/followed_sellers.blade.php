@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Followed Sellers') }}</h1>
    </div>

    @if (count($followed_sellers) > 0)
        <div class="kn-sellers">
            @foreach ($followed_sellers as $key => $followed_seller)
                @if($followed_seller->shop !=null)
                    <div class="kn-seller" id="followed_seller_{{ $followed_seller->shop->id }}">
                        <!-- Shop logo -->
                        <a href="{{ route('shop.visit', $followed_seller->shop->slug) }}" class="kn-seller-logo" tabindex="-1" aria-hidden="true">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($followed_seller->shop->logo) }}"
                                alt=""
                                class="img-fit lazyload"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </a>
                        <!-- Shop name -->
                        <h2 class="kn-panel-title">
                            <a href="{{ route('shop.visit', $followed_seller->shop->slug) }}" class="text-reset hov-text-primary">{{ $followed_seller->shop->name }}</a>
                        </h2>
                        <!-- Shop Rating -->
                        <div class="rating rating-md rating-space">
                            {{ renderStarRating($followed_seller->shop->rating) }}
                            <span class="kn-list-meta">({{ $followed_seller->shop->num_of_reviews }}
                                {{ translate('reviews') }})</span>
                        </div>
                        <a href="{{ route("followed_seller.remove", ['id'=>$followed_seller->shop->id]) }}" class="kn-auth-inline-link fs-13">{{ translate('Unfollow This Seller') }}</a>
                        <!-- Visit Button -->
                        <a href="{{ route('shop.visit', $followed_seller->shop->slug) }}" class="kn-btn kn-btn-outline">
                            {{ translate('Visit Store') }}
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="kn-empty">
            <img src="{{ static_asset('assets/img/nothing.svg') }}" alt="">
            <p class="kn-empty-title">{{ translate("There isn't anything added yet") }}</p>
        </div>
    @endif
    <!-- Pagination -->
    <div class="aiz-pagination">
        {{ $followed_sellers->links() }}
    </div>
@endsection
