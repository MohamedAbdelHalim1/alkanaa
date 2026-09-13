@php
    $fLocale = App::getLocale();
    $fIsAr = in_array($fLocale, ['sa', 'ar', 'eg']);
    $fIsCn = $fLocale == 'cn';
    // Three-way copy: Arabic / English / Chinese.
    $ft = fn ($ar, $en, $cn = null) => $fIsAr ? $ar : ($fIsCn && $cn ? $cn : $en);

    $footerLogo = get_setting('footer_logo') ?: get_setting('header_logo');
    $siteName = get_setting('website_name') ?? 'AlKanaa';
    $footerPhone = get_setting('contact_phone') ?? '966565124444';
    $footerEmail = get_setting('contact_email');
    $whatsapp = get_setting('whatsapp_number') ?: '966565124444';

    $widget_one_labels = json_decode(get_setting('widget_one_labels', null, $fLocale), true);
    $widget_one_links = json_decode(get_setting('widget_one_links'), true);

    $aboutLinks = [];
    if ($widget_one_labels && count($widget_one_labels)) {
        foreach ($widget_one_labels as $key => $label) {
            $aboutLinks[] = ['label' => $label, 'url' => $widget_one_links[$key] ?? '#'];
        }
    } else {
        $aboutLinks = [
            ['label' => $ft('عن متجر القناعة', 'About AlKanaa Store', '关于 AlKanaa 商店'), 'url' => route('about.us')],
            ['label' => $ft('المدونة', 'Blog', '博客'), 'url' => route('blog')],
            ['label' => $ft('تواصل معنا', 'Contact us', '联系我们'), 'url' => route('contact.us')],
            ['label' => $ft('الأسئلة الشائعة', 'FAQs', '常见问题'), 'url' => route('faq')],
        ];
    }

    $accountLinks = [
        auth()->check()
            ? ['label' => $ft('حسابي', 'My account', '我的账户'), 'url' => route('dashboard')]
            : ['label' => $ft('تسجيل الدخول', 'Log in', '登录'), 'url' => route('user.login')],
        ['label' => $ft('سلة التسوق', 'Shopping cart', '购物车'), 'url' => route('cart')],
        ['label' => $ft('طلباتي', 'My orders', '我的订单'), 'url' => route('purchase_history.index')],
        ['label' => $ft('تتبع طلبك', 'Track your order', '订单追踪'), 'url' => route('orders.track')],
    ];

    $serviceLinks = [
        ['label' => $ft('طلب عرض سعر', 'Request a quote', '询价'), 'url' => route('get-a-quote')],
        ['label' => $ft('الخدمات الهندسية', 'Engineering services', '工程服务'), 'url' => route('service-request')],
        ['label' => $ft('خدمات الصيانة', 'Maintenance services', '维修服务'), 'url' => route('maintainence-request')],
        ['label' => $ft('شركاء النجاح', 'Our partners', '合作伙伴'), 'url' => route('all-our-partners')],
    ];

    $policyLinks = [
        ['label' => $ft('سياسة الشحن', 'Shipping policy', '运输政策'), 'url' => route('terms')],
        ['label' => $ft('سياسة الخصوصية', 'Privacy policy', '隐私政策'), 'url' => route('privacypolicy')],
        ['label' => $ft('سياسة الضمان', 'Warranty policy', '保修政策'), 'url' => route('supportpolicy')],
        ['label' => $ft('الاسترجاع والاستبدال', 'Returns & exchanges', '退换政策'), 'url' => route('returnpolicy')],
        ['label' => $ft('سياسة التعامل مع العملاء', 'Customer service policy', '客户服务政策'), 'url' => route('sellerpolicy')],
    ];

    $columns = [
        ['title' => get_setting('footer_title', null, $fLocale) ?? $ft('عن القناعة', 'About AlKanaa', '关于 AlKanaa'), 'links' => $aboutLinks],
        ['title' => $ft('حسابك', 'Your account', '您的账户'), 'links' => $accountLinks],
        ['title' => $ft('خدماتنا', 'Our services', '我们的服务'), 'links' => $serviceLinks],
        ['title' => $ft('السياسات والأحكام', 'Policies & terms', '政策'), 'links' => $policyLinks],
    ];

    $socials = [
        ['setting' => 'facebook_link', 'icon' => 'fa-brands fa-facebook-f', 'name' => 'Facebook'],
        ['setting' => 'instagram_link', 'icon' => 'fa-brands fa-instagram', 'name' => 'Instagram'],
        ['setting' => 'twitter_link', 'icon' => 'fa-brands fa-x-twitter', 'name' => 'X'],
        ['setting' => 'snapchat_link', 'icon' => 'fa-brands fa-snapchat', 'name' => 'Snapchat'],
        ['setting' => 'linkedin_link', 'icon' => 'fa-brands fa-linkedin-in', 'name' => 'LinkedIn'],
        ['setting' => 'youtube_link', 'icon' => 'fa-brands fa-youtube', 'name' => 'YouTube'],
        ['setting' => 'threads_link', 'icon' => 'fa-brands fa-threads', 'name' => 'Threads'],
    ];
    $activeSocials = array_filter($socials, fn ($s) => get_setting($s['setting']));
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp);
@endphp

<footer class="site-footer {{ $fIsAr ? 'rtl' : 'ltr' }}">
    <div class="kn-waves" aria-hidden="true"></div>
    <div class="kn-wrap">
        {{-- Brand + direct contact: the first thing a buyer of big equipment needs --}}
        <div class="kn-foot-top">
            <div class="kn-foot-brand">
                <a href="{{ route('home') }}" class="kn-foot-logo" aria-label="{{ $siteName }}">
                    @if ($footerLogo)
                        {{-- Full-colour mark on a white tile: the blue and red waves are the brand. --}}
                        <span class="kn-foot-mark"><img src="{{ uploaded_asset($footerLogo) }}" alt="" loading="lazy"></span>
                    @endif
                    <span class="kn-foot-name">
                        <strong>{{ $ft('القناعة', 'AlKanaa', 'AlKanaa') }}</strong>
                        <small>{{ $ft('مصانع القناعة المحدودة', "Al Qana'a Factories Co. Ltd.", "Al Qana'a Factories Co. Ltd.") }}</small>
                    </span>
                </a>
                <p class="kn-foot-about">
                    {{ $ft('القناعة مصنع سعودي لمعدات المطابخ التجارية والمطاعم والمقاهي، بخبرة تمتد لأكثر من 60 عامًا.', 'AlKanaa is a Saudi manufacturer of commercial kitchen, restaurant and café equipment with more than 60 years of experience.', 'AlKanaa 是沙特商用厨房、餐厅和咖啡馆设备制造商，拥有 60 多年经验。') }}
                </p>
            </div>

            <ul class="kn-foot-contact">
                <li>
                    <a href="tel:{{ $footerPhone }}" class="kn-foot-chip">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <span>
                            <small>{{ $ft('اتصل بنا', 'Call us', '致电我们') }}</small>
                            <bdi dir="ltr">{{ $footerPhone }}</bdi>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="kn-foot-chip">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                        <span>
                            <small>{{ $ft('واتساب', 'WhatsApp', 'WhatsApp') }}</small>
                            <bdi>{{ $ft('راسلنا الآن', 'Message us', '给我们留言') }}</bdi>
                        </span>
                    </a>
                </li>
                @if ($footerEmail)
                    <li>
                        <a href="mailto:{{ $footerEmail }}" class="kn-foot-chip">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <span>
                                <small>{{ $ft('البريد الإلكتروني', 'Email', '电子邮件') }}</small>
                                <bdi dir="ltr">{{ $footerEmail }}</bdi>
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Link groups: columns on desktop, collapsible sections on phones --}}
        <div class="kn-foot-cols">
            @foreach ($columns as $column)
                <details class="kn-foot-group" @if ($loop->first) data-open-mobile @endif>
                    <summary class="kn-foot-title">
                        <span>{{ $column['title'] }}</span>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </summary>
                    <ul class="kn-foot-links">
                        @foreach ($column['links'] as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </details>
            @endforeach

            <div class="kn-foot-group kn-foot-seller">
                <h2 class="kn-foot-title is-static">{{ $ft('بِع منتجاتك معنا', 'Sell with us', '与我们一起销售') }}</h2>
                <p class="kn-foot-note">{{ $ft('سجّل كبائع وابدأ في بيع منتجاتك اليوم.', 'Register as a seller and start selling today.', '注册成为卖家，今天就开始销售。') }}</p>
                <a href="{{ route('seller.login') }}" class="kn-btn kn-btn-primary kn-foot-cta">{{ $ft('سجّل كبائع', 'Register as a seller', '注册成为卖家') }}</a>

                @if (count($activeSocials))
                    <p class="kn-foot-follow">{{ $ft('تابعنا', 'Follow us', '关注我们') }}</p>
                    <ul class="kn-foot-social">
                        @foreach ($activeSocials as $social)
                            <li>
                                <a href="{{ get_setting($social['setting']) }}" target="_blank" rel="noopener" aria-label="{{ $social['name'] }}">
                                    <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="kn-foot-legal">
            <p>
                <span>{{ $ft('الرقم الضريبي', 'VAT number', '税号') }}: <bdi dir="ltr">{{ get_setting('tax_number') ?? '300036125800003' }}</bdi></span>
                <span>{{ $ft('السجل التجاري', 'Commercial registration', '商业注册号') }}: <bdi dir="ltr">{{ get_setting('commercial_registration_number') ?? '700318115' }}</bdi></span>
            </p>
            <p>© {{ date('Y') }} {{ $ft('متجر القناعة. جميع الحقوق محفوظة.', 'AlKanaa Store. All rights reserved.', 'AlKanaa 商店 版权所有。') }}</p>
        </div>
    </div>

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
        class="{{ $fIsAr ? 'whatsapp-float' : 'whatsapp-float-en' }}" aria-label="{{ $ft('تواصل عبر واتساب', 'Chat on WhatsApp', '通过 WhatsApp 联系') }}">
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
    </a>
</footer>

<script>
    // Footer link groups: plain open columns on tablet/desktop; on phones they
    // collapse (except the first) so the footer stays short and scannable.
    (function () {
        var groups = document.querySelectorAll('details.kn-foot-group');
        var mq = window.matchMedia('(min-width: 768px)');

        function sync() {
            groups.forEach(function (g) {
                if (mq.matches || g.hasAttribute('data-open-mobile')) {
                    g.setAttribute('open', '');
                } else {
                    g.removeAttribute('open');
                }
            });
        }

        sync();
        if (mq.addEventListener) {
            mq.addEventListener('change', sync);
        }
        groups.forEach(function (g) {
            g.addEventListener('toggle', function () {
                if (mq.matches && !g.open) {
                    g.open = true;
                }
            });
        });
    })();
</script>
