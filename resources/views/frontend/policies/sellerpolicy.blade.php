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
    <meta name="twitter:label1" content="Price">

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
                <article class="kn-article customer-policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">سياسة التعامل مع العملاء</h1>
                    </header>
                    <div class="kn-prose">
                        <p>في <strong>القناعة</strong>، نؤمن بأن العميل هو محور اهتمامنا، ونسعى دومًا لتقديم تجربة متميزة ترتكز على
                            الاحترام، الشفافية، والجودة العالية في الخدمة.</p>

                        <ul class="customer-policy-list">
                            <li><strong>الاحترام واللباقة:</strong> نلتزم بالتعامل مع جميع العملاء باحترام وأدب، ونحرص على تقديم
                                تجربة احترافية وإنسانية.</li>
                            <li><strong>الرد السريع:</strong> نحرص على الرد على استفسارات العملاء خلال فترة زمنية قصيرة (من 1 إلى 2
                                يوم عمل).</li>
                            <li><strong>الشفافية:</strong> نعرض جميع تفاصيل المنتجات والأسعار بوضوح، بما في ذلك أي رسوم إضافية مثل
                                الشحن أو التركيب.</li>
                            <li><strong>حل الشكاوى:</strong> نأخذ جميع الشكاوى والملاحظات بجدية، ونوفر آليات واضحة للتواصل والتصعيد
                                عند الحاجة.</li>
                            <li><strong>السرية:</strong> نحترم خصوصية العملاء ونحافظ على بياناتهم الشخصية، ولا نستخدمها إلا وفقًا
                                لسياسة الخصوصية.</li>
                            <li><strong>التحسين المستمر:</strong> نرحب بملاحظات العملاء كفرصة للتحسين وتطوير الخدمة والمنتجات.</li>
                            <li><strong>العدل والمساواة:</strong> يتم التعامل مع جميع العملاء بنفس المعايير دون تمييز.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>إذا كان لديك أي اقتراح أو شكوى، يسعدنا تواصلك معنا من خلال <a
                                href="{{ route('contact.us') }}">نموذج
                                التواصل</a>.</p>
                    </div>
                </article>
            @elseif(app()->getLocale() == 'cn')
                <article class="kn-article customer-policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">客户服务政策</h1>
                    </header>
                    <div class="kn-prose">
                        <p>在 <strong>Al Kanaa</strong>，我们始终相信客户是我们关注的核心。我们致力于提供基于尊重、透明和高质量服务的卓越体验。</p>

                        <ul class="customer-policy-list">
                            <li><strong>尊重与礼貌：</strong> 我们承诺以尊重和礼貌对待所有客户，确保提供专业且人性化的体验。</li>
                            <li><strong>快速回应：</strong> 我们努力在较短时间内（1至2个工作日）回复客户的咨询。</li>
                            <li><strong>透明度：</strong> 我们清晰展示所有产品详情和价格，包括任何额外费用，如运输或安装费用。</li>
                            <li><strong>投诉处理：</strong> 我们认真对待所有投诉和反馈，并提供明确的沟通和升级机制。</li>
                            <li><strong>保密性：</strong> 我们尊重客户隐私，并保护其个人数据，仅在隐私政策范围内使用。</li>
                            <li><strong>持续改进：</strong> 我们欢迎客户的反馈，将其视为改进和优化服务与产品的机会。</li>
                            <li><strong>公平与平等：</strong> 我们承诺平等对待所有客户，不存在任何歧视。</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>如果您有任何建议或投诉，欢迎通过我们的 <a href="{{ route('contact.us') }}">联系表单</a> 与我们联系。</p>
                    </div>
                </article>
            @else
                <article class="kn-article customer-policy-box">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">Customer Service Policy</h1>
                    </header>
                    <div class="kn-prose">
                        <p>At <strong>Al Kanaa</strong>, we believe that the customer is at the heart of everything we do. We
                            strive to deliver an exceptional experience based on respect, transparency, and high-quality service.
                        </p>

                        <ul class="customer-policy-list">
                            <li><strong>Respect and Courtesy:</strong> We are committed to treating all customers with respect and
                                courtesy, ensuring a professional and human experience.</li>
                            <li><strong>Quick Response:</strong> We aim to respond to customer inquiries within a short period (1 to
                                2 business days).</li>
                            <li><strong>Transparency:</strong> We display all product details and prices clearly, including any
                                additional fees such as shipping or installation.</li>
                            <li><strong>Complaint Resolution:</strong> We take all complaints and feedback seriously and provide
                                clear mechanisms for communication and escalation when necessary.</li>
                            <li><strong>Confidentiality:</strong> We respect customer privacy and protect their personal data, using
                                it only in accordance with our privacy policy.</li>
                            <li><strong>Continuous Improvement:</strong> We welcome customer feedback as an opportunity for
                                improvement and development of our services and products.</li>
                            <li><strong>Fairness and Equality:</strong> All customers are treated equally without any
                                discrimination.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>If you have any suggestions or complaints, we would be happy to hear from you through our
                            <a href="{{ route('contact.us') }}">Contact Form</a>.</p>
                    </div>
                </article>
            @endif
        </div>
    </div>
@endsection
