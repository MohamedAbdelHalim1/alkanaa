@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap kn-narrow">
            <header class="kn-page-head">
                <h1 class="kn-page-title">{{ translate('faq') }}</h1>
            </header>

            <div class="kn-faq" id="faqAccordion">
                @foreach ($faqs as $index => $faq)
                    <div class="kn-faq-item">
                        <h2 class="kn-faq-heading" id="heading{{ $index }}">
                            <button type="button" class="kn-faq-q" id="faq-btn-{{ $index }}"
                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $index }}">
                                <span>{{ $faq['question'] }}</span>
                                <svg class="kn-faq-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </button>
                        </h2>

                        <div id="collapse{{ $index }}" class="kn-faq-a" role="region"
                            aria-labelledby="faq-btn-{{ $index }}" @if ($index != 0) hidden @endif>
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="kn-faq-cta kn-panel kn-panel-steel">
                <p class="kn-text">{{ $tr('لم تجد إجابة سؤالك؟ فريقنا جاهز لمساعدتك.', "Didn't find your answer? Our team is ready to help.", '没有找到答案？我们的团队随时为您提供帮助。') }}</p>
                <a href="{{ route('contact.us') }}" class="kn-btn kn-btn-primary">{{ $tr('تواصل معنا', 'Contact us', '联系我们') }}</a>
            </div>
        </div>
    </div>

    {{-- Host (not required anymore but harmless) --}}
    <div id="miniCartHost"></div>

    @include('frontend.partials.cart.cart_summary_toast')
@endsection

@section('script')
    <script type="text/javascript">
        // Add-to-cart and the mini-cart toast are global (resources/js/storefront.js).
        // فتح الـ Offcanvas عند الضغط على زر "اذهب إلى السلة" داخل التوست
        $(document).on('click', '.go-cart-btn', function(e) {
            e.preventDefault();
            const el = document.getElementById('cartOffcanvas');
            if (!el) {
                window.location.href = "{{ route('cart') }}";
                return;
            }

            // هات أحدث محتوى للأصناف + الملخص بس
            refreshCartToast();
            const off = bootstrap.Offcanvas.getOrCreateInstance(el);
            off.show();
        });
    </script>

    <script>
        // Accessible FAQ accordion: one panel open at a time, state on aria-expanded + hidden.
        (function() {
            var root = document.getElementById('faqAccordion');
            if (!root) return;
            var buttons = root.querySelectorAll('.kn-faq-q');

            function setOpen(btn, open) {
                var panel = document.getElementById(btn.getAttribute('aria-controls'));
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (panel) panel.hidden = !open;
            }

            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var willOpen = btn.getAttribute('aria-expanded') !== 'true';
                    buttons.forEach(function(other) {
                        if (other !== btn) setOpen(other, false);
                    });
                    setOpen(btn, willOpen);
                });
            });
        })();
    </script>
@endsection
