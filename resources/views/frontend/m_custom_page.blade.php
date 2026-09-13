<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['sa', 'ar', 'eg']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->getTranslation('title') }}</title>
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">
    @vite(['resources/css/storefront-compat.css'])
</head>
<body>
    <div class="kn-page">
        <div class="kn-wrap">
            <article class="kn-article">
                <header class="kn-page-head">
                    <h1 class="kn-page-title">{{ $page->getTranslation('title') }}</h1>
                </header>
                <div class="kn-prose">
                    {!! $page->getTranslation('content') !!}
                </div>
            </article>
        </div>
    </div>
</body>
</html>
