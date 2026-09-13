@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
    @endphp

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
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Flash Deals') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('Flash Deals') }}</h1>
        </div>

        <!-- Banner -->
        @if (get_setting('flash_deal_banner') != null || get_setting('flash_deal_banner_small') != null)
            <div class="kn-page-banner d-none d-md-block">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ uploaded_asset(get_setting('flash_deal_banner')) }}"
                    alt="{{ env('APP_NAME') }} promo" class="lazyload"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
            <div class="kn-page-banner d-md-none">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ get_setting('flash_deal_banner_small') != null ? uploaded_asset(get_setting('flash_deal_banner_small')) : uploaded_asset(get_setting('flash_deal_banner')) }}"
                    alt="{{ env('APP_NAME') }} promo" class="lazyload"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
        @endif

        <!-- All flash deals -->
        @if (count($all_flash_deals) > 0)
            <div class="kn-deals">
                @foreach ($all_flash_deals as $single)
                    <a href="{{ route('flash-deal-details', $single->slug) }}" class="kn-deal">
                        <span class="kn-deal-img">
                            <img src="{{ uploaded_asset($single->banner) }}" alt="" loading="lazy"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </span>
                        <span class="kn-deal-body">
                            <span class="kn-deal-title">{{ $single->title }}</span>
                            <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $single->end_date) }}"></div>
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="kn-empty">
                <span class="kn-empty-icon" aria-hidden="true"><i class="las la-bolt"></i></span>
                <h2 class="kn-empty-title">{{ $t('لا توجد عروض فلاش حاليًا', 'No flash deals right now') }}</h2>
                <a href="{{ route('categories.all') }}" class="kn-btn kn-btn-primary">{{ translate('All Categories') }}</a>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        AIZ.plugins.particles();
    </script>
@endsection
