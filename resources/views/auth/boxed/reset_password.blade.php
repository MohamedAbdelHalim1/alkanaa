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

                    <h1 class="kn-auth-title">{{ translate('Reset Password') }}</h1>
                    <p class="kn-auth-sub">{{ translate('Enter your email address and new password and confirm password.') }}</p>

                    <form class="form-default" role="form" action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="kn-field">
                            <label for="email">{{ translate('Email') }}</label>
                            <input id="email" type="email" autocomplete="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                value="{{ $email ?? old('email') }}" placeholder="{{ translate('Email') }}" required
                                autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Code -->
                        <div class="kn-field">
                            <label for="code">{{ translate('Code') }}</label>
                            <input id="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                                class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code"
                                value="{{ $email ?? old('code') }}" placeholder="{{ translate('Code') }}" required>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="kn-field">
                            <label for="password">{{ translate('New Password') }}</label>
                            <input id="password" type="password" autocomplete="new-password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                placeholder="{{ translate('New Password') }}" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Password Confirmation-->
                        <div class="kn-field">
                            <label for="password-confirm">{{ translate('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" autocomplete="new-password"
                                class="form-control" name="password_confirmation"
                                placeholder="{{ translate('Confirm Password') }}" required>
                        </div>

                        <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Reset Password') }}</button>
                    </form>
                </div>
            </div>

            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>
@endsection
