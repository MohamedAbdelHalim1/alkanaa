@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $topCategories = $categories->where('parent_id', 0);
    @endphp

    <div class="kn-wrap kn-page">
        <!-- Page header -->
        <div class="kn-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('All Categories') }}</li>
                </ol>
            </nav>
            <h1 class="kn-page-title">{{ translate('All Categories') }}</h1>
            <p class="kn-page-count">{{ $topCategories->count() }} {{ $t('قسم رئيسي', 'main categories') }}</p>
        </div>

        <!-- All Categories -->
        <div class="kn-catgrid">
            @foreach ($topCategories as $key => $category)
                @php
                    // The banner is a square product photo: prefer it, fall back to the icon.
                    $catImg = null;
                    if ($category->banner) {
                        $catImg = uploaded_asset($category->banner);
                    } elseif ($category->icon) {
                        $catImg = uploaded_asset($category->icon);
                    }
                    $children = $category->childrenCategories;
                @endphp
                <article class="kn-catcard">
                    <a href="{{ route('products.category', $category->slug) }}" class="kn-catcard-head">
                        <span class="kn-plinth kn-catcard-img">
                            @if ($catImg)
                                <img src="{{ $catImg }}" alt="" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            @else
                                <i class="fa-solid fa-kitchen-set" aria-hidden="true"></i>
                            @endif
                        </span>
                        <span class="kn-catcard-text">
                            <span class="kn-catcard-title">{{ $category->getTranslation('name') }}</span>
                            <span class="kn-catcard-meta">
                                @if ($children->count() > 0)
                                    {{ $children->count() }} {{ $t('قسم فرعي', 'subcategories') }}
                                @else
                                    {{ $t('تصفح المنتجات', 'Browse products') }}
                                @endif
                            </span>
                        </span>
                        <i class="las la-angle-right kn-catcard-arrow kn-flip" aria-hidden="true"></i>
                    </a>

                    @if ($children->count() > 0)
                        <ul class="kn-catcard-links">
                            @foreach ($children as $key => $child_category)
                                <li>
                                    <!-- Sub Category Name -->
                                    <a class="kn-catcard-link"
                                        href="{{ route('products.category', $child_category->slug) }}">
                                        {{ $child_category->getTranslation('name') }}
                                    </a>

                                    <!-- Sub-sub Categories -->
                                    @if ($child_category->childrenCategories->count() > 0)
                                        <ul
                                            class="kn-catcard-sub has-transition @if ($child_category->childrenCategories->count() > 5) less @endif">
                                            @foreach ($child_category->childrenCategories as $key => $second_level_category)
                                                <li>
                                                    <a href="{{ route('products.category', $second_level_category->slug) }}">
                                                        {{ $second_level_category->getTranslation('name') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if ($child_category->childrenCategories->count() > 5)
                                            <button type="button" class="show-hide-cetegoty kn-more-btn">{{ translate('More') }}
                                                <i class="las la-angle-down"></i></button>
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.show-hide-cetegoty').on('click', function() {
            var el = $(this).siblings('ul');
            if (el.hasClass('less')) {
                el.removeClass('less');
                $(this).html('{{ translate('Less') }} <i class="las la-angle-up"></i>');
            } else {
                el.addClass('less');
                $(this).html('{{ translate('More') }} <i class="las la-angle-down"></i>');
            }
        });
    </script>
@endsection
