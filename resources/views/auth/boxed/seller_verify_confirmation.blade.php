@extends('frontend.layouts.app')

@section('content')
    @php
        $isOtpSystemActivated = addon_is_activated('otp_system');
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn($ar, $en) => $isAr ? $ar : $en;
    @endphp
    <div class="kn-auth">
        <div class="kn-auth-shell">
            <div class="kn-auth-card">
                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ get_setting('website_name') }}"
                    class="kn-auth-logo">

                <h1 class="kn-auth-title">
                    {{ !$isOtpSystemActivated ? translate('Verify Your Email') : translate('Verify Your Email/Phone') }}
                </h1>
                <p class="kn-auth-sub">{{ $t('أدخل رمز التحقق الذي أرسلناه إليك.', 'Enter the verification code we sent you.') }}</p>

                <form id="reg-form" class="form-default" role="form"
                    action="{{ route('shop-reg.verify_code_confirmation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="seller_verification_id" value="{{ $sellerVerification->id }}">

                    @if ($sellerVerification->email != null)
                        <div class="kn-field">
                            <label for="verify-email">{{ translate('Email') }}</label>
                            <input type="text" id="verify-email" name="email" class="form-control"
                                value="{{ $sellerVerification->email }}" readonly>
                        </div>
                    @else
                        <div class="kn-field">
                            <label for="verify-phone">{{ translate('Phone') }}</label>
                            <input type="text" id="verify-phone" name="phone" class="form-control"
                                value="{{ $sellerVerification->phone }}" readonly>
                        </div>
                    @endif

                    <div class="kn-field">
                        <label for="verification_code">{{ translate('Verification Code') }}</label>
                        <input type="number" id="verification_code" name="verification_code" class="form-control"
                            inputmode="numeric" autocomplete="one-time-code">
                    </div>

                    <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Submit') }}</button>
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
