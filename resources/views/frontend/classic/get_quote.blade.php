@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);

    $steps = [
        [
            $tr('تصفح المنتجات', 'Browse Products', '浏览产品'),
            $tr('تجوّل بين أقسام الموقع واختر المنتجات التي تلبّي احتياجاتك وتثير اهتمامك.', 'Explore the site sections and choose the products that meet your needs and spark your interest.', '浏览网站各个分类，选择满足您需求并引起您兴趣的产品。'),
        ],
        [
            $tr('أضف إلى السلة', 'Add to Cart', '加入购物车'),
            $tr('اضغط على زر "أضف إلى السلة" لكل منتج ترغب في الاستعلام عن سعره أو طلبه لاحقًا.', 'Click the "Add to Cart" button for each product you want to inquire about or order later.', '点击每个您想了解价格或稍后购买的商品旁边的“加入购物车”按钮。'),
        ],
        [
            $tr('انتقل إلى السلة', 'Go to Cart', '前往购物车'),
            $tr('بعد الانتهاء من اختيار المنتجات، انتقل إلى صفحة السلة لمراجعة الطلب بالكامل.', "Once you've selected your products, go to the cart page to review your full order.", '选择好产品后，前往购物车页面查看完整订单。'),
        ],
        [
            $tr('اختر "طلب عرض سعر"', 'Select "Request a Quote"', '选择“请求报价”'),
            $tr('في صفحة السلة .. قم بالضغط على طلب عرض السعر.', 'On the cart page, click on Request a Quote.', '在购物车页面，点击 请求报价。'),
        ],
        [
            $tr('أرسل الطلب', 'Submit Your Request', '提交请求'),
            $tr('بمجرد ارسال الطلب ، سيصل اليك عرض السعر عبر البريد الالكتروني المسجل للحساب', 'Once the request is submitted, the quote will be sent to the email address registered to your account.', '提交请求后，报价将发送至您账户注册的电子邮箱。'),
        ],
    ];

    $notes = [
        $tr('لا يترتب عليك أي التزام عند طلب عرض السعر.', 'There is no obligation when requesting a quotation.', '请求报价无需承担任何义务。'),
        $tr('يمكنك التواصل معنا في أي وقت لمزيد من الاستفسارات.', 'You can contact us anytime for further inquiries.', '您可以随时联系我们进行进一步咨询。'),
        $tr('تأكد من إدخال بريد إلكتروني صحيح ليصل اليك عرض السعر', 'Make sure to enter a valid email address to receive the quotation.', '请确保输入有效的电子邮箱地址，以便及时接收详情。'),
        $tr('يرجى التأكد من إدخال اسم الشركة، السجل التجاري، والرقم الضريبي بشكل صحيح', 'Please make sure to enter the company name, commercial registration, and tax number correctly.', '请确保正确填写公司名称、商业注册号和税号。'),
    ];
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap kn-narrow">
            <header class="kn-page-head">
                <h1 class="kn-page-title">{{ $tr('عروض الأسعار', 'Price Quotations', '价格报价') }}</h1>
                <p class="kn-lead">
                    {{ $tr('نوفر لك إمكانية طلب عرض سعر مخصص للمنتجات التي تختارها بسهولة وسرعة.', 'We provide you with the ability to request a customized price quotation for the products you choose easily and quickly.', '我们为您提供方便快捷的方式，为您选择的产品请求定制报价。') }}
                </p>
            </header>

            <div class="kn-stack">
                <section class="kn-form-card" aria-labelledby="kn-quote-steps">
                    <h2 id="kn-quote-steps" class="kn-h2">{{ $tr('خطوات طلب عرض السعر', 'How to request a quote', '如何请求报价') }}</h2>
                    <ol class="kn-steps">
                        @foreach ($steps as $step)
                            <li class="kn-step">
                                <span class="kn-step-title">{{ $step[0] }}</span>
                                <span class="kn-step-text">{{ $step[1] }}</span>
                            </li>
                        @endforeach
                    </ol>
                    <div class="kn-actions">
                        <a href="{{ route('search') }}" class="kn-btn kn-btn-primary kn-btn-lg">{{ $tr('العودة لصفحة المنتجات', 'Back to Products Page', '返回产品页面') }}</a>
                        <a href="{{ route('cart') }}" class="kn-btn kn-btn-ghost kn-btn-lg">{{ $tr('الذهاب إلى السلة', 'Go to cart', '前往购物车') }}</a>
                    </div>
                </section>

                <section class="kn-panel kn-panel-steel" aria-labelledby="kn-quote-notes">
                    <h2 id="kn-quote-notes" class="kn-h3">{{ $tr('ملاحظات مهمة', 'Important Notes', '重要注意事项') }}</h2>
                    <ul class="kn-checks">
                        @foreach ($notes as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>
                </section>

                <div class="kn-faq-cta kn-panel">
                    <div>
                        <h2 class="kn-h3">{{ $tr('هل لديك أي أسئلة؟', 'Do you have any questions?', '有任何问题吗？') }}</h2>
                        <p class="kn-text kn-muted">{{ $tr('لا تتردد في التواصل معنا عبر صفحة', 'Feel free to contact us via our', '欢迎通过我们的') }} <a href="{{ route('contact.us') }}">{{ $tr('اتصل بنا', 'Contact Us', '联系我们') }}</a></p>
                    </div>
                </div>
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
@endsection
