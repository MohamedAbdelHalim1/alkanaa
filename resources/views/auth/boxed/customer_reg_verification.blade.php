@extends('auth.layouts.authentication')

@section('css')
    @vite(['resources/css/storefront-compat.css'])
@endsection

@section('content')
    @php
        $isOtpSystemActivated = addon_is_activated('otp_system');
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn($ar, $en) => $isAr ? $ar : $en;
    @endphp
    <div class="kn-auth">
        <div class="kn-auth-shell has-media">
            <div class="kn-auth-split">
                <div class="kn-auth-media">
                    <img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}" alt="">
                </div>

                <div class="kn-auth-card">
                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ get_setting('website_name') }}"
                        class="kn-auth-logo">

                    <h1 class="kn-auth-title">
                        {{ !$isOtpSystemActivated ? translate('Verify Your Email') : translate('Verify Your Email/Phone') }}
                    </h1>
                    <p class="kn-auth-sub">{{ $t('سنرسل لك رمز تحقق لإكمال إنشاء حسابك.', 'We will send you a verification code to finish creating your account.') }}</p>

                    <form id="reg-form" class="form-default" role="form"
                        action="{{ route('customer-reg.verification_code_send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <!-- Email or Phone -->
                        @if (addon_is_activated('otp_system'))
                            <div class="kn-field phone-form-group">
                                <label for="phone-code">{{ translate('Phone') }}</label>
                                <input type="tel" id="phone-code"
                                    class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                            </div>

                            <input type="hidden" name="country_code" value="">

                            <div class="kn-field email-form-group d-none">
                                <label for="email">{{ translate('Email') }}</label>
                                <input type="email" id="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                    autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <button class="btn btn-link kn-toggle-link" type="button"
                                onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                        @else
                            <div class="kn-field">
                                <label for="email">{{ translate('Email') }}</label>
                                <input type="email" id="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                    required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                        <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Verify') }}</button>
                    </form>

                    <p class="kn-auth-alt">
                        {{ translate('Already have an account?') }}
                        <a href="{{ route('user.login') }}">{{ translate('Log In') }}</a>
                    </p>
                </div>
            </div>

            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>
@endsection
