@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);

    $requirements = $isAr
        ? [
            'توضيح المساحة الحالية للمطبخ بالرسم.',
            'تحديد أماكن الأبواب والنوافذ.',
            'تحديد مواقع توصيلات الكهرباء والمياه.',
            'تحديد أماكن الأجهزة والمعدات بالمطبخ.',
            'تحديد مساحة صالة الطعام.',
            'تقديم الملفات بصيغة صورة أو PDF واضحة ومقروءة.',
            'إذا كنت تطلب رفع المساحة لدينا، يرجى طلب ذلك مسبقًا.',
        ]
        : ($isCn
            ? [
                '用图纸描述现有厨房空间。',
                '标识门窗位置。',
                '标识电力和供水点。',
                '标识厨房设备和家具。',
                '标识用餐区域。',
                '提供清晰可读的 PDF 或图片文件。',
                '如需测绘服务，请提前提出申请。',
            ]
            : [
                'Describe the current kitchen space in drawing form.',
                'Identify the doors and windows.',
                'Identify the electricity and water supply points.',
                'Identify the kitchen appliances and furniture.',
                'Identify the dining area.',
                'Provide clear and readable files in PDF or image format.',
                'If you require surveying services, please request it in advance.',
            ]);
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">

            {{-- Success message --}}
            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ $tr('تم إرسال طلبك بنجاح.', 'Your request has been submitted successfully.', '您的请求已成功提交。') }}
                </div>
            @endif

            <div class="kn-narrow">
                <header class="kn-page-head">
                    <h1 class="kn-page-title">{{ $tr('ما هي الخدمات الهندسية؟', 'What are Engineering Services?', '什么是工程服务？') }}</h1>
                    <p class="kn-lead">
                        {{ $tr(
                            'الخدمات الهندسية هي مجموعة من الرسومات المخططات التي تضمن تنفيذ المشروع بطريقة صحيحة وآمنة، وتشمل الاتي:.',
                            'Engineering services are a set of drawings and plans that ensure the project is executed correctly and safely, including the following:.',
                            '工程服务是一系列图纸和规划，确保项目的正确和安全实施，包括电力、燃气、通风等方面。'
                        ) }}
                    </p>
                </header>

                <div class="kn-stack">
                    <ul class="kn-next">
                        <li>{{ $tr('أضف الخدمة المناسبة إلى السلة وأكمل طلبك.', 'Add the right service to your cart and complete your order.', '将合适的服务加入购物车并完成订单。') }}</li>
                        <li>{{ $tr('جهّز الملفات المطلوبة أدناه بصيغة صورة أو PDF.', 'Prepare the files listed below as images or PDF.', '按以下要求准备图片或 PDF 文件。') }}</li>
                    </ul>

                    <section class="kn-panel kn-panel-steel instructions" aria-labelledby="kn-service-reqs">
                        <h2 id="kn-service-reqs" class="kn-h3">
                            {{ $tr('الرجاء مراجعة المتطلبات التالية قبل تقديم الخدمة', 'Please review the following requirements before submitting the service', '请在提交请求之前审阅以下要求') }}
                        </h2>
                        <ul class="kn-checks">
                            @foreach ($requirements as $requirement)
                                <li>{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </section>
                </div>
            </div>

            <section class="kn-section" aria-labelledby="kn-service-packages">
                <div class="kn-section-head">
                    <h2 id="kn-service-packages" class="kn-section-title">{{ $tr('باقات الخدمات الهندسية', 'Engineering service packages', '工程服务套餐') }}</h2>
                </div>
                <div class="kn-products">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>

            {{--
            <form action="{{ route('service-request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="gallery-grid">

                            @foreach ($products as $key => $product)
                                <!-- منتج -->
                                <div class="product-box position-relative" style="cursor: pointer" data-bs-target="#productModal{{ $key }}" data-bs-toggle="modal">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" style="width: 100%"
                                        alt="Product">
                                    <div class="product-title">{{ $product->getTranslation('name') }}</div>
                                    <div class="product-price">
                                        {{ single_price($product->unit_price - $product->discount) }} </div>
                                    <div class="product-check position-absolute bottom-0 start-50 translate-middle-x mb-2">
                                        <input type="checkbox" @if ($key == 0) checked @endif
                                            id="check1" value="{{ $product->id }}" name="products[]">
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="productModal{{ $key }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered bg-white" style="max-height: 400px; max-width: 600px; border-radius: 30px;">
                                        <div class="modal-content" style="margin: 0% auto; padding: 0px;">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="productModalTitle">{{ $product->getTranslation('name') }}</h5>
                                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="productModalDesc" class="mb-3">{!! $product->getTranslation('description') !!}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach


                        </div>

                    </div>

                </div>

                <div>
                    <label for="file">
                        @if (app()->getLocale() == 'sa')
                            ارفع ملف (صورة أو PDF):
                        @elseif(app()->getLocale() == 'cn')
                            上传文件 (图片或 PDF):
                        @elseif(app()->getLocale() == 'en')
                            Upload File (Image or PDF):
                        @endif
                    </label>
                    <input type="file" id="file" name="file" accept=".pdf,image/*" required>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="submit-form-button w-25">
                        @if (app()->getLocale() == 'sa')
                            ارسال
                        @elseif(app()->getLocale() == 'cn')
                            提交
                        @elseif(app()->getLocale() == 'en')
                            Submit
                        @endif
                    </button>
                </div>

            </form> --}}
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
@endsection
