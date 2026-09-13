@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn($ar, $en) => $isAr ? $ar : $en;
    @endphp
    <div class="kn-auth">
        <div class="kn-auth-shell">
            <div class="kn-auth-card">
                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ get_setting('website_name') }}"
                    class="kn-auth-logo">

                <h1 class="kn-auth-title">{{ translate('Login to your account') }}</h1>
                <p class="kn-auth-sub">{{ $t('ادخل بريدك الإلكتروني وكلمة المرور لمتابعة طلباتك.', 'Enter your email and password to manage your orders.') }}</p>

                <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="kn-field">
                        <label for="login-email">{{ translate('Email') }}</label>
                        <input type="email" id="login-email" autocomplete="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                            value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="kn-field">
                        <label for="login-password">{{ translate('Password') }}</label>
                        <div class="kn-pass">
                            <input type="password" id="login-password" autocomplete="current-password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                placeholder="{{ translate('Password') }}">
                            <i class="password-toggle las la-eye" role="button" tabindex="0"
                                aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="kn-auth-row">
                        <label class="aiz-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>{{ translate('Remember Me') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>
                        <a href="{{ route('password.request') }}">{{ translate('Forgot password?') }}</a>
                    </div>

                    <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Login') }}</button>
                </form>

                <!-- Social Login -->
                @if (get_setting('google_login') == 1 ||
                        get_setting('facebook_login') == 1 ||
                        get_setting('twitter_login') == 1 ||
                        get_setting('apple_login') == 1)
                    <div class="kn-auth-divider">{{ translate('Or Login With') }}</div>
                    <ul class="kn-social">
                        @if (get_setting('google_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="kn-social-btn google">
                                    <i class="lab la-google" aria-hidden="true"></i><span>Google</span>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('facebook_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="kn-social-btn facebook">
                                    <i class="lab la-facebook-f" aria-hidden="true"></i><span>Facebook</span>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('twitter_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="kn-social-btn twitter">
                                    <i class="lab la-twitter" aria-hidden="true"></i><span>Twitter</span>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('apple_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="kn-social-btn apple">
                                    <i class="lab la-apple" aria-hidden="true"></i><span>Apple</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif

                <p class="kn-auth-alt">
                    {{ translate("Don't have an account?") }}
                    <a href="{{ route('user.registration') }}">{{ translate('Register Account Now') }}</a>
                </p>
                <p class="kn-auth-alt">
                    {{ translate("Don't have a Seller account") }}
                    <a href="{{ route('shop-reg.verification') }}">{{ translate('Register Now Seller') }}</a>
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>

    {{-- Host (not required anymore but harmless) --}}
    <div id="miniCartHost"></div>

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
        // ===== Format price (kept from your code) =====
        function formatPrice(n, cur) {
            try {
                return new Intl.NumberFormat(document.documentElement.lang || 'sa').format(n) + (cur ? (' ' + cur) : '');
            } catch (e) {
                return n + (cur ? (' ' + cur) : '');
            }
        }

        // Add-to-cart and the mini cart toast are handled globally in resources/js/storefront.js.

        // Close & remove toast
        $(document).on('click', '.mct-close', function() {
            $(this).closest('.mini-cart-toast').remove();
        });

        // ===== Sliders (as in your original) =====
        const slider = document.getElementById('slider');
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');
        if (nextBtn && prevBtn && slider) {
            nextBtn.addEventListener('click', () => slider.scrollBy({
                left: -180,
                behavior: 'smooth'
            }));
            prevBtn.addEventListener('click', () => slider.scrollBy({
                left: 180,
                behavior: 'smooth'
            }));
        }

        const sliderarrivals = document.getElementById('slider-arrivals');
        const nextBtnarrivals = document.querySelector('.next-btn-arrivals');
        const prevBtnarrivals = document.querySelector('.prev-btn-arrivals');
        if (nextBtnarrivals && prevBtnarrivals && sliderarrivals) {
            nextBtnarrivals.addEventListener('click', () => sliderarrivals.scrollBy({
                left: -180,
                behavior: 'smooth'
            }));
            prevBtnarrivals.addEventListener('click', () => sliderarrivals.scrollBy({
                left: 180,
                behavior: 'smooth'
            }));
        }

        // Open the cart offcanvas from the "go to cart" button inside the toast
        $(document).on('click', '.go-cart-btn', function(e) {
            e.preventDefault();
            const el = document.getElementById('cartOffcanvas');
            if (!el) {
                window.location.href = "{{ route('cart') }}";
                return;
            }

            // Refresh the items + summary only
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
