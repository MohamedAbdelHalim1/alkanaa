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
                <article class="kn-article shipping-policyy">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">سياسات الشحن</h1>
                    </header>
                    <div class="kn-prose">
                        <p>نحن في <strong>القناعة</strong> نلتزم بتوصيل طلباتكم بأعلى جودة وأسرع وقت ممكن. يرجى قراءة سياسات الشحن
                            التالية بعناية:</p>

                        <ul>
                            <li><strong>مدة الشحن:</strong> يتم تجهيز وشحن الطلبات خلال 2 - 5 أيام عمل داخل المملكة.</li>
                            <li><strong>شركات الشحن:</strong> نعتمد على شركات شحن موثوقة لضمان وصول طلباتكم بأمان وفي الوقت المحدد.
                            </li>
                            <li><strong>رسوم الشحن:</strong> تختلف رسوم الشحن حسب المدينة والوزن، وتُعرض أثناء إتمام الطلب.</li>
                            <li><strong>تتبع الطلب:</strong> بعد الشحن، يتم إرسال رقم تتبع للعميل عبر البريد الإلكتروني أو رسالة
                                نصية.</li>
                            <li><strong>مناطق التوصيل:</strong> يتم التوصيل لجميع مدن المملكة وبعض الدول المجاورة حسب التوفر.</li>
                            <li><strong>فشل التوصيل:</strong> في حال تعذر التوصيل بسبب خطأ في العنوان أو عدم الرد، يتم التواصل مع
                                العميل لتحديد موعد جديد.</li>
                            <li><strong>طلبات كبيرة أو مخصصة:</strong> قد تتطلب وقتًا إضافيًا للتجهيز، ويتم إبلاغ العميل مسبقًا
                                بالتفاصيل.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>في حال وجود أي استفسارات تتعلق بالشحن، يُرجى التواصل معنا من خلال صفحة <a
                                href="{{ route('contact.us') }}">تواصل معنا</a>.</p>
                    </div>
                </article>
            @elseif(app()->getLocale() == 'cn')
                <article class="kn-article shipping-policyy">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">运输政策</h1>
                    </header>
                    <div class="kn-prose">
                        <p>在 <strong>Al Kanaa</strong>，我们承诺以最高的质量和最快的速度交付您的订单。请仔细阅读以下运输政策：</p>

                        <ul>
                            <li><strong>运输时间：</strong> 订单将在沙特境内 2 - 5 个工作日内准备并发货。</li>
                            <li><strong>运输公司：</strong> 我们依赖值得信赖的运输公司，确保您的订单安全、准时送达。</li>
                            <li><strong>运输费用：</strong> 运输费用根据城市和重量而有所不同，并在结账时显示。</li>
                            <li><strong>订单跟踪：</strong> 发货后，我们会通过电子邮件或短信向客户发送追踪号。</li>
                            <li><strong>送达区域：</strong> 我们可送达沙特所有城市以及部分邻近国家，具体视情况而定。</li>
                            <li><strong>送货失败：</strong> 如果因地址错误或无人接收导致送货失败，我们将联系客户重新安排送货时间。</li>
                            <li><strong>大宗或定制订单：</strong> 这类订单可能需要额外的准备时间，客户将在提前获知详细信息。</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>如对运输有任何疑问，请通过 <a href="{{ route('contact.us') }}">联系我们</a> 页面与我们联系。</p>
                    </div>
                </article>
            @else
                <article class="kn-article shipping-policyy">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">Shipping Policies</h1>
                    </header>
                    <div class="kn-prose">
                        <p>At <strong>Al Kanaa</strong>, we are committed to delivering your orders with the highest quality and as
                            quickly as possible. Please read the following shipping policies carefully:</p>

                        <ul>
                            <li><strong>Shipping Time:</strong> Orders are prepared and shipped within 2 - 5 business days inside
                                the Kingdom.</li>
                            <li><strong>Shipping Companies:</strong> We rely on trusted shipping companies to ensure your orders
                                arrive safely and on time.</li>
                            <li><strong>Shipping Fees:</strong> Shipping fees vary based on city and weight, and are displayed
                                during checkout.</li>
                            <li><strong>Order Tracking:</strong> Once shipped, a tracking number will be sent to the customer via
                                email or SMS.</li>
                            <li><strong>Delivery Areas:</strong> We deliver to all cities within the Kingdom and to some neighboring
                                countries where available.</li>
                            <li><strong>Failed Delivery:</strong> If delivery fails due to an incorrect address or no response, we
                                will contact the customer to arrange a new delivery time.</li>
                            <li><strong>Large or Customized Orders:</strong> These may require additional preparation time, and the
                                customer will be informed in advance of the details.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>If you have any questions regarding shipping, please contact us via the <a
                                href="{{ route('contact.us') }}">Contact Us</a> page.</p>
                    </div>
                </article>
            @endif
        </div>
    </div>
@endsection
