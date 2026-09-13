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
                <h1 class="kn-page-title">{{ translate('maintainence_request') }}</h1>
                <p class="kn-lead">
                    {{ $tr('صف المشكلة وأرفق صورة الفاتورة، وسيتواصل معك فريق الصيانة.', 'Describe the problem and attach your invoice, and our maintenance team will get back to you.', '请描述问题并上传发票，我们的维修团队将与您联系。') }}
                </p>
            </header>

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ $tr('تم إرسال طلبك بنجاح، وسيتم التواصل معك خلال ٢٤ ساعة عمل.', 'Your request has been submitted successfully. We will contact you within 24 business hours.', '您的请求已成功提交，我们将在 24 个工作小时内与您联系。') }}
                </div>
            @endif

            <div class="kn-form-card support-box">
                <h2 class="kn-h3">{{ $tr('ماذا يحدث بعد الإرسال؟', 'What happens next?', '提交后会发生什么？') }}</h2>
                <ul class="kn-next">
                    <li>{{ $tr('أرسل وصف المشكلة وصورة الفاتورة.', 'Send the problem description and invoice image.', '提交问题描述和发票图片。') }}</li>
                    <li>{{ $tr('سيتم التواصل معك خلال ٢٤ ساعة عمل.', 'We will contact you within 24 business hours.', '我们将在 24 个工作小时内与您联系。') }}</li>
                </ul>

                <form id="supportForm" enctype="multipart/form-data" method="POST" action="{{ route('quote.submit') }}">
                    @csrf

                    <fieldset class="kn-fieldset">
                        <legend class="kn-legend">{{ $tr('تفاصيل المشكلة', 'Problem details', '问题详情') }}</legend>
                        <div class="kn-field">
                            <label for="problemDesc" class="kn-label">
                                {{ $tr('وصف المشكلة', 'Problem Description', '问题描述') }}<span class="kn-req" aria-hidden="true">*</span>
                            </label>
                            <textarea id="problemDesc" class="form-control @error('problem_desc') is-invalid @enderror" rows="5" name="problem_desc" required
                                aria-describedby="problemDescHint"
                                placeholder="{{ $tr('مثال: نوع الجهاز، الموديل، ومتى بدأت المشكلة', 'e.g. equipment type, model, and when the problem started', '例如：设备类型、型号以及问题开始的时间') }}">{{ old('problem_desc') }}</textarea>
                            <span id="problemDescHint" class="kn-hint">{{ $tr('كلما كان الوصف أوضح، كان التشخيص أسرع.', 'The clearer the description, the faster the diagnosis.', '描述越清楚，诊断越快。') }}</span>
                            @error('problem_desc')
                                <span class="kn-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>

                    <fieldset class="kn-fieldset">
                        <legend class="kn-legend">{{ $tr('الفاتورة', 'Invoice', '发票') }}</legend>
                        <div class="kn-field">
                            <label for="invoiceFile" class="kn-label">{{ $tr('أرفق صورة الفاتورة', 'Upload Invoice Image', '上传发票图片') }}</label>
                            <div class="kn-file">
                                <span class="kn-file-head"><i class="las la-file-upload" aria-hidden="true"></i>{{ $tr('صورة أو PDF، بحد أقصى 10 ميجابايت.', 'Image or PDF, up to 10 MB.', '图片或 PDF，最大 10 MB。') }}</span>
                                <input type="file" name="invoice_file" id="invoiceFile" accept="image/*, .pdf" />
                            </div>
                            @error('invoice_file')
                                <span class="kn-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>

                    <div class="kn-form-actions">
                        <button type="submit" class="kn-btn kn-btn-primary kn-btn-lg">
                            {{ $tr('إرسال الطلب', 'Submit Request', '提交请求') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="confirmationModalLabel">
                        {{ $tr('تم إرسال الطلب', 'Request Submitted', '请求已提交') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ $tr('إغلاق', 'Close', '关闭') }}"></button>
                </div>
                <div class="modal-body">
                    {{ $tr('تم إرسال طلبك بنجاح، وسيتم التواصل معك خلال ٢٤ ساعة عمل.', 'Your request has been submitted successfully. We will contact you within 24 business hours.', '您的请求已成功提交，我们将在 24 个工作小时内与您联系。') }}
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="kn-btn kn-btn-primary" data-bs-dismiss="modal">
                        {{ $tr('تم', 'OK', '确定') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                myModal.show();
            });
        </script>
    @endif

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
@endsection
