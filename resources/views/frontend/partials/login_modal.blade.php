<div class="modal fade kn-auth-modal" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modal_title"
        aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="login_modal_title">{{ translate('Login') }}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                    @csrf

                    @if (addon_is_activated('otp_system'))
                        <!-- Phone -->
                        <div class="kn-field phone-form-group">
                            <label for="phone-code">{{ translate('Phone') }}</label>
                            <input type="tel" id="phone-code"
                                class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                        </div>
                        <!-- Country Code -->
                        <input type="hidden" name="country_code" value="">
                        <!-- Email -->
                        <div class="kn-field email-form-group d-none">
                            <label for="email">{{ translate('Email') }}</label>
                            <input type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                id="email" autocomplete="off">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <!-- Use Email Instead -->
                        <button class="btn btn-link kn-toggle-link" type="button"
                            onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                    @else
                        <!-- Email -->
                        <div class="kn-field">
                            <label for="email">{{ translate('Email') }}</label>
                            <input type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                id="email" autocomplete="email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Password -->
                    <div class="kn-field">
                        <label for="login_modal_password">{{ translate('Password') }}</label>
                        <input type="password" name="password" id="login_modal_password" class="form-control"
                            autocomplete="current-password" placeholder="{{ translate('Password') }}">
                    </div>

                    <!-- Remember Me & Forgot password -->
                    <div class="kn-auth-row">
                        <label class="aiz-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>{{ translate('Remember Me') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>
                        <a href="{{ route('password.request') }}">{{ translate('Forgot password?') }}</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Login') }}</button>
                </form>

                <!-- Social Login -->
                @if (get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                    <div class="kn-auth-divider">{{ translate('Or Login With') }}</div>
                    <ul class="kn-social">
                        @if (get_setting('facebook_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="kn-social-btn facebook">
                                    <i class="lab la-facebook-f" aria-hidden="true"></i><span>Facebook</span>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('google_login') == 1)
                            <li>
                                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="kn-social-btn google">
                                    <i class="lab la-google" aria-hidden="true"></i><span>Google</span>
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

                <!-- Register Now -->
                <p class="kn-auth-alt">
                    {{ translate('Dont have an account?') }}
                    <a href="{{ route(get_setting('customer_registration_verify') === '1' ? 'registration.verification' : 'user.registration') }}">{{ translate('Register Now') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
