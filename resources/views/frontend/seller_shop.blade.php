@extends('frontend.layouts.app')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')
    <!--  Top Menu -->
    <nav class="kn-shop-tabs-bar" aria-label="{{ $shop->name }}">
        <div class="kn-wrap">
            <div class="kn-shop-tabs">
                <a class="kn-shop-tab @if(!isset($type)) is-active @endif"
                    href="{{ route('shop.visit', $shop->slug) }}" @if(!isset($type)) aria-current="page" @endif>{{ translate('Store Home')}}</a>
                <a class="kn-shop-tab @if(isset($type) && $type == 'top-selling') is-active @endif"
                    href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'top-selling']) }}" @if(isset($type) && $type == 'top-selling') aria-current="page" @endif>{{ translate('Top Selling')}}</a>
                <a class="kn-shop-tab @if(isset($type) && $type == 'cupons') is-active @endif"
                    href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'cupons']) }}" @if(isset($type) && $type == 'cupons') aria-current="page" @endif>{{ translate('Coupons')}}</a>
                <a class="kn-shop-tab @if(isset($type) && $type == 'all-products') is-active @endif"
                    href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all-products']) }}" @if(isset($type) && $type == 'all-products') aria-current="page" @endif>{{ translate('All Products')}}</a>
                @if(addon_is_activated('preorder'))
                    <a class="kn-shop-tab @if(isset($type) && $type == 'all-preorder-products') is-active @endif"
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all-preorder-products']) }}" @if(isset($type) && $type == 'all-preorder-products') aria-current="page" @endif>{{ translate('All Preorder Products')}}</a>
                @endif
            </div>
        </div>
    </nav>

    @php
        $followed_sellers = [];
        if (Auth::check()) {
            $followed_sellers = get_followed_sellers();
        }
    @endphp

    <div class="kn-wrap kn-page">

        @if (!isset($type) || $type == 'top-selling' || $type == 'cupons')
            @if ($shop->top_banner_image)
                <!-- Top Banner -->
                <a href="{{ $shop->top_banner_link }}" class="kn-shop-banner">
                    <img class="lazyload"
                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                        data-src="{{ uploaded_asset($shop->top_banner_image) }}" alt="{{ env('APP_NAME') }} offer">
                </a>
            @endif
        @endif

        <!-- Seller Info -->
        <section class="kn-shop-info">
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
                    @if ($shop->verification_status == 1)
                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5" role="img" aria-label="{{ translate('Verified') }}">
                            <g transform="translate(-537.249 -1042.75)">
                                <path d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#2346d1"/>
                            </g>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5" aria-hidden="true">
                            <g transform="translate(-537.249 -1042.75)">
                                <path d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#d92d20"/>
                            </g>
                        </svg>
                    @endif
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
                <!-- Social Links -->
                @if ($shop->facebook || $shop->instagram || $shop->google || $shop->twitter || $shop->youtube)
                    <div class="kn-shop-stat">
                        <span class="kn-shop-stat-label">{{ translate('Social Media') }}</span>
                        <ul class="social-md colored-light list-inline mb-0">
                            @if ($shop->facebook)
                            <li class="list-inline-item">
                                <a href="{{ $shop->facebook }}" class="facebook" target="_blank" rel="noopener" aria-label="Facebook">
                                    <i class="lab la-facebook-f"></i>
                                </a>
                            </li>
                            @endif
                            @if ($shop->instagram)
                            <li class="list-inline-item">
                                <a href="{{ $shop->instagram }}" class="instagram" target="_blank" rel="noopener" aria-label="Instagram">
                                    <i class="lab la-instagram"></i>
                                </a>
                            </li>
                            @endif
                            @if ($shop->google)
                            <li class="list-inline-item">
                                <a href="{{ $shop->google }}" class="google" target="_blank" rel="noopener" aria-label="Google">
                                    <i class="lab la-google"></i>
                                </a>
                            </li>
                            @endif
                            @if ($shop->twitter)
                            <li class="list-inline-item">
                                <a href="{{ $shop->twitter }}" class="twitter" target="_blank" rel="noopener" aria-label="Twitter">
                                    <i class="lab la-twitter"></i>
                                </a>
                            </li>
                            @endif
                            @if ($shop->youtube)
                            <li class="list-inline-item">
                                <a href="{{ $shop->youtube }}" class="youtube" target="_blank" rel="noopener" aria-label="YouTube">
                                    <i class="lab la-youtube"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                @endif
                <!-- follow -->
                @php $shopFollowers = count($shop->followers) + $shop->custom_followers; @endphp
                @if(in_array($shop->id, $followed_sellers))
                    <a href="{{ route("followed_seller.remove", ['id'=>$shop->id]) }}" data-toggle="tooltip" data-title="{{ translate('Unfollow Seller') }}" data-placement="top"
                        class="btn btn-success kn-shop-follow follow-btn followed">
                        <i class="las la-check" aria-hidden="true"></i>
                        <span>{{ translate('Followed') }}</span> ({{ $shopFollowers }})
                    </a>
                @else
                    <a href="{{ route("followed_seller.store", ['id'=>$shop->id]) }}"
                        class="btn btn-primary kn-shop-follow follow-btn">
                        <i class="las la-plus" aria-hidden="true"></i>
                        <span>{{ translate('Follow Seller') }}</span> ({{ $shopFollowers }})
                    </a>
                @endif
            </div>
        </section>

        @if (!isset($type))

            <!-- Featured Products -->
            @php
                $feature_products = $shop->user->products->where('published', 1)->where('approved', 1)->where('seller_featured', 1);
            @endphp
            @if (count($feature_products) > 0)
                <section class="kn-section" id="section_featured">
                    <div class="kn-section-head">
                        <h2 class="kn-section-title">{{ translate('Featured Products') }}</h2>
                        <div class="kn-slide-nav">
                            <button type="button" class="arrow-prev slide-arrow kn-slide-btn" onclick="clickToSlide('slick-prev','section_featured')" aria-label="{{ translate('Previous') }}"><i class="las la-angle-left" aria-hidden="true"></i></button>
                            <button type="button" class="arrow-next slide-arrow kn-slide-btn" onclick="clickToSlide('slick-next','section_featured')" aria-label="{{ translate('Next') }}"><i class="las la-angle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="aiz-carousel arrow-none" data-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-autoplay='true' data-infinute="true">
                        @foreach ($feature_products as $key => $product)
                            <div class="carousel-box kn-carousel-cell">
                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Banner Slider -->
            @if ($shop->slider_images != null)
                <section class="kn-section">
                    <div class="aiz-carousel mobile-img-auto-height" data-arrows="true" data-dots="false" data-autoplay="true">
                        @php
                            $shop_slider_images = get_slider_images(json_decode($shop->slider_images, true));
                            $shop_slider_links = json_decode($shop->slider_links, true);
                        @endphp
                        @foreach ($shop_slider_images as $key => $slider)
                            <div class="carousel-box w-100">
                                <a href="{{ isset($shop_slider_links[$key]) ? $shop_slider_links[$key] : '' }}" class="kn-shop-banner">
                                    <img class="lazyload"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                        alt="{{ $key }} offer">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Coupons -->
            @php
                $coupons = get_coupons($shop->user->id);
            @endphp
            @if (count($coupons)>0)
                <section class="kn-section" id="section_coupons">
                    <div class="kn-section-head">
                        <h2 class="kn-section-title">{{ translate('Coupons') }}</h2>
                        <div class="kn-slide-nav">
                            <button type="button" class="arrow-prev slide-arrow link-disable kn-slide-btn" onclick="clickToSlide('slick-prev','section_coupons')" aria-label="{{ translate('Previous') }}"><i class="las la-angle-left" aria-hidden="true"></i></button>
                            <a class="kn-link" href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'cupons']) }}">{{ translate('View All') }}</a>
                            <button type="button" class="arrow-next slide-arrow kn-slide-btn" onclick="clickToSlide('slick-next','section_coupons')" aria-label="{{ translate('Next') }}"><i class="las la-angle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="3" data-lg-items="2" data-sm-items="1" data-arrows='true' data-infinite='false'>
                        @foreach ($coupons->take(10) as $key => $coupon)
                            <div class="carousel-box">
                                @include('frontend.partials.coupon_box',['coupon' => $coupon])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Banner full width 1 -->
            @if ($shop->banner_full_width_1_images)
                @php
                    $shop_banner_full_width_1_images = get_slider_images(json_decode($shop->banner_full_width_1_images, true));
                    $shop_banner_full_width_1_links = json_decode($shop->banner_full_width_1_links, true);
                @endphp
                <section class="kn-section">
                    @foreach ($shop_banner_full_width_1_images as $key => $banner)
                        <a href="{{ isset($shop_banner_full_width_1_links[$key]) ? $shop_banner_full_width_1_links[$key] : '' }}" class="kn-shop-banner">
                            <img class="lazyload"
                                src="{{ $banner ? my_asset($banner->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                alt="{{ env('APP_NAME') }} banner">
                        </a>
                    @endforeach
                </section>
            @endif

            <!-- Banner half width -->
            @if($shop->banners_half_width_images)
                @php
                    $shop_banners_half_width_images = get_slider_images(json_decode($shop->banners_half_width_images, true));
                    $shop_banners_half_width_links = json_decode($shop->banners_half_width_links, true);
                @endphp
                <section class="kn-section">
                    <div class="row gutters-16">
                        @foreach ($shop_banners_half_width_images as $key => $banner)
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ isset($shop_banners_half_width_links[$key]) ? $shop_banners_half_width_links[$key] : '' }}" class="kn-shop-banner">
                                <img class="lazyload"
                                    src="{{ $banner ? my_asset($banner->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                    alt="{{ env('APP_NAME') }} banner">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif

        @endif

        <section class="kn-section" id="section_types">
            @php
                $sectionTitle = null;
                if (!isset($type)) {
                    $sectionTitle = translate('New Arrival Products');
                } elseif ($type == 'top-selling') {
                    $sectionTitle = translate('Top Selling');
                } elseif ($type == 'cupons') {
                    $sectionTitle = translate('All Cupons');
                }
            @endphp

            @if ($sectionTitle)
                <!-- Top Section -->
                <div class="kn-section-head">
                    <h2 class="kn-section-title">{{ $sectionTitle }}</h2>
                    @if (!isset($type))
                        <div class="kn-slide-nav">
                            <button type="button" class="arrow-prev slide-arrow link-disable kn-slide-btn" onclick="clickToSlide('slick-prev','section_types')" aria-label="{{ translate('Previous') }}"><i class="las la-angle-left" aria-hidden="true"></i></button>
                            <button type="button" class="arrow-next slide-arrow kn-slide-btn" onclick="clickToSlide('slick-next','section_types')" aria-label="{{ translate('Next') }}"><i class="las la-angle-right" aria-hidden="true"></i></button>
                        </div>
                    @endif
                </div>
            @endif

            @php
                if (!isset($type)){
                    $products = get_seller_products($shop->user->id);
                }
                elseif ($type == 'top-selling'){
                    $products = get_shop_best_selling_products($shop->user->id);
                }
                elseif ($type == 'cupons'){
                    $coupons = get_coupons($shop->user->id , 24);
                }
            @endphp

            @if (!isset($type))
                <!-- New Arrival Products Section -->
                <div class="aiz-carousel arrow-none" data-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false'>
                    @foreach ($products as $key => $product)
                        <div class="carousel-box kn-carousel-cell">
                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>

                <!-- Banner full width 2 -->
                @if ($shop->banner_full_width_2_images)
                    @php
                        $shop_banner_full_width_2_images = get_slider_images(json_decode($shop->banner_full_width_2_images, true));
                        $shop_banner_full_width_2_links = json_decode($shop->banner_full_width_2_links, true);
                    @endphp
                    <div class="kn-section">
                        @foreach ($shop_banner_full_width_2_images as $key => $banner)
                            <a href="{{ isset($shop_banner_full_width_2_links[$key]) ? $shop_banner_full_width_2_links[$key] : '' }}" class="kn-shop-banner">
                                <img class="lazyload"
                                    src="{{ $banner ? my_asset($banner->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                    alt="{{ env('APP_NAME') }} banner">
                            </a>
                        @endforeach
                    </div>
                @endif

            @elseif ($type == 'cupons')
                <!-- All Coupons Section -->
                <div class="row gutters-16 row-cols-xl-3 row-cols-md-2 row-cols-1">
                    @foreach ($coupons as $key => $coupon)
                        <div class="col mb-4">
                            @include('frontend.partials.coupon_box',['coupon' => $coupon])
                        </div>
                    @endforeach
                </div>
                <div class="kn-pagination">
                    <div class="aiz-pagination">
                        {{ $coupons->links() }}
                    </div>
                </div>

            @elseif ($type == 'all-products')
                <!-- All Products Section -->
                <form class="" id="search-form" action="" method="GET">
                    <div class="kn-listing-layout">
                        <!-- Sidebar Filters -->
                        <aside class="kn-listing-aside" aria-label="{{ translate('Filters') }}">
                            <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                                <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                                <div class="collapse-sidebar c-scrollbar-light kn-filter-panel">
                                    <div class="kn-filter-drawer-head d-xl-none">
                                        <h2 class="kn-filter-drawer-title">{{ translate('Filters') }}</h2>
                                        <button type="button" class="kn-filter-close filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" aria-label="{{ translate('Close') }}">
                                            <i class="las la-times" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <!-- Categories -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">
                                            <a href="#collapse_1" class="dropdown-toggle filter-section d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('Categories')}}
                                            </a>
                                        </h3>
                                        <div class="collapse show kn-filter-options" id="collapse_1">
                                            @foreach (get_categories_by_products($shop->user->id) as $category)
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="checkbox"
                                                        name="selected_categories[]"
                                                        value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ $category->getTranslation('name') }}</span>
                                                </label>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Price range -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">{{ translate('Price range')}}</h3>
                                        <div class="kn-filter-options">
                                            <div class="aiz-range-slider">
                                                <div
                                                    id="input-slider-range"
                                                    data-range-value-min="@if(get_products_count($shop->user->id) < 1) 0 @else {{ get_product_min_unit_price($shop->user->id) }} @endif"
                                                    data-range-value-max="@if(get_products_count($shop->user->id) < 1) 0 @else {{ get_product_max_unit_price($shop->user->id) }} @endif"
                                                ></div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            @if ($min_price != null)
                                                                data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else
                                                                data-range-value-low="0"
                                                            @endif
                                                            id="input-slider-range-value-low"
                                                        ></span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            @if ($max_price != null)
                                                                data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else
                                                                data-range-value-high="0"
                                                            @endif
                                                            id="input-slider-range-value-high"
                                                        ></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Hidden Items -->
                                        <input type="hidden" name="min_price" value="">
                                        <input type="hidden" name="max_price" value="">
                                    </div>

                                    <!-- Ratings -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">
                                            <a href="#collapse_2" class="dropdown-toggle filter-section d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('Ratings')}}
                                            </a>
                                        </h3>
                                        <div class="collapse show kn-filter-options" id="collapse_2">
                                            @foreach ([5, 4, 3, 2, 1] as $ratingValue)
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="{{ $ratingValue }}" @if ($rating==$ratingValue) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-2">{{ renderStarRating($ratingValue) }}</span>
                                                    @if ($ratingValue < 5)
                                                        <span class="fs-14 fw-400 text-dark">{{ translate('And Up')}}</span>
                                                    @endif
                                                </label>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Brands -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">
                                            <a href="#collapse_3" class="dropdown-toggle filter-section d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('Brands')}}
                                            </a>
                                        </h3>
                                        <div class="collapse show" id="collapse_3">
                                            <div class="row gutters-10">
                                                @foreach (get_brands_by_products($shop->user->id) as $key => $brand)
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="{{ $brand->slug }}" type="radio" onchange="filter()"
                                                                name="brand" @isset($brand_id) @if ($brand_id == $brand->id) checked @endif @endisset>
                                                            <span class="d-block aiz-megabox-elem p-2 border-transparent hov-border-primary">
                                                                <img src="{{ uploaded_asset($brand->logo) }}"
                                                                    class="img-fit mb-2" alt="{{ $brand->getTranslation('name') }}">
                                                                <span class="d-block text-center fs-13">{{ $brand->getTranslation('name') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </aside>

                        <!-- Contents -->
                        <div class="kn-listing-main">
                            <!-- Top Filters -->
                            <div class="kn-listing-toolbar">
                                <h2 class="kn-listing-toolbar-title">{{ translate('All Products') }}</h2>
                                <button type="button" class="kn-listing-filter-btn d-xl-none" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                    <i class="las la-sliders-h" aria-hidden="true"></i>
                                    <span>{{ translate('Filters') }}</span>
                                </button>
                                <div class="kn-listing-sort">
                                    <label for="kn-shop-sort" class="kn-listing-sort-label">{{ translate('Sort by') }}</label>
                                    <select id="kn-shop-sort" class="form-control form-control-sm aiz-selectpicker" name="sort_by" onchange="filter()">
                                        <option value="">{{ translate('Sort by')}}</option>
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Products -->
                            @if ($products->count() > 0)
                                <div class="kn-listing-grid">
                                    @foreach ($products as $key => $product)
                                        <div class="kn-grid-item">
                                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                                        </div>
                                    @endforeach
                                </div>
                                <div class="kn-pagination">
                                    <div class="aiz-pagination">
                                        {{ $products->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="kn-empty">
                                    <span class="kn-empty-icon" aria-hidden="true"><i class="las la-search"></i></span>
                                    <h3 class="kn-empty-title">{{ translate('No products found') }}</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>

            @elseif ($type == 'all-preorder-products')
                <!-- All preorder Products Section -->
                <form class="" id="search-form" action="" method="GET">
                    <div class="kn-listing-layout">
                        <!-- Sidebar Filters -->
                        <aside class="kn-listing-aside" aria-label="{{ translate('Filters') }}">
                            <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                                <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                                <div class="collapse-sidebar c-scrollbar-light kn-filter-panel">
                                    <div class="kn-filter-drawer-head d-xl-none">
                                        <h2 class="kn-filter-drawer-title">{{ translate('Filters') }}</h2>
                                        <button type="button" class="kn-filter-close filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" aria-label="{{ translate('Close') }}">
                                            <i class="las la-times" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <!-- Categories -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">
                                            <a href="#collapse_1" class="dropdown-toggle filter-section d-flex align-items-center justify-content-between" data-toggle="collapse">
                                                {{ translate('Categories')}}
                                            </a>
                                        </h3>
                                        <div class="collapse show kn-filter-options" id="collapse_1">
                                            @php
                                            $product_categories = $type == 'all-preorder-products' ? get_categories_by_preorder_products($shop->user->id) : get_categories_by_products($shop->user->id);
                                            @endphp
                                            @foreach ($product_categories as $category)
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="checkbox"
                                                        name="selected_categories[]"
                                                        value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ $category->getTranslation('name') }}</span>
                                                </label>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Availability -->
                                    <div class="kn-filter-block">
                                        <h3 class="kn-filter-title">
                                            <a href="#" class="dropdown-toggle filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse" data-target="#collapse_availability_filter" style="white-space: normal;">
                                                {{ translate('Filter by Availability') }}
                                            </a>
                                        </h3>
                                        @php
                                            $show = $is_available !== null ? 'show' : '';
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_availability_filter">
                                            <div class="kn-filter-options aiz-checkbox-list">
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="is_available"
                                                        value="1" @if ($is_available == 1) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('Available Now') }}</span>
                                                </label>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="is_available"
                                                        value="0" @if ($is_available === '0') checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('Upcoming') }}</span>
                                                </label>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="is_available"
                                                        value=""
                                                        @if ($is_available === null) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('All') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </aside>

                        <!-- Contents -->
                        <div class="kn-listing-main">
                            <!-- Top Filters -->
                            <div class="kn-listing-toolbar">
                                <h2 class="kn-listing-toolbar-title">{{ translate('All Preorder Products') }}</h2>
                                <button type="button" class="kn-listing-filter-btn d-xl-none" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                    <i class="las la-sliders-h" aria-hidden="true"></i>
                                    <span>{{ translate('Filters') }}</span>
                                </button>
                                <div class="kn-listing-sort">
                                    <label for="kn-shop-preorder-sort" class="kn-listing-sort-label">{{ translate('Sort by') }}</label>
                                    <select id="kn-shop-preorder-sort" class="form-control form-control-sm aiz-selectpicker" name="sort_by" onchange="filter()">
                                        <option value="">{{ translate('Sort by')}}</option>
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Products -->
                            <div class="kn-listing-grid">
                                @foreach ($products as $key => $product)
                                    <div class="kn-grid-item">
                                        @include('preorder.frontend.product_box3',['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                            <div class="kn-pagination">
                                <div class="aiz-pagination">
                                    {{ $products->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @else

                <!-- Top Selling Products Section -->
                <div class="kn-listing-grid">
                    @foreach ($products as $key => $product)
                        <div class="kn-grid-item">
                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
                <div class="kn-pagination">
                    <div class="aiz-pagination">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }

        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
