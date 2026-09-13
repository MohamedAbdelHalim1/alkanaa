@extends('frontend.layouts.app')

@section('content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn($ar, $en) => $isAr ? $ar : $en;
    @endphp
    <div class="kn-auth">
        <div class="kn-auth-shell is-wide">
            <div class="kn-auth-card">
                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ get_setting('website_name') }}"
                    class="kn-auth-logo">

                <h1 class="kn-auth-title">{{ translate('Register your shop') }}</h1>
                <p class="kn-auth-sub">{{ $t('أدخل بياناتك الشخصية وبيانات المتجر لإنشاء حساب البائع.', 'Enter your personal and shop details to create your seller account.') }}</p>

                <form id="reg-form" action="{{ route('shops.store') }}" method="POST" class="form-default">
                    @csrf

                    <!-- Personal info -->
                    <div class="kn-auth-section">{{ translate('Personal Info') }}</div>
                    <div class="kn-fields-2">
                        <div class="kn-field">
                            <label for="seller-name">{{ translate('Your Name') }}</label>
                            <input type="text" name="name" id="seller-name" autocomplete="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required
                                placeholder="{{ translate('Full Name') }}" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="seller-email">{{ translate('Your Email') }}</label>
                            <input type="email" name="email" id="seller-email" autocomplete="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                placeholder="{{ translate('Email') }}" value="{{ $email ?? old('email') }}"
                                {{ $email ? 'readonly' : '' }}>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="seller-phone">{{ translate('Your Phone') }}</label>
                            <input type="text" name="phone" id="seller-phone" autocomplete="tel"
                                class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" required
                                placeholder="{{ translate('Phone') }}" value="{{ $phone ?? old('phone') }}"
                                {{ $phone ? 'readonly' : '' }}>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="seller-password">{{ translate('Password') }}</label>
                            <div class="kn-pass">
                                <input type="password" name="password" id="seller-password" autocomplete="new-password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                    placeholder="{{ translate('Password') }}">
                                <i class="password-toggle las la-eye" role="button" tabindex="0"
                                    aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                    onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                            </div>
                            <span class="kn-hint">{{ translate('Password must contain at least 6 digits') }}</span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="seller-password-confirm">{{ translate('Confirm Password') }}</label>
                            <div class="kn-pass">
                                <input type="password" name="password_confirmation" id="seller-password-confirm"
                                    autocomplete="new-password" class="form-control" required
                                    placeholder="{{ translate('Confirm Password') }}">
                                <i class="password-toggle las la-eye" role="button" tabindex="0"
                                    aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                    onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Shop info -->
                    <div class="kn-auth-section">{{ translate('Basic Info') }}</div>
                    <div class="kn-fields-2">
                        <div class="kn-field">
                            <label for="shop-name">{{ translate('Shop Name') }}</label>
                            <input type="text" name="shop_name" id="shop-name"
                                class="form-control{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" required
                                placeholder="{{ translate('Shop Name') }}" value="{{ old('shop_name') }}">
                            @error('shop_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="shop-address">{{ translate('Address') }}</label>
                            <input type="text" name="address" id="shop-address"
                                class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" required
                                placeholder="{{ translate('Address') }}" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="kn-field">
                            <label for="shop-tax">{{ translate('Tax Number') }}</label>
                            <input type="text" name="tax_number" id="shop-tax" class="form-control"
                                placeholder="{{ translate('Optional') }}">
                        </div>

                        <div class="kn-field">
                            <label for="shop-cr">{{ translate('Commercial Register') }}</label>
                            <input type="text" name="commercial_register" id="shop-cr" class="form-control"
                                placeholder="{{ translate('Optional') }}">
                        </div>
                    </div>

                    <!-- Recaptcha -->
                    @if (get_setting('google_recaptcha') == 1)
                        <div class="kn-field">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                            @error('g-recaptcha-response')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif

                    <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">
                        {{ translate('Register Your Shop') }}
                    </button>
                </form>

                <p class="kn-auth-alt">
                    {{ translate('Already have an account?') }}
                    <a href="{{ route('seller.login') }}">{{ translate('Log In') }}</a>
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>
    @include('auth.login_register_js')
@endsection

@section('script')
    @if (get_setting('google_recaptcha') == 1)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <script type="text/javascript">
        @if (get_setting('google_recaptcha') == 1)
            // making the CAPTCHA  a required field for form submission
            $(document).ready(function() {
                $("#reg-form").on("submit", function(evt) {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        //reCaptcha not verified
                        alert("please verify you are human!");
                        evt.preventDefault();
                        return false;
                    }
                    //captcha verified
                    //do the rest of your validations here
                    $("#reg-form").submit();
                });
            });
        @endif
    </script>
@endsection
