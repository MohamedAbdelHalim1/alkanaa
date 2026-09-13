@php
    $navLocale = app()->getLocale();
    $navIsAr = in_array($navLocale, ['sa', 'ar', 'eg']);
    $nt = fn ($ar, $en) => $navIsAr ? $ar : $en;
    $header_logo = get_setting('header_logo');
    $siteName = get_setting('website_name') ?? 'AlKanaa';
    $phone = get_setting('contact_phone') ?? '966565124444';
    $motto = $nt('الخيار الأول للمطابخ التجارية في السعودية', 'The first choice for commercial kitchens in Saudi Arabia');
    $searchPlaceholder = $nt('ابحث عن منتج أو قسم', 'Search products or categories');

    $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
    if (auth()->check()) {
        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
    } else {
        $tempUserId = session()->get('temp_user_id');
        $cartCount = $tempUserId ? \App\Models\Cart::where('temp_user_id', $tempUserId)->count() : 0;
    }
@endphp

<!-- Desktop Header -->
<header class="kn-header d-none d-lg-block">
    <div class="kn-header-bar">
        <div class="kn-wrap">
            <a href="{{ route('home') }}" class="kn-logo kn-logo-lockup" aria-label="{{ $siteName }}">
                @if ($header_logo)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="">
                @endif
                <span class="kn-logo-text">{{ $nt('القناعة', 'AlKanaa') }}</span>
            </a>

            <div class="kn-search" role="search">
                <form action="{{ route('search') }}" method="GET">
                    <label for="kn-search-desktop" class="kn-sr-only">{{ $searchPlaceholder }}</label>
                    <div class="kn-search-field">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" id="kn-search-desktop" name="keyword" value="{{ request('keyword') }}"
                            placeholder="{{ $searchPlaceholder }}" autocomplete="off">
                        <button type="submit" class="kn-search-submit">{{ $nt('بحث', 'Search') }}</button>
                    </div>
                </form>
            </div>

            <div class="kn-header-actions">
                <a href="tel:{{ $phone }}" class="header-icon-action" title="{{ $nt('اتصل بنا', 'Call us') }}"
                    aria-label="{{ $nt('اتصل بنا', 'Call us') }}">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                </a>

                <a href="{{ route('wishlists.index') }}" class="header-icon-action" title="{{ $nt('المفضلة', 'Wishlist') }}"
                    aria-label="{{ $nt('المفضلة', 'Wishlist') }}">
                    <i class="fa-regular fa-heart" aria-hidden="true"></i>
                    @if ($wishlistCount > 0)
                        <span class="header-icon-badge">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <a href="javascript:void(0);" class="header-icon-action" id="nav-cart-area" onclick="openCartOffcanvas()"
                    title="{{ $nt('السلة', 'Cart') }}" aria-label="{{ $nt('السلة', 'Cart') }}">
                    <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    <span class="header-icon-badge cart-count-span">{{ $cartCount }}</span>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="header-icon-action" title="{{ $nt('حسابي', 'My account') }}"
                        aria-label="{{ $nt('حسابي', 'My account') }}">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                    </a>
                @else
                    <a href="{{ route('user.login') }}" class="header-icon-action" title="{{ $nt('تسجيل الدخول', 'Log in') }}"
                        aria-label="{{ $nt('تسجيل الدخول', 'Log in') }}">
                        <i class="fa-regular fa-user" aria-hidden="true"></i>
                    </a>
                @endauth

                <div class="dropdown">
                    <button class="kn-lang-btn" type="button" id="langDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="{{ $nt('اللغة', 'Language') }}">
                        @if ($navLocale == 'sa')
                            <img src="https://flagcdn.com/sa.svg" width="18" height="12" alt="">
                        @elseif ($navLocale == 'cn')
                            <img src="https://flagcdn.com/cn.svg" width="18" height="12" alt="">
                        @else
                            <img src="https://flagcdn.com/gb.svg" width="18" height="12" alt="">
                        @endif
                        <span>{{ strtoupper($navLocale == 'sa' ? 'ع' : $navLocale) }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 9px;" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-1" style="border-radius: 10px;"
                        aria-labelledby="langDropdown">
                        @foreach (get_all_active_language() as $language)
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 rounded"
                                    href="{{ url('language/' . $language->code) }}">
                                    @if ($language->code == 'sa')
                                        <img src="https://flagcdn.com/sa.svg" width="18" alt="">
                                    @elseif($language->code == 'en')
                                        <img src="https://flagcdn.com/gb.svg" width="18" alt="">
                                    @elseif($language->code == 'cn')
                                        <img src="https://flagcdn.com/cn.svg" width="18" alt="">
                                    @endif
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <nav class="kn-subnav" aria-label="{{ $nt('القائمة الرئيسية', 'Main menu') }}">
        <div class="kn-wrap">
            <div class="kn-subnav-links">
                <div class="position-relative mega-category">
                    <a href="{{ route('search') }}" class="kn-cat-trigger"
                        onmouseover="showMegaMenu()" onmouseout="hideMegaMenu()" onfocus="showMegaMenu()">
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                        <span>{{ $nt('جميع الأقسام', 'All categories') }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 9px;" aria-hidden="true"></i>
                    </a>

                    <div class="@if (App::getLocale() == 'en' || App::getLocale() == 'cn') mega-menu-ltr @else mega-menu @endif"
                        id="megaMenu" onmouseover="cancelHide()" onmouseout="hideMegaMenu()">
                        <ul class="category-list">
                            @foreach ($categories->where('featured', 1) as $category)
                                <li data-sub="cat-{{ $category->id }}" onmouseover="showSub(this)">
                                    <a href="{{ route('products.category', $category->slug) }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ uploaded_asset($category->icon) }}" alt=""
                                                style="width: 28px; height: 28px; object-fit: contain;">
                                            <span>{{ $category->getTranslation('name') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="subcategories-wrapper">
                            <div class="sub-category-container" id="activeSubCategory"></div>
                            @foreach ($categories as $category)
                                <template id="cat-{{ $category->id }}">
                                    @if ($category->childrenCategories && $category->childrenCategories->count())
                                        @foreach ($category->childrenCategories as $child)
                                            <div class="sub-group">
                                                <h5 class="sub-group-title" data-sub="subcat-{{ $child->id }}">
                                                    <a href="{{ route('products.category', $child->slug) }}">
                                                        {{ $child->getTranslation('name') }}
                                                    </a>
                                                </h5>
                                                @if ($child->childrenCategories && $child->childrenCategories->count())
                                                    <ul class="third-level-list">
                                                        @foreach ($child->childrenCategories as $grandChild)
                                                            <li>
                                                                <a href="{{ route('products.category', $grandChild->slug) }}">
                                                                    {{ $grandChild->getTranslation('name') }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted p-3">{{ $nt('لا توجد أقسام فرعية', 'No subcategories') }}</p>
                                    @endif
                                </template>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('todays-deal') }}" class="kn-subnav-link">
                    <i class="fa-solid fa-tag" aria-hidden="true"></i>
                    <span>{{ $nt('منتجات مخفضة', 'Discounted products') }}</span>
                </a>
                <a href="{{ route('get-a-quote') }}" class="kn-subnav-link">
                    <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                    <span>{{ $nt('طلب عرض سعر', 'Request a quote') }}</span>
                </a>
                <a href="{{ route('about.us') }}" class="kn-subnav-link">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                    <span>{{ $nt('من نحن', 'About us') }}</span>
                </a>
            </div>

            <span class="kn-motto">
                <img src="https://flagcdn.com/sa.svg" width="20" height="14" alt="">
                <span>{{ $motto }}</span>
            </span>
        </div>
    </nav>
</header>

<!-- Mobile Header -->
<div class="kn-mobile-motto d-lg-none">{{ $motto }}</div>

<header class="kn-header kn-mobile-header d-lg-none">
    <div class="kn-mobile-row">
        <button type="button" class="kn-mobile-icon" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
            aria-controls="mobileMenu" aria-label="{{ $nt('القائمة', 'Menu') }}">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        {{-- The logo is a square mark whose lettering is unreadable at header size,
            so phones get the brand name set as a wordmark instead. --}}
        <a href="{{ route('home') }}" class="kn-wordmark" aria-label="{{ $siteName }}">
            {{ $nt('القناعة', 'AlKanaa') }}
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="kn-mobile-icon" aria-label="{{ $nt('حسابي', 'My account') }}">
                <i class="fa-solid fa-user" aria-hidden="true"></i>
            </a>
        @else
            <a href="{{ route('user.login') }}" class="kn-mobile-icon" aria-label="{{ $nt('تسجيل الدخول', 'Log in') }}">
                <i class="fa-regular fa-user" aria-hidden="true"></i>
            </a>
        @endauth
    </div>

    <div class="kn-search" role="search">
        <form action="{{ route('search') }}" method="GET">
            <label for="kn-search-mobile" class="kn-sr-only">{{ $searchPlaceholder }}</label>
            <div class="kn-search-field">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" id="kn-search-mobile" name="keyword" value="{{ request('keyword') }}"
                    placeholder="{{ $searchPlaceholder }}" autocomplete="off" enterkeyhint="search">
            </div>
        </form>
    </div>
</header>

<!-- Mobile Menu Drawer -->
<div class="offcanvas {{ $navIsAr ? 'offcanvas-end' : 'offcanvas-start' }} kn-drawer d-lg-none" dir="{{ $navIsAr ? 'rtl' : 'ltr' }}" tabindex="-1"
    id="mobileMenu" aria-labelledby="mobileMenuLabel" style="padding-bottom: 80px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title m-0 text-white fw-bold" id="mobileMenuLabel" style="font-size: 1rem;">{{ $siteName }}</h5>
        <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="offcanvas"
            aria-label="{{ $nt('إغلاق', 'Close') }}"></button>
    </div>
    <div class="offcanvas-body p-2">
        <ul class="list-unstyled m-0 p-0">
            <li>
                <a href="{{ route('home') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-house" aria-hidden="true"></i>{{ $nt('الصفحة الرئيسية', 'Home') }}</span>
                </a>
            </li>
            <li>
                <a class="kn-drawer-link" data-bs-toggle="collapse" href="#productsMenu" role="button"
                    aria-expanded="false" aria-controls="productsMenu">
                    <span><i class="fa-solid fa-table-cells" aria-hidden="true"></i>{{ $nt('جميع المنتجات', 'All products') }}</span>
                    <i class="fa-solid fa-chevron-down" style="color: #8592a6; font-size: 12px;" aria-hidden="true"></i>
                </a>

                <div class="collapse ps-2 pe-2" id="productsMenu">
                    <ul class="list-unstyled m-0 p-0">
                        @foreach ($categories->where('featured', 1) as $category)
                            @php $hasChildren = $category->childrenCategories && $category->childrenCategories->count(); @endphp
                            <li>
                                <a class="kn-drawer-sub justify-content-between"
                                    @if ($hasChildren) data-bs-toggle="collapse" href="#sub-{{ $category->id }}" role="button"
                                        aria-expanded="false" aria-controls="sub-{{ $category->id }}"
                                    @else href="{{ route('products.category', $category->slug) }}" @endif>
                                    <span class="d-flex align-items-center gap-2">
                                        <img src="{{ uploaded_asset($category->icon) }}" alt=""
                                            style="width: 22px; height: 22px; object-fit: contain;">
                                        <span>{{ $category->getTranslation('name') }}</span>
                                    </span>
                                    @if ($hasChildren)
                                        <i class="fa-solid fa-chevron-down" style="color: #8592a6; font-size: 10px;" aria-hidden="true"></i>
                                    @endif
                                </a>

                                @if ($hasChildren)
                                    <div class="collapse ps-4 pe-4" id="sub-{{ $category->id }}">
                                        <ul class="list-unstyled m-0 p-0">
                                            <li>
                                                <a class="kn-drawer-sub fw-bold" href="{{ route('products.category', $category->slug) }}">
                                                    {{ $nt('كل منتجات القسم', 'All in this category') }}
                                                </a>
                                            </li>
                                            @foreach ($category->childrenCategories as $sub)
                                                <li>
                                                    <a class="kn-drawer-sub" href="{{ route('products.category', $sub->slug) }}">
                                                        {{ $sub->getTranslation('name') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('todays-deal') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-tag" aria-hidden="true"></i>{{ $nt('منتجات مخفضة', 'Discounted products') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('get-a-quote') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-file-lines" aria-hidden="true"></i>{{ $nt('عروض الأسعار', 'Request a quote') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('service-request') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-compass-drafting" aria-hidden="true"></i>{{ $nt('الخدمات الهندسية', 'Engineering services') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('maintainence-request') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>{{ $nt('خدمات الصيانة', 'Maintenance services') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('all-our-partners') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-handshake" aria-hidden="true"></i>{{ $nt('شركاء النجاح', 'Our partners') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('about.us') }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-circle-info" aria-hidden="true"></i>{{ $nt('عن القناعة', 'About AlKanaa') }}</span>
                </a>
            </li>
            <li>
                <a href="tel:{{ $phone }}" class="kn-drawer-link">
                    <span><i class="fa-solid fa-phone" aria-hidden="true"></i>{{ $nt('اتصل بنا', 'Call us') }}</span>
                </a>
            </li>
        </ul>

        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 px-2" style="border-top: 1px solid #e3e8ef;">
            @foreach (get_all_active_language() as $language)
                <a href="{{ url('language/' . $language->code) }}"
                    class="kn-btn {{ $language->code == $navLocale ? 'kn-btn-primary' : '' }}"
                    style="min-height: 40px; padding: 6px 14px; {{ $language->code == $navLocale ? '' : 'background:#eef2f6;color:#0e1a2e !important;' }}">
                    {{ $language->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    let megaMenuTimer = null;

    function showMegaMenu() {
        clearTimeout(megaMenuTimer);
        const menu = document.getElementById('megaMenu');
        if (!menu) return;
        menu.style.display = 'flex';

        // Auto load first category if activeSubCategory is empty
        const container = document.getElementById('activeSubCategory');
        const firstLi = menu.querySelector('.category-list li');
        if (firstLi && (!container || container.innerHTML.trim() === '')) {
            showSub(firstLi);
        }
    }

    function hideMegaMenu() {
        megaMenuTimer = setTimeout(function () {
            const menu = document.getElementById('megaMenu');
            if (menu) {
                menu.style.display = 'none';
            }
        }, 200);
    }

    function cancelHide() {
        clearTimeout(megaMenuTimer);
    }

    function showSub(el) {
        if (!el) return;
        const subId = el.getAttribute('data-sub');
        const template = document.getElementById(subId);
        const container = document.getElementById('activeSubCategory');
        if (template && container) {
            container.innerHTML = template.innerHTML;
        }

        const list = el.closest('.category-list');
        if (list) {
            list.querySelectorAll('li').forEach(li => li.classList.remove('active-cat'));
            el.classList.add('active-cat');
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('megaMenu');
            if (menu) menu.style.display = 'none';
        }
    });

    function openCartOffcanvas() {
        const el = document.getElementById('cartOffcanvas');
        if (!el || typeof bootstrap === 'undefined') {
            window.location.href = "{{ route('cart') }}";
            return;
        }

        const open = function () {
            bootstrap.Offcanvas.getOrCreateInstance(el).show();
        };

        if (typeof refreshCartToast === 'function') {
            const request = refreshCartToast();
            if (request && typeof request.always === 'function') {
                request.always(open);
                return;
            }
        }

        open();
    }
</script>
