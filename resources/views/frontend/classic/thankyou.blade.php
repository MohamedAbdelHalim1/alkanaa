@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">
            <div class="kn-result" role="status">
                <span class="kn-result-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                <h1 class="kn-page-title">{{ $tr('شكراً لك!', 'Thank You!', '谢谢您！') }}</h1>
                <p class="kn-lead">
                    {{ $tr('تم إرسال طلب عرض السعر إلى البريد الإلكتروني المسجل لدينا.', 'The quotation request has been sent to the email registered with us.', '报价请求已发送至您在我们系统中注册的电子邮箱。') }}
                </p>
                <div class="kn-actions">
                    <a href="{{ route('home') }}" class="kn-btn kn-btn-primary kn-btn-lg">{{ $tr('متابعة التسوق', 'Continue shopping', '继续购物') }}</a>
                    <a href="{{ route('contact.us') }}" class="kn-btn kn-btn-ghost kn-btn-lg">{{ $tr('تواصل معنا', 'Contact us', '联系我们') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
