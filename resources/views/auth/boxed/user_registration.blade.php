@extends('frontend.layouts.app')
@section('style')
    <!-- Toastr CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection
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

                <h1 class="kn-auth-title">{{ translate('Create an account') }}</h1>
                <p class="kn-auth-sub">{{ $t('سجّل مرة واحدة لتتابع طلباتك وعروض الأسعار بسهولة.', 'Register once to track your orders and quotations easily.') }}</p>

                <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <!-- Name -->
                    <div class="kn-field">
                        <label for="name">{{ translate('Full Name') }}</label>
                        <input type="text" required id="name" autocomplete="name"
                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                            value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}" name="name">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    @if (true)
                        {{-- Show both fields (phone + email) --}}
                        <div class="kn-field phone-form-group">
                            <label for="phone-code">{{ translate('Phone') }}</label>
                            <input type="tel" id="phone-code" required
                                class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', 'SA') }}">

                        <div class="kn-field email-form-group">
                            <label for="email">{{ translate('Email') }}</label>
                            <input type="email" required id="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                autocomplete="off">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    @else
                        {{-- If OTP system is disabled, show only the email field --}}
                        <div class="kn-field">
                            <label for="email">{{ translate('Email') }}</label>
                            <input type="email" required id="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ $email ?? old('email') }}" placeholder="{{ translate('Email') }}"
                                name="email" {{ $email ? 'readonly' : '' }}>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- password -->
                    <div class="kn-field">
                        <label for="password">{{ translate('Password') }}</label>
                        <div class="kn-pass">
                            <input type="password" required id="password" autocomplete="new-password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="{{ translate('Password') }}" name="password">
                            <i class="password-toggle las la-eye" role="button" tabindex="0"
                                aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                        </div>
                        <span class="kn-hint">{{ translate('Password must contain at least 6 digits') }}</span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <!-- password Confirm -->
                    <div class="kn-field">
                        <label for="password_confirmation">{{ translate('Confirm Password') }}</label>
                        <div class="kn-pass">
                            <input type="password" id="password_confirmation" class="form-control"
                                autocomplete="new-password" placeholder="{{ translate('Confirm Password') }}"
                                name="password_confirmation">
                            <i class="password-toggle las la-eye" role="button" tabindex="0"
                                aria-label="{{ $t('إظهار كلمة المرور', 'Show password') }}"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"></i>
                        </div>
                    </div>

                    <!-- Address -->
                    <button type="button" class="kn-auth-address" onclick="add_new_address()">
                        <i class="las la-plus" aria-hidden="true"></i>
                        <span>{{ translate('Add New Address') }}</span>
                    </button>

                    <!-- Company Checkbox -->
                    <div class="kn-auth-check">
                        <label class="aiz-checkbox">
                            <input type="checkbox" id="is_company_checkbox" name="is_company" value="1"
                                onchange="toggleCompanyFields()">
                            <span>{{ translate('Register as a Company') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>

                    <!-- Hidden Company Fields -->
                    <div id="company_fields" style="display: none;">
                        <div class="kn-field">
                            <label for="company_name">{{ translate('Company Name') }}</label>
                            <input type="text" id="company_name" name="company_name" class="form-control"
                                placeholder="{{ translate('Enter company name') }}">
                        </div>
                        <div class="kn-field">
                            <label for="commercial_register">{{ translate('Commercial Register') }}</label>
                            <input type="text" id="commercial_register" name="commercial_register" class="form-control"
                                placeholder="{{ translate('Enter commercial register number') }}">
                        </div>
                        <div class="kn-field">
                            <label for="tax_number">{{ translate('Tax Number') }}</label>
                            <input type="text" id="tax_number" name="tax_number" class="form-control"
                                placeholder="{{ translate('Enter tax number') }}">
                        </div>
                        <div class="alert alert-warning" role="alert">
                            @if (app()->getLocale() == 'sa')
                                <p class="mb-0">يرجى التأكد من إدخال البيانات المطلوبة بدقة، لأنها ستُستخدم في إعداد الفواتير وعروض الأسعار الخاصة بك.</p>
                            @elseif(app()->getLocale() == 'cn')
                                <p class="mb-0">请确保准确填写所需信息，这些信息将用于您的发票和报价单。</p>
                            @else
                                <p class="mb-0">Please make sure to enter the required data accurately, as it will be used for your invoices and quotations.</p>
                            @endif
                        </div>
                    </div>

                    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog"
                        aria-labelledby="newAddressLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newAddressLabel">{{ translate('New Address') }}</h5>
                                </div>
                                <div class="modal-body">
                                    <!-- Address -->
                                    <div class="kn-field">
                                        <label for="reg-address">{{ translate('Address') }}</label>
                                        <textarea class="form-control" id="reg-address" name="address" rows="2" required
                                            placeholder="{{ translate('Your Address') }}"></textarea>
                                    </div>

                                    <!-- Country -->
                                    <div class="kn-field">
                                        <label for="reg-country">{{ translate('Country') }}</label>
                                        <select class="form-select" id="reg-country" data-live-search="true"
                                            name="country_id" required>
                                            <option value="">{{ translate('Select your country') }}</option>
                                            @foreach (get_active_countries() as $country)
                                                <option value="{{ $country->id }}">{{ translate($country->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- State -->
                                    <div class="kn-field">
                                        <label for="reg-state">{{ translate('State') }}</label>
                                        <select class="form-select" id="reg-state" data-live-search="true"
                                            name="state_id" required></select>
                                    </div>

                                    <!-- City -->
                                    <div class="kn-field">
                                        <label for="reg-city">{{ translate('City') }}</label>
                                        <select class="form-select" id="reg-city" data-live-search="true"
                                            name="city_id" required></select>
                                    </div>

                                    <!-- Postal Code -->
                                    <div class="kn-field">
                                        <label for="reg-postal">{{ translate('Postal code') }}</label>
                                        <input type="text" class="form-control" id="reg-postal" name="postal_code"
                                            required placeholder="{{ translate('Your Postal Code') }}">
                                    </div>

                                    <!-- Save button -->
                                    <div class="kn-form-actions">
                                        <button type="button" onclick="hideModal()" data-dismiss="modal"
                                            class="kn-btn kn-btn-primary">{{ translate('Save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recaptcha -->
                    @if (get_setting('google_recaptcha') == 1)
                        <div class="kn-field">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                        </div>
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="invalid-feedback" role="alert" style="display: block;">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    @endif

                    <!-- Terms and Conditions -->
                    <div class="kn-auth-check">
                        <label class="aiz-checkbox">
                            <input type="checkbox" name="checkbox_example_1" required>
                            <span>{{ translate('By signing up you agree to our ') }}
                                <a href="{{ route('terms') }}" class="kn-auth-inline-link">{{ translate('terms and conditions.') }}</a></span>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" onclick="checkAddress()"
                        class="kn-btn kn-btn-primary kn-auth-submit">{{ translate('Create Account') }}</button>
                </form>

                <!-- Social Login -->
                @if (get_setting('google_login') == 1 ||
                        get_setting('facebook_login') == 1 ||
                        get_setting('twitter_login') == 1 ||
                        get_setting('apple_login') == 1)
                    <div class="kn-auth-divider">{{ translate('Or Join With') }}</div>
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

                <!-- Log In -->
                <p class="kn-auth-alt">
                    {{ translate('Already have an account?') }}
                    <a href="{{ route('user.login') }}">{{ translate('Log In') }}</a>
                </p>
            </div>

            <!-- Go Back -->
            <a href="{{ url()->previous() }}" class="kn-auth-back">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Back to Previous Page') }}
            </a>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if (get_setting('google_recaptcha') == 1)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <script type="text/javascript">
        function checkAddress() {
            $address = $('#address').val();
            if ($address == null || $address == '')
                toastr.error('{{ translate('Please Add Address') }}');

        }

        function add_new_address() {
            $('#new-address-modal').modal('show');
        }
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

        function hideModal() {
            toastr.success('{{ translate('Address Added') }}');
            $("#new-address-modal").modal('hide');
        }
    </script>
    <script>
        function toggleCompanyFields() {
            const isCompany = document.getElementById('is_company_checkbox').checked;
            const companyFields = document.getElementById('company_fields');
            companyFields.style.display = isCompany ? 'block' : 'none';
        }
    </script>


    @include('frontend.partials.address.address_js')
@endsection
