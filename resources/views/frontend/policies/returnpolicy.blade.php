@extends('frontend.layouts.app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">
            @if (app()->getLocale() == 'sa')
                <article class="kn-article policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">سياسة الاسترجاع والاستبدال</h1>
                    </header>
                    <div class="kn-prose">
                        <p>نحرص في <strong>القناعة</strong> على رضا عملائنا، لذا نقدم سياسة مرنة لاسترجاع واستبدال المنتجات وفقًا
                            للشروط التالية:</p>

                        <ul>
                            <li><strong>مدة الاسترجاع:</strong> يمكن استرجاع أو استبدال المنتج خلال 7 أيام من تاريخ الاستلام.</li>
                            <li><strong>حالة المنتج:</strong> يجب أن يكون المنتج في حالته الأصلية وغير مستخدم، وبجميع ملحقاته
                                وتغليفه الأصلي.</li>
                            <li><strong>الإثبات:</strong> يجب تقديم الفاتورة أو إثبات الشراء مع طلب الاسترجاع أو الاستبدال.</li>
                            <li><strong>المنتجات غير القابلة للإرجاع:</strong> المنتجات المصنّعة حسب الطلب، أو التي تعرضت للتلف بسبب
                                سوء الاستخدام.</li>
                            <li><strong>رسوم الشحن:</strong> يتحمّل العميل رسوم الشحن في حال كان سبب الاسترجاع غير متعلق بعيب في
                                المنتج.</li>
                            <li><strong>الاسترجاع المالي:</strong> يتم استرداد المبلغ بنفس وسيلة الدفع خلال 5 - 10 أيام عمل بعد
                                الموافقة.</li>
                            <li><strong>الاستبدال:</strong> يمكن استبدال المنتج بمنتج آخر من نفس الفئة أو بنفس القيمة، حسب التوفر.
                            </li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>للتواصل بشأن الاسترجاع أو الاستبدال، يرجى زيارة صفحة <a
                                href="{{ route('contact.us') }}">تواصل معنا</a>
                            أو الاتصال بخدمة العملاء.</p>
                    </div>
                </article>
            @elseif(app()->getLocale() == 'cn')
                <article class="kn-article policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">退换货政策</h1>
                    </header>
                    <div class="kn-prose">
                        <p>在 <strong>Al Kanaa</strong>，我们非常重视客户的满意度。因此，我们在以下条件下提供灵活的退换货政策：</p>

                        <ul>
                            <li><strong>退换期限：</strong> 商品可在收到之日起 7 天内退货或换货。</li>
                            <li><strong>商品状态：</strong> 商品必须保持原始状态、未使用，并附带所有配件及原包装。</li>
                            <li><strong>凭证：</strong> 退换货时必须提供发票或购买凭证。</li>
                            <li><strong>不可退换商品：</strong> 定制产品或因误用而损坏的产品无法退换。</li>
                            <li><strong>运费：</strong> 若退货原因与产品缺陷无关，客户需自行承担运费。</li>
                            <li><strong>退款：</strong> 经批准后，款项将在 5 - 10 个工作日内通过原支付方式退还。</li>
                            <li><strong>换货：</strong> 商品可更换为同类或同等价值的其他商品，视库存情况而定。</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>如需咨询退换货事宜，请访问 <a href="{{ route('contact.us') }}">联系我们</a> 页面或致电客服。</p>
                    </div>
                </article>
            @else
                <article class="kn-article policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">Return &amp; Exchange Policy</h1>
                    </header>
                    <div class="kn-prose">
                        <p>At <strong>Al Kanaa</strong>, we care about our customers' satisfaction. Therefore, we offer a flexible
                            return and exchange policy under the following conditions:</p>

                        <ul>
                            <li><strong>Return Period:</strong> Products can be returned or exchanged within 7 days from the date of
                                receipt.</li>
                            <li><strong>Product Condition:</strong> The product must be in its original condition, unused, and with
                                all its accessories and original packaging.</li>
                            <li><strong>Proof:</strong> The invoice or proof of purchase must be provided with the return or
                                exchange request.</li>
                            <li><strong>Non-Returnable Products:</strong> Customized products or products damaged due to misuse
                                cannot be returned.</li>
                            <li><strong>Shipping Fees:</strong> The customer bears the shipping costs if the return reason is not
                                related to a defect in the product.</li>
                            <li><strong>Refund:</strong> The amount will be refunded via the same payment method within 5 - 10
                                working days after approval.</li>
                            <li><strong>Exchange:</strong> The product can be exchanged for another item from the same category or
                                of the same value, subject to availability.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>To inquire about returns or exchanges, please visit the <a
                                href="{{ route('contact.us') }}">Contact Us</a> page or call customer service.</p>
                    </div>
                </article>
            @endif
        </div>
    </div>
@endsection
