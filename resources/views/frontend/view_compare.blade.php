@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $compareRows = collect(Session::has('compare') ? Session::get('compare') : [])
            ->map(fn ($item) => ['item' => $item, 'product' => get_single_product($item)])
            ->filter(fn ($row) => $row['product'] != null)
            ->values();
    @endphp

    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head kn-page-head-row">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ translate('Compare Products') }}</li>
                    </ol>
                </nav>
                <h1 class="kn-page-title">{{ translate('Compare Products') }}</h1>
            </div>
            <a href="{{ route('compare.reset') }}" class="kn-btn kn-btn-line">
                <i class="las la-redo-alt" aria-hidden="true"></i>
                {{ translate('Reset Compare List') }}
            </a>
        </div>

        @if ($compareRows->count() > 0)
            <div class="kn-compare-scroll" role="region" tabindex="0"
                aria-label="{{ translate('Compare Products') }}">
                <table class="table kn-compare-table">
                    <tbody>
                        <!-- Product Image -->
                        <tr>
                            <th scope="row">{{ translate('Image') }}</th>
                            @foreach ($compareRows as $row)
                                <td>
                                    <a href="{{ route('product', $row['product']->slug) }}" class="kn-plinth kn-compare-img" tabindex="-1" aria-hidden="true">
                                        <img loading="lazy" src="{{ uploaded_asset($row['product']->thumbnail_img) }}"
                                            alt="{{ translate('Product Image') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                        <!-- Product Name -->
                        <tr>
                            <th scope="row">{{ translate('Name') }}</th>
                            @foreach ($compareRows as $row)
                                <td>
                                    <a class="kn-compare-name" href="{{ route('product', $row['product']->slug) }}"
                                        title="{{ $row['product']->getTranslation('name') }}">
                                        {{ $row['product']->getTranslation('name') }}
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                        <!-- Price -->
                        <tr>
                            <th scope="row">{{ translate('Price') }}</th>
                            @foreach ($compareRows as $row)
                                @php $product = $row['product']; @endphp
                                <td class="kn-compare-price">
                                    @if (home_base_price($product) != home_discounted_base_price($product))
                                        <del>{{ home_base_price($product) }}</del>
                                    @endif
                                    <span>{{ home_discounted_base_price($product) }}</span>
                                </td>
                            @endforeach
                        </tr>
                        <!-- Category -->
                        <tr>
                            <th scope="row">{{ translate('Category') }}</th>
                            @foreach ($compareRows as $row)
                                <td>
                                    @if ($row['product']->main_category != null)
                                        {{ $row['product']->main_category->getTranslation('name') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <!-- Brand -->
                        <tr>
                            <th scope="row">{{ translate('Brand') }}</th>
                            @foreach ($compareRows as $row)
                                <td>
                                    @if ($row['product']->brand != null)
                                        {{ $row['product']->brand->getTranslation('name') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <!-- Add to cart -->
                        <tr>
                            <th scope="row"><span class="kn-sr-only">{{ translate('Add to cart') }}</span></th>
                            @foreach ($compareRows as $row)
                                <td>
                                    <button type="button" class="kn-btn kn-btn-primary"
                                        onclick="showAddToCartModal({{ $row['item'] }})">
                                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                                        {{ translate('Add to cart') }}
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="kn-empty">
                <span class="kn-empty-icon" aria-hidden="true"><i class="las la-sync"></i></span>
                <h2 class="kn-empty-title">{{ translate('Your comparison list is empty') }}</h2>
                <a href="{{ route('categories.all') }}" class="kn-btn kn-btn-primary">{{ translate('All Categories') }}</a>
            </div>
        @endif
    </div>

@endsection
