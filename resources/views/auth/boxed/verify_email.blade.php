@extends('auth.layouts.authentication')

@section('css')
    {!! render_vite_assets(['resources/css/storefront-compat.css']) !!}
@endsection

@section('content')
    <div class="kn-auth">
        <div class="kn-auth-shell has-media">
            <div class="kn-auth-split">
                <div class="kn-auth-media">
                    <img src="{{ uploaded_asset(get_setting('password_reset_page_image')) }}"
                        alt="{{ translate('Password Reset Page Image') }}">
                </div>

                <div class="kn-auth-card">
                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ get_setting('website_name') }}"
                        class="kn-auth-logo">

                    <h1 class="kn-auth-title">{{ translate('Verify Your Email Address') }}</h1>
                    <p class="kn-auth-sub">
                        {{ translate('Before proceeding, please check your email for a verification link. If you did not receive the email.') }}
                    </p>

                    <a href="{{ route('verification.resend') }}"
                        class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Click here to request another') }}</a>
                    @if (session('resent'))
                        <div class="alert alert-success mt-3 mb-0" role="alert">
                            {{ translate('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            </div>

            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>
@endsection
