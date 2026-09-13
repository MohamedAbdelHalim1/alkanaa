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
                <article class="kn-article warranty-section">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">سياسة الضمان</h1>
                    </header>
                    <div class="kn-prose">
                        <p>نحن في <strong>القناعة</strong> نؤمن بجودة منتجاتنا ونسعى لتقديم أفضل تجربة ممكنة لعملائنا. وتوضح هذه
                            السياسة شروط وأحكام الضمان على المنتجات:</p>

                        <ul class="warranty-list">
                            <li><strong>مدة الضمان:</strong> تختلف مدة الضمان حسب نوع المنتج، وتتراوح عادة بين 12 إلى 24 شهرًا من
                                تاريخ الشراء.</li>
                            <li><strong>التغطية:</strong> يغطي الضمان عيوب الصناعة أو أي خلل في المواد أو التصنيع.</li>
                            <li><strong>الاستثناءات:</strong> لا يغطي الضمان الأعطال الناتجة عن سوء الاستخدام أو التركيب الخاطئ أو
                                الكسر أو التلف الناتج عن الحوادث.</li>
                            <li><strong>شروط التفعيل:</strong> يجب الاحتفاظ بفاتورة الشراء الأصلية وتقديمها عند طلب خدمة الضمان.
                            </li>
                            <li><strong>الإصلاح أو الاستبدال:</strong> في حال ثبوت العطل، يتم إصلاح المنتج أو استبداله حسب التقييم
                                الفني دون رسوم إضافية.</li>
                            <li><strong>مراكز الخدمة:</strong> يتم تقديم خدمات الضمان من خلال مراكز الخدمة المعتمدة أو من خلال
                                التواصل معنا مباشرة.</li>
                            <li><strong>المنتجات خارج الضمان:</strong> يمكن تقديم خدمة مدفوعة للمنتجات التي انتهت مدة ضمانها.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>لأي استفسارات أو طلبات ضمان، يرجى التواصل مع خدمة العملاء عبر <a
                                href="{{ route('contact.us') }}">نموذج
                                التواصل</a>.</p>
                    </div>
                </article>
            @elseif(app()->getLocale() == 'cn')
                <article class="kn-article warranty-section">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">保修政策</h1>
                    </header>
                    <div class="kn-prose">
                        <p>在 <strong>Al Kanaa</strong>，我们相信我们产品的质量，并努力为客户提供最佳体验。本政策阐述了产品保修的条款和条件：</p>

                        <ul class="warranty-list">
                            <li><strong>保修期：</strong> 保修期因产品类型而异，通常为自购买之日起 12 至 24 个月。</li>
                            <li><strong>保修范围：</strong> 保修涵盖制造缺陷或材料或工艺方面的任何故障。</li>
                            <li><strong>不包括：</strong> 保修不包括因误用、安装不当、破损或事故造成的损坏所导致的问题。</li>
                            <li><strong>激活条件：</strong> 必须保留原始购买发票，并在申请保修服务时出示。</li>
                            <li><strong>维修或更换：</strong> 若确认存在故障，将根据技术评估对产品进行维修或更换，且不收取额外费用。</li>
                            <li><strong>服务中心：</strong> 保修服务通过授权服务中心或直接与我们联系提供。</li>
                            <li><strong>超出保修期的产品：</strong> 对于超出保修期的产品，可提供付费服务。</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>如有任何疑问或保修请求，请通过我们的 <a href="{{ route('contact.us') }}">联系表单</a> 联系客户服务。</p>
                    </div>
                </article>
            @else
                <article class="kn-article warranty-section">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">Warranty Policy</h1>
                    </header>
                    <div class="kn-prose">
                        <p>At <strong>Al Kanaa</strong>, we believe in the quality of our products and strive to offer the best
                            possible experience to our customers. This policy outlines the terms and conditions of our product
                            warranty:</p>

                        <ul class="warranty-list">
                            <li><strong>Warranty Period:</strong> The warranty period varies depending on the type of product,
                                typically ranging from 12 to 24 months from the date of purchase.</li>
                            <li><strong>Coverage:</strong> The warranty covers manufacturing defects or any faults in materials or
                                workmanship.</li>
                            <li><strong>Exclusions:</strong> The warranty does not cover malfunctions resulting from misuse,
                                incorrect installation, breakage, or damage caused by accidents.</li>
                            <li><strong>Activation Requirements:</strong> The original purchase invoice must be kept and presented
                                when requesting warranty service.</li>
                            <li><strong>Repair or Replacement:</strong> If a defect is confirmed, the product will be repaired or
                                replaced according to the technical assessment at no additional cost.</li>
                            <li><strong>Service Centers:</strong> Warranty services are provided through authorized service centers
                                or by contacting us directly.</li>
                            <li><strong>Out-of-Warranty Products:</strong> Paid services are available for products whose warranty
                                period has expired.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>For any inquiries or warranty requests, please contact customer service via our <a
                                href="{{ route('contact.us') }}">Contact Form</a>.</p>
                    </div>
                </article>
            @endif
        </div>
    </div>
@endsection
