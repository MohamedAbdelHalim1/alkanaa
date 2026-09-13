{{--
    Shared <head> for the admin panel's two layouts (backend.layouts.app and
    backend.layouts.layout), which used to each maintain their own near-
    identical copy. backend.layouts.blank is intentionally NOT included here
    — it's a distinct installer-flow layout with its own styling.

    The old proprietary AIZ theme (vendors.css/aiz-core.css/js) is no longer
    loaded here — the app has fully switched to a Tailwind + Alpine.js stack
    built from scratch (see resources/css/admin.css, resources/js/admin.js).
--}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="app-url" content="{{ getBaseURL() }}">
<meta name="file-base-url" content="{{ getFileBaseURL() }}">

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Favicon -->
<link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
<link rel="apple-touch-icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
<title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

<!-- google font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap">

@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <meta name="text-direction" content="rtl">
@endif

<x-legacy-js-bridge />

{!! render_vite_assets(['resources/css/admin.css', 'resources/js/admin.js']) !!}

<script>
    // Translated strings for the file-upload widget (see Milestone 1B).
    window.appStrings = {
        nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
        nothing_found: '{!! translate('Nothing found', null, true) !!}',
        choose_file: '{{ translate('Choose file') }}',
        file_selected: '{{ translate('File selected') }}',
        files_selected: '{{ translate('Files selected') }}',
        add_more_files: '{{ translate('Add more files') }}',
        adding_more_files: '{{ translate('Adding more files') }}',
        drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
        browse: '{{ translate('Browse') }}',
        upload_complete: '{{ translate('Upload complete') }}',
        upload_paused: '{{ translate('Upload paused') }}',
        resume_upload: '{{ translate('Resume upload') }}',
        pause_upload: '{{ translate('Pause upload') }}',
        retry_upload: '{{ translate('Retry upload') }}',
        cancel_upload: '{{ translate('Cancel upload') }}',
        uploading: '{{ translate('Uploading') }}',
        processing: '{{ translate('Processing') }}',
        complete: '{{ translate('Complete') }}',
        file: '{{ translate('File') }}',
        files: '{{ translate('Files') }}',
    };
</script>
