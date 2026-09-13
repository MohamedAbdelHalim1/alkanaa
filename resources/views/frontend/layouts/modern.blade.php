<!DOCTYPE html>

@php
    $rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    @yield('meta')

    @php
        $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">
    <link rel="apple-touch-icon" href="{{ $site_icon }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/cbcafb1e3c.js" crossorigin="anonymous"></script>

    {{-- Fully converted pages carry no legacy Bootstrap/AIZ stylesheet at
        all, so the standard, unrestricted Tailwind entry is safe here:
        Preflight's base-element reset has nothing to fight. --}}
    @vite(['resources/css/storefront.css', 'resources/js/storefront.js'])

    <script>
        var AIZ = AIZ || {};
        AIZ.routes = {
            addToCart: '{{ route('cart.addToCart') }}',
        };
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
        };
    </script>

    @yield('style')
</head>

<body class="bg-neutral-50 text-neutral-900 font-sans">
    {{-- TODO(Phase 1): swap these for dedicated modern nav/footer partials
        once inc/nav.blade.php and inc/footer.blade.php are rebuilt. Reusing
        the legacy includes here keeps this shell deployable in isolation
        before that conversion lands, without duplicating chrome twice in
        the meantime. --}}
    @include('frontend.inc.nav')

    <main>
        @yield('content')
    </main>

    @include('frontend.inc.button_nav')
    @include('frontend.inc.footer')

    @yield('script')
</body>
</html>
