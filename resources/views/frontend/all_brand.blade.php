@extends('frontend.layouts.app')

@section('content')
    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('All Brands') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('All Brands') }}</h1>
        </div>

        <!-- All Brands -->
        <div class="kn-brands">
            @foreach ($brands as $brand)
                <a href="{{ route('products.brand', $brand->slug) }}" class="kn-brand">
                    <span class="kn-brand-logo">
                        <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->getTranslation('name') }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </span>
                    <span class="kn-brand-name">{{ $brand->getTranslation('name') }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
