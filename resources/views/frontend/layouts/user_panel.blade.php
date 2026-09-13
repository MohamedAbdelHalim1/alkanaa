@extends('frontend.layouts.app')
@section('content')
<section class="kn-account">
    <div class="kn-wrap">
        <div class="kn-account-grid">
            @include('frontend.inc.user_side_nav')
            <div class="aiz-user-panel">
                @yield('panel_content')
            </div>
        </div>
    </div>
</section>
@endsection
