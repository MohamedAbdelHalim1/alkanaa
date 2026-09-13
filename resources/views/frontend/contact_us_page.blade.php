@extends('frontend.layouts.app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
    @php
        $lang = str_replace('_', '-', app()->getLocale());
        $content = json_decode($page->getTranslation('content', $lang));
        $address = $content->address ?? null;
        $phone = $content->phone ?? null;
        $email = $content->email ?? null;
    @endphp

    <div class="kn-page">
        <div class="kn-wrap">
            <header class="kn-page-head">
                <h1 class="kn-page-title">{{ $page->getTranslation('title') }}</h1>
                @if (!empty($content->description))
                    <p class="kn-lead">{{ $content->description }}</p>
                @endif
            </header>

            <div class="kn-contact">
                <ul class="kn-channels">
                    @if ($address)
                        <li>
                            <div class="kn-channel">
                                <span class="kn-channel-icon" aria-hidden="true"><i class="las la-map-marker"></i></span>
                                <span class="kn-channel-text">
                                    <span class="kn-channel-label">{{ translate('Address') }}</span>
                                    <span class="kn-channel-value">{!! str_replace("\n", '<br>', e($address)) !!}</span>
                                </span>
                            </div>
                        </li>
                    @endif
                    @if ($phone)
                        <li>
                            <a class="kn-channel" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">
                                <span class="kn-channel-icon" aria-hidden="true"><i class="las la-phone"></i></span>
                                <span class="kn-channel-text">
                                    <span class="kn-channel-label">{{ translate('Phone') }}</span>
                                    <span class="kn-channel-value"><bdi dir="ltr">{{ $phone }}</bdi></span>
                                </span>
                            </a>
                        </li>
                    @endif
                    @if ($email)
                        <li>
                            <a class="kn-channel" href="mailto:{{ $email }}">
                                <span class="kn-channel-icon" aria-hidden="true"><i class="las la-envelope"></i></span>
                                <span class="kn-channel-text">
                                    <span class="kn-channel-label">{{ translate('Email Address') }}</span>
                                    <span class="kn-channel-value"><bdi dir="ltr">{{ $email }}</bdi></span>
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="kn-form-card">
                    <form class="form-default" role="form" action="{{ route('contact') }}" method="POST">
                        @csrf

                        <div class="kn-field-grid">
                            <!-- Name -->
                            <div class="kn-field">
                                <label for="name" class="kn-label">{{ translate('Name') }}<span class="kn-req" aria-hidden="true">*</span></label>
                                <input type="text" id="name" class="form-control" value="{{ old('name') }}" placeholder="{{ translate('Enter Name') }}" name="name" autocomplete="name" required>
                            </div>
                            <!-- Phone -->
                            <div class="kn-field">
                                <label for="phone" class="kn-label">{{ translate('Phone no. (optional)') }}</label>
                                <input type="tel" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="{{ translate('Enter Phone') }}" name="phone" autocomplete="tel" inputmode="tel" dir="ltr">
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="kn-field">
                            <label for="email" class="kn-label">{{ translate('Email') }}<span class="kn-req" aria-hidden="true">*</span></label>
                            <input type="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="{{ translate('Enter Email') }}" name="email" autocomplete="email" dir="ltr" required>
                        </div>
                        <!-- Query -->
                        <div class="kn-field">
                            <label for="query" class="kn-label">{{ translate('Tell us about your query') }}<span class="kn-req" aria-hidden="true">*</span></label>
                            <textarea id="query" class="form-control" placeholder="{{ translate('Type here...') }}" name="content" rows="4" required>{{ old('content') }}</textarea>
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

                        <!-- Submit Button -->
                        <div class="kn-form-actions">
                            @if (env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
                                <a class="kn-btn kn-btn-primary kn-btn-lg" href="javascript:void(1)" onclick="showWarning()" role="button">
                                    {{ translate('Submit') }}
                                </a>
                            @else
                                <button type="submit" class="kn-btn kn-btn-primary kn-btn-lg">{{ translate('Submit') }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

    <script type="text/javascript">
        function showWarning() {
            AIZ.plugins.notify('warning', "{{ translate('Something went wrong.') }}");
            return false;
        }
    </script>
@endsection
