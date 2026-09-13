@extends('frontend.layouts.app')

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">
            <header class="kn-page-head">
                <h1 class="kn-page-title">{{ translate('partners') }}</h1>
            </header>

            <ul class="kn-logos">
                @foreach ($all_partners as $partner)
                    <li class="kn-logo-tile">
                        <img src="{{ $partner->logo ? uploaded_asset($partner->logo) : static_asset('assets/img/placeholder.jpg') }}"
                            alt="{{ $partner->name ?: 'logo' }}" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
