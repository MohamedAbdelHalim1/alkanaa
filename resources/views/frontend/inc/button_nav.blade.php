<style>
    .mobile-bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.97);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        border-top: 1px solid #e3e8ef;
        z-index: 1000;
        padding: 4px 8px calc(4px + env(safe-area-inset-bottom));
        display: none;
    }

    @media (max-width: 991px) {
        .mobile-bottom-nav {
            display: block;
        }
        .mobile-bottom-spacer {
            height: calc(64px + env(safe-area-inset-bottom));
            display: block;
        }
    }

    .mobile-bottom-nav__inner {
        display: flex;
        align-items: stretch;
        justify-content: space-around;
        max-width: 520px;
        margin: 0 auto;
    }

    .mobile-nav-tab {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        min-height: 52px;
        color: #5b6b82 !important;
        text-decoration: none !important;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
        position: relative;
        padding: 4px 2px;
        transition: color 0.2s ease;
    }

    .mobile-nav-tab:hover,
    .mobile-nav-tab.is-active {
        color: #2346d1 !important;
    }

    .mobile-nav-tab i {
        font-size: 20px;
    }

    .mobile-nav-badge {
        position: absolute;
        top: 2px;
        inset-inline-start: calc(50% + 4px);
        background: #2346d1;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        min-width: 17px;
        height: 17px;
        padding: 0 4px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ffffff;
    }
</style>

<div class="d-lg-none">
    <div class="mobile-bottom-spacer" aria-hidden="true"></div>

    @php
        $bnIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $bt = fn ($ar, $en) => $bnIsAr ? $ar : $en;
    @endphp
    <nav class="mobile-bottom-nav" aria-label="{{ $bt('التنقل السفلي', 'Bottom navigation') }}">
        <div class="mobile-bottom-nav__inner">
            <a class="mobile-nav-tab {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">
                <i class="fa-solid fa-house" aria-hidden="true"></i>
                <span>{{ $bt('الرئيسية', 'Home') }}</span>
            </a>

            <!-- Menu / Offcanvas trigger -->
            <button type="button" class="mobile-nav-tab border-0 bg-transparent" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fa-solid fa-table-cells" aria-hidden="true"></i>
                <span>{{ $bt('الأقسام', 'Categories') }}</span>
            </button>

            <!-- Wishlist -->
            <a class="mobile-nav-tab" href="{{ route('wishlists.index') }}">
                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                @php
                    $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
                @endphp
                @if ($wishlistCount > 0)
                    <span class="mobile-nav-badge">{{ $wishlistCount }}</span>
                @endif
                <span>{{ $bt('المفضلة', 'Wishlist') }}</span>
            </a>

            <!-- Cart -->
            @php
                if (auth()->check()) {
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                } else {
                    $tempUserId = session()->get('temp_user_id');
                    $cartCount = $tempUserId ? \App\Models\Cart::where('temp_user_id', $tempUserId)->count() : 0;
                }
            @endphp
            <a class="mobile-nav-tab" href="javascript:void(0);" onclick="openCartOffcanvas()">
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                <span class="mobile-nav-badge cart-count-span">{{ $cartCount }}</span>
                <span>{{ $bt('السلة', 'Cart') }}</span>
            </a>
        </div>
    </nav>
</div>
