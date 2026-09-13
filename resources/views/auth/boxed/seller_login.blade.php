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

                <h1 class="kn-auth-title">{{ translate('login as a seller') }}</h1>
                <p class="kn-auth-sub">{{ $t('ادخل إلى لوحة البائع لإدارة منتجاتك وطلباتك.', 'Sign in to your seller panel to manage products and orders.') }}</p>

                <form class="form-default" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="kn-field">
                        <label for="email">{{ translate('Email') }}</label>
                        <input type="email" id="email" autocomplete="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                            value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback d-block">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="kn-field">
                        <label for="password">{{ translate('Password') }}</label>
                        <div class="kn-pass">
                            <input type="password" id="password" autocomplete="current-password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                placeholder="{{ translate('Password') }}">
                            <i class="password-toggle las la-eye" role="button" tabindex="0"
                                aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                        </div>
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

                <p class="kn-auth-alt">
                    {{ translate("Don't have a Seller account") }}
                    <a href="{{ route('shop-reg.verification') }}">{{ translate('Register Now Seller') }}</a>
                </p>
                <p class="kn-auth-alt">
                    {{ $t('تبحث عن حساب عميل؟', 'Looking for a customer account?') }}
                    <a href="{{ route('user.login') }}">{{ translate('Log In') }}</a>
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
    <script type="text/javascript">
        function autoFillSeller() {
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
