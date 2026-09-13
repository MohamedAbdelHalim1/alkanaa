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
                <article class="kn-article privacy-wrapper">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">سياسة الخصوصية</h1>
                    </header>
                    <div class="kn-prose">
                        <p>في <strong>القناعة</strong>، نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. توضح هذه السياسة كيف نجمع
                            ونستخدم ونحمي المعلومات التي تقدمها لنا عند استخدامك لموقعنا.</p>

                        <ul class="privacy-list">
                            <li><strong>جمع المعلومات:</strong> نقوم بجمع بعض البيانات الأساسية مثل الاسم، البريد الإلكتروني، رقم
                                الهاتف، ومعلومات الدفع عند تقديم طلب عبر الموقع.</li>
                            <li><strong>استخدام المعلومات:</strong> تُستخدم المعلومات لتحسين تجربة المستخدم، تنفيذ الطلبات، التواصل
                                بشأن الطلبات، وإرسال العروض والخدمات.</li>
                            <li><strong>سرية البيانات:</strong> لا نقوم ببيع أو مشاركة بياناتك مع أي طرف ثالث دون موافقتك، باستثناء
                                الجهات التي تساعد في تنفيذ الطلب (مثل شركات الشحن).</li>
                            <li><strong>أمان البيانات:</strong> نستخدم تقنيات حديثة لحماية بياناتك من الوصول أو الاستخدام غير المصرح
                                به.</li>
                            <li><strong>ملفات تعريف الارتباط (Cookies):</strong> نستخدم الكوكيز لتحسين أداء الموقع وتخصيص تجربة
                                المستخدم.</li>
                            <li><strong>حقوق المستخدم:</strong> يمكنك طلب تعديل أو حذف بياناتك في أي وقت من خلال التواصل معنا.</li>
                            <li><strong>تحديث السياسة:</strong> قد نقوم بتحديث هذه السياسة من وقت لآخر، وسيتم الإعلان عن ذلك في
                                الموقع.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>إذا كان لديك أي استفسار بخصوص سياسة الخصوصية، يرجى التواصل معنا عبر <a
                                href="{{ route('contact.us') }}">نموذج الاتصال</a>.</p>
                    </div>
                </article>
            @elseif(app()->getLocale() == 'cn')
                <article class="kn-article privacy-wrapper">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">隐私政策</h1>
                    </header>
                    <div class="kn-prose">
                        <p>在 <strong>Al Kanaa</strong>，我们尊重您的隐私，并致力于保护您的个人数据。本政策说明我们如何在您使用我们网站时收集、使用和保护您提供的信息。</p>

                        <ul class="privacy-list">
                            <li><strong>信息收集：</strong> 我们会在您通过网站下订单时收集基本数据，例如姓名、电子邮件地址、电话号码以及支付信息。</li>
                            <li><strong>信息使用：</strong> 我们将信息用于提升用户体验、处理订单、就订单进行沟通以及发送优惠和服务。</li>
                            <li><strong>数据保密：</strong> 除了帮助完成订单的相关方（如运输公司）外，未经您同意，我们不会出售或与任何第三方共享您的数据。</li>
                            <li><strong>数据安全：</strong> 我们采用现代技术保护您的数据，防止未经授权的访问或使用。</li>
                            <li><strong>曲奇饼:</strong> 我们使用 曲奇饼 来提升网站性能并个性化用户体验。</li>
                            <li><strong>用户权利：</strong> 您可以随时联系我们，要求修改或删除您的数据。</li>
                            <li><strong>政策更新：</strong> 我们可能会不时更新此政策，任何更改将在网站上公布。</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>如果您对隐私政策有任何疑问，请通过 <a href="{{ route('contact.us') }}">联系表单</a> 与我们联系。</p>
                    </div>
                </article>
            @else
                <article class="kn-article privacy-wrapper">
                    <header class="kn-page-head">
                        <h1 class="kn-page-title">Privacy Policy</h1>
                    </header>
                    <div class="kn-prose">
                        <p>At <strong>Al Kanaa</strong>, we respect your privacy and are committed to protecting your personal
                            data. This policy explains how we collect, use, and protect the information you provide to us when using
                            our website.</p>

                        <ul class="privacy-list">
                            <li><strong>Information Collection:</strong> We collect basic data such as name, email address, phone
                                number, and payment information when you place an order through the website.</li>
                            <li><strong>Use of Information:</strong> Information is used to enhance user experience, process orders,
                                communicate regarding orders, and send offers and services.</li>
                            <li><strong>Data Confidentiality:</strong> We do not sell or share your data with any third party
                                without your consent, except for entities that help fulfill the order (like shipping companies).
                            </li>
                            <li><strong>Data Security:</strong> We use modern technologies to protect your data from unauthorized
                                access or misuse.</li>
                            <li><strong>Cookies:</strong> We use cookies to improve website performance and personalize the user
                                experience.</li>
                            <li><strong>User Rights:</strong> You can request to modify or delete your data at any time by
                                contacting us.</li>
                            <li><strong>Policy Updates:</strong> We may update this policy from time to time, and any changes will
                                be announced on the website.</li>
                        </ul>
                    </div>
                    <div class="kn-article-foot">
                        <p>If you have any questions regarding the privacy policy, please contact us via the <a
                                href="{{ route('contact.us') }}">contact form</a>.</p>
                    </div>
                </article>
            @endif
        </div>
    </div>
@endsection
