@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $productTotal = method_exists($products, 'total') ? $products->total() : $products->count();
    @endphp

    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Inhouse products') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('Inhouse products') }}</h1>
            <p class="kn-page-count">{{ $productTotal }} {{ $t('منتج', 'products') }}</p>
        </div>

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
                <span class="kn-empty-icon" aria-hidden="true"><i class="las la-box"></i></span>
                <h2 class="kn-empty-title">{{ translate('No products found') }}</h2>
                <a href="{{ route('categories.all') }}" class="kn-btn kn-btn-primary">{{ translate('All Categories') }}</a>
            </div>
        @endif
    </div>

@endsection
