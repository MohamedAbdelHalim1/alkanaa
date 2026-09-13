@extends('frontend.layouts.app')

@if (!empty($category))
    @php
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
    @endphp
@elseif (!empty($brand_id))
    @php
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif


@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn ($ar, $en) => $isAr ? $ar : $en;
        $isPreorder = isset($product_type) && $product_type == 'preorder_product';

        // Resolve the current category up front: the sidebar loops below reuse $category.
        $currentCategory = null;
        if (isset($category_id) && $category_id) {
            $currentCategory = (!empty($category) && $category->id == $category_id)
                ? $category
                : \App\Models\Category::find($category_id);
        }
        $parentCategory = $currentCategory && $currentCategory->parent_id != 0
            ? get_single_category($currentCategory->parent_id)
            : null;
        $childCategories = $currentCategory && $currentCategory->childrenCategories
            ? $currentCategory->childrenCategories
            : collect();
        $categoryRoute = $isPreorder ? 'preorder.category' : 'products.category';

        // The controller only loads top categories on /search; category pages need them too.
        $sidebarCategories = collect($categories);
        if (!$isPreorder && $sidebarCategories->isEmpty()) {
            $sidebarCategories = \App\Models\Category::with('childrenCategories')
                ->where('level', 0)
                ->orderBy('order_level', 'desc')
                ->get();
        }
        $openCategoryIds = array_filter([$currentCategory?->id, $currentCategory?->parent_id]);

        $brandForTitle = !empty($brand_id) ? get_single_brand($brand_id) : null;
        if ($currentCategory) {
            $pageTitle = $currentCategory->getTranslation('name');
        } elseif ($query) {
            $pageTitle = $t('نتائج البحث عن', 'Search results for') . ' "' . $query . '"';
        } elseif ($brandForTitle) {
            $pageTitle = $brandForTitle->getTranslation('name');
        } else {
            $pageTitle = $t('كل المنتجات', 'All products');
        }
        $productTotal = method_exists($products, 'total') ? $products->total() : $products->count();
    @endphp

    <section class="kn-wrap kn-page kn-listing">
        <form class="" id="search-form" action="" method="GET">

            <!-- Page header -->
            <div class="kn-page-head">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        @if (!isset($category_id))
                            <li class="breadcrumb-item active" aria-current="page">{{ translate('All Categories') }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                            </li>
                            @if ($parentCategory)
                                <li class="breadcrumb-item">
                                    <a href="{{ route($categoryRoute, $parentCategory->slug) }}">{{ $parentCategory->getTranslation('name') }}</a>
                                </li>
                            @endif
                            @if ($currentCategory)
                                <li class="breadcrumb-item active" aria-current="page">{{ $currentCategory->getTranslation('name') }}</li>
                            @endif
                        @endif
                    </ol>
                </nav>
                <h1 class="kn-page-title">{{ $pageTitle }}</h1>
                <p class="kn-page-count">{{ $productTotal }} {{ $t('منتج', 'products') }}</p>
            </div>

            <div class="kn-listing-layout">

                <!-- Sidebar (drawer below 1200px) -->
                <aside class="kn-listing-aside" aria-label="{{ translate('Filters') }}">
                    <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                        <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                            data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                        <div class="collapse-sidebar c-scrollbar-light kn-filter-panel">
                            <div class="kn-filter-drawer-head d-xl-none">
                                <h2 class="kn-filter-drawer-title">{{ translate('Filters') }}</h2>
                                <button type="button" class="kn-filter-close filter-sidebar-thumb"
                                    data-toggle="class-toggle" data-target=".aiz-filter-sidebar"
                                    aria-label="{{ translate('Close') }}">
                                    <i class="las la-times" aria-hidden="true"></i>
                                </button>
                            </div>

                            @if ($isPreorder)
                                <!-- Categories -->
                                <div class="kn-filter-block">
                                    <h2 class="kn-filter-title">
                                        <a href="#collapse_1"
                                            class="dropdown-toggle filter-section d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">
                                            {{ translate('Categories') }}
                                        </a>
                                    </h2>
                                    <div class="collapse show" id="collapse_1">
                                        <ul class="kn-filter-list">
                                            @if (!isset($category_id))
                                                @foreach ($categories as $category)
                                                    <li>
                                                        <a class="kn-filter-link"
                                                            href="{{ route('preorder.category', $category?->slug) }}">
                                                            {{ $category->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li>
                                                    <a class="kn-filter-link is-strong" href="{{ route('search') }}">
                                                        <i class="las la-angle-left kn-flip" aria-hidden="true"></i>
                                                        {{ translate('All Categories') }}
                                                    </a>
                                                </li>

                                                @if ($category->parent_id != 0)
                                                    <li>
                                                        <a class="kn-filter-link is-strong"
                                                            href="{{ route('preorder.category', get_single_category($category->parent_id)->slug) }}">
                                                            <i class="las la-angle-left kn-flip" aria-hidden="true"></i>
                                                            {{ get_single_category($category->parent_id)->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="kn-filter-link is-strong"
                                                        href="{{ route('preorder.category', $category?->slug) }}">
                                                        <i class="las la-angle-left kn-flip" aria-hidden="true"></i>
                                                        {{ $category->getTranslation('name') }}
                                                    </a>
                                                </li>
                                                @foreach ($category->childrenCategories as $key => $immediate_children_category)
                                                    <li class="kn-filter-sub">
                                                        <a class="kn-filter-link"
                                                            href="{{ route('preorder.category', $immediate_children_category?->slug) }}">
                                                            {{ $immediate_children_category->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!-- Availability -->
                                <div class="kn-filter-block">
                                    <h2 class="kn-filter-title">
                                        <a href="#"
                                            class="dropdown-toggle filter-section collapsed d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_availability_filter"
                                            style="white-space: normal;">
                                            {{ translate('Filter by Availability') }}
                                        </a>
                                    </h2>
                                    @php
                                        $show = $is_available !== null ? 'show' : '';
                                    @endphp
                                    <div class="collapse {{ $show }}" id="collapse_availability_filter">
                                        <div class="kn-filter-options aiz-checkbox-list">
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="1"
                                                    @if ($is_available == 1) checked @endif
                                                    onchange="filter()">
                                                <span class="aiz-square-check"></span>
                                                <span class="fs-14 fw-400 text-dark">{{ translate('Available Now') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="0"
                                                    @if ($is_available === '0') checked @endif
                                                    onchange="filter()">
                                                <span class="aiz-square-check"></span>
                                                <span class="fs-14 fw-400 text-dark">{{ translate('Upcoming') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value=""
                                                    @if ($is_available === null) checked @endif
                                                    onchange="filter()">
                                                <span class="aiz-square-check"></span>
                                                <span class="fs-14 fw-400 text-dark">{{ translate('All') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Categories -->
                                <div class="kn-filter-block">
                                    <h2 class="kn-filter-title">{{ translate('Categories') }}</h2>
                                    <div class="accordion kn-cat-accordion" id="categoryAccordion">
                                        @foreach ($sidebarCategories->where('featured', 1) as $category)
                                            @php $isOpen = in_array($category->id, $openCategoryIds); @endphp
                                            <div class="accordion-item kn-acc-item" x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }">
                                                <h3 class="accordion-header" id="heading-{{ $category->id }}">
                                                    <button class="accordion-button kn-acc-btn @unless ($isOpen) collapsed @endunless"
                                                        :class="{ 'collapsed': !open }"
                                                        type="button" @click="open = !open"
                                                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                                        :aria-expanded="open.toString()"
                                                        aria-controls="collapse-{{ $category->id }}">
                                                        <span class="kn-acc-name">{{ $category->getTranslation('name') }}</span>
                                                        <i class="las la-angle-down kn-acc-chev" aria-hidden="true"></i>
                                                    </button>
                                                </h3>
                                                <div id="collapse-{{ $category->id }}"
                                                    x-show="open" x-collapse x-cloak
                                                    class="accordion-collapse"
                                                    aria-labelledby="heading-{{ $category->id }}">
                                                    <ul class="kn-acc-links">
                                                        <li>
                                                            <a href="{{ route('products.category', $category->slug) }}"
                                                                class="kn-acc-link is-all {{ $currentCategory && $currentCategory->id == $category->id ? 'is-active' : '' }}">
                                                                {{ $t('كل منتجات القسم', 'All in this category') }}
                                                            </a>
                                                        </li>
                                                        @if ($category->childrenCategories && $category->childrenCategories->count())
                                                            @foreach ($category->childrenCategories as $child)
                                                                @php $isCurrentChild = $currentCategory && $currentCategory->id == $child->id; @endphp
                                                                <li>
                                                                    <a href="{{ route('products.category', $child->slug) }}"
                                                                        class="kn-acc-link {{ $isCurrentChild ? 'is-active' : '' }}"
                                                                        @if ($isCurrentChild) aria-current="page" @endif>
                                                                        {{ $child->getTranslation('name') }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li class="kn-acc-empty">{{ translate('No Subcategories') }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>

                <!-- Contents -->
                <div class="kn-listing-main">

                    @if (addon_is_activated('preorder') && Route::currentRouteName() == 'search')
                        <div class="product-tab kn-listing-types">
                            @php
                                $activeClasses = 'bg-soft-dark mr-2 my-2 text-white';
                                $inActiveClasses = 'preorder-border-dashed m-2 text-muted  fw-600';
                            @endphp
                            <div class="aiz-radio-inline">
                                <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                    data-title="{{ translate('General Products') }}">
                                    <input type="radio" name="product_type" value="general_product"
                                        onchange="filter()" @if (isset($product_type) && $product_type == 'general_product') checked @endif>
                                    <span
                                        class="badge badge-inline fs-12 p-3 rounded-3 {{ $product_type == 'general_product' ? $activeClasses : $inActiveClasses }}">
                                        {{ translate('General Products') }}
                                    </span>
                                </label>
                                <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                    data-title="{{ translate('Preorder Products') }}">
                                    <input type="radio" name="product_type" value="preorder_product"
                                        onchange="filter()" @if (isset($product_type) && $product_type == 'preorder_product') checked @endif>
                                    <span
                                        class="badge badge-inline fs-12 p-3 rounded-3 {{ $product_type == 'preorder_product' ? $activeClasses : $inActiveClasses }}">
                                        {{ translate('Preorder Products') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- Toolbar -->
                    <div class="kn-listing-toolbar">
                        <input type="hidden" name="keyword" value="{{ $query }}">

                        <button type="button" class="kn-listing-filter-btn d-xl-none" data-toggle="class-toggle"
                            data-target=".aiz-filter-sidebar">
                            <i class="las la-sliders-h" aria-hidden="true"></i>
                            <span>{{ translate('Filters') }}</span>
                        </button>

                        <div class="kn-listing-sort">
                            <label for="kn-sort-by" class="kn-listing-sort-label">{{ translate('Sort by') }}</label>
                            <select id="kn-sort-by" class="form-control form-control-sm aiz-selectpicker" name="sort_by"
                                onchange="filter()">
                                <option value="">{{ translate('Sort by') }}</option>
                                <option value="newest"
                                    @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                    {{ translate('Newest') }}</option>
                                <option value="oldest"
                                    @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                    {{ translate('Oldest') }}</option>
                                <option value="price-asc"
                                    @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                    {{ translate('Price low to high') }}</option>
                                <option value="price-desc"
                                    @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                    {{ translate('Price high to low') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Child categories -->
                    @if ($childCategories->count() > 0)
                        <nav class="kn-chips" aria-label="{{ $t('الأقسام الفرعية', 'Subcategories') }}">
                            @foreach ($childCategories as $childCategory)
                                <a href="{{ route($categoryRoute, $childCategory->slug) }}" class="kn-chip">
                                    {{ $childCategory->getTranslation('name') }}
                                </a>
                            @endforeach
                        </nav>
                    @endif

                    <!-- Products -->
                    @if ($products->count() > 0)
                        <div class="kn-listing-grid">
                            @foreach ($products as $key => $product)
                                <div class="kn-grid-item">
                                    @if ($isPreorder)
                                        @include('preorder.frontend.product_box3', [
                                            'product' => $product,
                                        ])
                                    @else
                                        @include(
                                            'frontend.' .
                                                get_setting('homepage_select') .
                                                '.partials.product_box_1',
                                            ['product' => $product]
                                        )
                                    @endif
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
                            <h2 class="kn-empty-title">{{ translate('No products found') }}</h2>
                            <p class="kn-empty-text">
                                {{ $t('جرّب كلمة بحث أخرى أو تصفح أقسام المعدات.', 'Try another keyword or browse the equipment categories.') }}
                            </p>
                            <a href="{{ route('categories.all') }}" class="kn-btn kn-btn-primary">{{ translate('All Categories') }}</a>
                        </div>
                    @endif

                </div>
            </div>
        </form>
    </section>

    @include('frontend.partials.cart.cart_summary_toast')

@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }

        // Add-to-cart and the toast itself are handled globally in resources/js/storefront.js.
        $(document).on('click', '.mct-close', function() {
            $(this).closest('.mini-cart-toast').remove();
        });

        // Open the cart offcanvas from the "go to cart" button in the toast
        $(document).on('click', '.go-cart-btn', function(e) {
            e.preventDefault();
            const el = document.getElementById('cartOffcanvas');
            if (!el) {
                window.location.href = "{{ route('cart') }}";
                return;
            }

            refreshCartToast();
            const off = bootstrap.Offcanvas.getOrCreateInstance(el);
            off.show();
        });

        document.addEventListener("click", function(e) {
            if (e.target && e.target.id === "openFormBtn") {
                const bottomSheet = document.getElementById("bottomSheet");
                if (bottomSheet) bottomSheet.style.display = "flex";
            }

            if (e.target && e.target.id === "closeFormBtn") {
                const bottomSheet = document.getElementById("bottomSheet");
                if (bottomSheet) bottomSheet.style.display = "none";
            }

            if (e.target && e.target.id === "bottomSheet") {
                e.target.style.display = "none";
            }
        });
    </script>
@endsection
