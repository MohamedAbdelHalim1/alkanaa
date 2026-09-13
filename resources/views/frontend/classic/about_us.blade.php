@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);
    $img = fn ($file) => static_asset('assets/front_img/' . $file);

    $about = [
        'title' => $tr('من نحن', 'About Us', '关于我们'),
        'heading' => $tr('عن القناعة', 'About Al-Qanaa', '关于 Al-Qanaa'),
        'paragraphs' => $isAr
            ? [
                'بدأت مسيرة القناعة في عام ٢٠٠٢ كإحدى حلقات سلسلة المصانع التي بدأت في عام ١٩٦٨ وكأول مصنع سعودي في مجال صناعة المعدات وحفظ الأطعمة؛ وذلك عندما استشعر الشيخ غالب نصر أهمية تطوير صناعة المعدات الخاصة بصناعة وحفظ الأطعمة في المملكة العربية السعودية لتلبية احتياجات السوق المحلي المتزايد من هذه المنتجات.',
                'وبأفكاره الناجحة وتوجيهاته تم إنشاء وتطوير عدة مشاريع صناعية عالية الكفاءة لتحقيق هدف البناء والدعم لاقتصاد المملكة العربية السعودية فبدأنا بإنتاج الشوايات والبرادات وسرعان ما تم توسيع الإنتاج ليشمل مصانع معدات المطابخ والأفران المتطورة.',
            ]
            : ($isCn
                ? [
                    'Al-Qanaa 的旅程始于 2002 年，作为自 1968 年开始的一系列工厂链中的一环，成为沙特阿拉伯首家专门生产食品设备和保存产品的工厂。Ghaleb Nasr 先生意识到，在沙特阿拉伯发展食品设备产业的重要性，以满足不断增长的本地市场需求。',
                    '在他成功的构想和指导下，建立和开发了多个高效的工业项目，以支持沙特阿拉伯经济。我们开始生产烤炉和冷柜，并迅速扩展生产线，包括先进的厨房设备和烤箱制造。',
                ]
                : [
                    'Al-Qanaa journey started in 2002 as one of the links in a chain of factories that began in 1968, becoming the first Saudi factory specializing in manufacturing food equipment and preservation products. Sheikh Ghaleb Nasr recognized the importance of developing the food equipment industry in Saudi Arabia to meet the growing local market demand.',
                    'With his successful ideas and guidance, several high-efficiency industrial projects were established and developed to support Saudi Arabia\'s economy. We started producing grills and refrigerators, and soon expanded production to include advanced kitchen equipment and oven factories.',
                ]),
    ];

    // Years are taken from the company story itself.
    $figures = [
        ['value' => '1968', 'label' => $tr('انطلاق سلسلة مصانعنا', 'Our chain of factories began', '工厂链始于此年')],
        ['value' => '2002', 'label' => $tr('بداية مسيرة القناعة', 'Al-Qanaa journey started', 'Al-Qanaa 旅程开始')],
        ['value' => '2010', 'label' => $tr('خط إنتاج جديد ومصنع ثلاجات العرض', 'New production line and display refrigerator factory', '新增生产线及展示冷柜工厂')],
    ];

    $stories = [
        [
            'title' => $tr('مسيرتنا', 'Our Journey', '我们的历程'),
            'image' => 'masertna.jpg',
            'alt' => $tr('خطوط إنتاج المعدات في المصنع', 'Equipment production lines in the factory', '工厂设备生产线'),
            'paragraphs' => $isAr
                ? [
                    'بدأت مسيرة القناعة في سنة ٢٠٠٢ كأحدى حلقات سلسلة المصانع التي بدأت في عام ١٩٦٨ كأول مصنع سعودي في مجال صناعة وحفظ الأطعمة في المملكة العربية السعودية لتلبية احتياجات السوق المحلي المتزايد من هذه المنتجات.',
                    'وبأفكاره الناجحة وتوجيهاته تم إنشاء وتطوير عدة مشاريع صناعية عالية الكفاءة لتحقيق هدفه البناء والداعم لاقتصاد المملكة العربية السعودية، فبدأنا بإنتاج الشوايات والبرادات وسرعان ما تم توسيع الإنتاج ليشمل مصانع معدات المطابخ والأفران.',
                ]
                : ($isCn
                    ? [
                        'Al-Qanaa 的旅程始于 2002 年，作为自 1968 年开始的一系列工厂链中的一环，成为沙特阿拉伯首家专门生产和保存食品设备的工厂，以满足不断增长的本地市场需求。',
                        '在成功的愿景和指导下，建立了多个高效的工业项目，旨在建设并支持沙特阿拉伯经济。我们开始生产烤炉和冷柜，并迅速扩大生产线，包括厨房设备和烤箱制造。',
                    ]
                    : [
                        'Al-Qanaa journey began in 2002 as one of the links in the chain of factories that started in 1968, as the first Saudi factory specialized in manufacturing and preserving food products to meet the increasing demand of the local market.',
                        'With successful visions and guidance, several high-efficiency industrial projects were established to build and support Saudi Arabia\'s economy. We began producing grills and refrigerators, and quickly expanded production to include kitchen equipment and ovens.',
                    ]),
        ],
        [
            'title' => $tr('رحلتنا', 'Our Path', '我们的旅程'),
            'image' => 'rhltna.jpg',
            'alt' => $tr('تطور تصنيع الأفران من الورشة إلى المصنع الحديث', 'Oven manufacturing, from workshop to modern factory', '烤箱制造：从作坊到现代工厂'),
            'paragraphs' => $isAr
                ? [
                    'دخلت الشّركة في بداية الألفية الثانية مرحلة جديدة لإعادة تنظيم وتخطيط نشاطها، حيث كان هدفها توفير أفضل المنتجات بأسعار منافسة ليتمكن صناع الأغذية من استخدام منتجات عالية الجودة وبأسعار مدروسة.',
                    'وبذلك حققت القناعة هدفها ببناء وتشغيل مصنعها الجديد والاستحواذ على النسبة الأكبر من السوق المحلي وبعض الأسواق الخارجية.',
                    'وتزامنًا مع ذلك قمنا بإنشاء خط إنتاج كبير في مدينة جدة ويُعد الأول من نوعه في الشرق الأوسط وقارة أفريقيا.',
                    'وفي أواخر عام ٢٠١٠ تم الاستحواذ على مصنع ثلاجات العرض وتطويره.',
                ]
                : ($isCn
                    ? [
                        '在 21 世纪初，公司进入了重新组织和规划其业务的新阶段。其目标是以具有竞争力的价格提供最佳产品，使食品行业的从业者能够以合理的价格使用高质量的产品。',
                        '因此，Al-Qanaa 实现了建设和运营新工厂的目标，并占据了本地市场以及部分海外市场的最大份额。',
                        '同时，我们在吉达建立了一条大型生产线，这是中东和非洲地区首创。',
                        '到 2010 年底，Al-Qanaa 收购并开发了展示冷柜工厂。',
                    ]
                    : [
                        'In the early 2000s, the company entered a new phase to reorganize and plan its operations. Its goal was to provide the best products at competitive prices, enabling food industry professionals to access high-quality products at reasonable costs.',
                        'Thus, Al-Qanaa achieved its goal by building and operating its new factory and capturing the largest share of the local market and some foreign markets.',
                        'At the same time, we established a large production line in Jeddah, which was the first of its kind in the Middle East and Africa.',
                        'By the end of 2010, Al-Qanaa acquired and developed a display refrigerator factory.',
                    ]),
        ],
        [
            'title' => $tr('استراتيجيتنا', 'Our Strategy', '我们的战略'),
            'image' => 'estrageytna.jpg',
            'alt' => $tr('خطوط تصنيع حديثة ومؤتمتة', 'Modern automated manufacturing lines', '现代化自动生产线'),
            'paragraphs' => $isAr
                ? ['تركز استراتيجيتنا الحالية على الابتكار والتطوير والتوسّع الإقليمي، وبالنسبة لمعدات المطابخ ونظرًا لما شهده سوق المملكة العربية السعودية من نمو كبير فقد تم إضافة خط إنتاج في عام ٢٠١٠ ورفع الطاقة الإنتاجية والقوى العاملة وتطوير الجودة لنواكب مجريات السوق المحلي والخارجي وإطلاق علامتنا التجارية.']
                : ($isCn
                    ? ['我们当前的战略专注于创新、开发和区域扩张。针对厨房设备，由于沙特市场的快速增长，我们在 2010 年增加了一条生产线，提高了生产能力和劳动力，并改进了质量，以跟上本地和国际市场的发展，并推出了我们的品牌。']
                    : ['Our current strategy focuses on innovation, development, and regional expansion. Regarding kitchen equipment, and due to the significant growth in the Saudi market, we added a production line in 2010, increased production capacity and workforce, and enhanced quality to keep pace with local and international market developments and launched our brand.']),
        ],
    ];

    $commitment = [
        'title' => $tr('التزامنا بتقديم الأفضل', 'Our Commitment to Excellence', '我们对卓越的承诺'),
        'text' => $isAr
            ? 'دخلت القناعة في بداية مرحلة جديدة لإعادة تنظيم وتخطيط أنشطتها وهدفها هو تقديم أفضل المنتجات بأسعار منافسة ليتمكن صناع الأغذية من استخدام منتجات عالية الجودة وبأسعار معقولة. وتحقيق النجاح في تحقيق هدفها وعملها وتشغيلها في صناعة معدات صناعة حفظ الأطعمة ليحل محل المنتجات المستوردة رغم محاولات لمنافستها إلا أننا سننجح في تحقيق النجاح في النسبة الأكبر من السوق المحلي وبعض الأسواق الخارجية. وتزامن ذلك مع إنشاء خط إنتاج كبير في مدينة نيويورك. أول من سيُقام في الشرق الأوسط وقارة أفريقيا. وفي أواخر العام، تم الاستحواذ على مصنع ثلاجات العرض وتطويره، وشهد هذا العام إنشاء مصنع شركة قناعة لصناعة الأرفف.'
            : ($isCn
                ? 'Al-Qanaa 进入了重新组织和规划活动的新阶段，旨在以具有竞争力的价格提供最好的产品，使食品行业的从业者能够以合理的价格使用高质量的产品。Al-Qanaa 成功实现了目标，在制造食品保存设备方面取得了成功，取代了进口产品。尽管存在竞争，但我们成功占据了本地市场以及部分海外市场的最大份额。这与在纽约市建立的大型生产线同时发生，这是中东和非洲地区首创。到年底，Al-Qanaa 收购并开发了展示冷柜工厂，今年见证了 Al-Qanaa 架子制造厂的成立。'
                : 'Al-Qanaa entered a new phase to reorganize and plan its activities, aiming to provide the best products at competitive prices so food industry professionals can access high-quality products at affordable prices. Al-Qanaa succeeded in achieving its goals, working in manufacturing food preservation equipment to replace imported products. Despite attempts to compete with it, we succeeded in capturing the largest share of the local market and some foreign markets. This coincided with establishing a major production line in New York City, the first of its kind in the Middle East and Africa. By the end of the year, Al-Qanaa acquired and developed a display refrigerator factory, and this year witnessed the establishment of Al-Qanaa\'s shelf manufacturing plant.'),
    ];

    $gallery = [
        ['file' => 'about2.jpeg', 'alt' => $tr('ثلاجات عرض جاهزة للتسليم', 'Branded display refrigerators ready for delivery', '待交付的品牌展示冷柜')],
        ['file' => 'about4.jpeg', 'alt' => $tr('ثلاجات عرض صغيرة على خط الإنتاج', 'Countertop display coolers on the production line', '生产线上的台式展示冷柜')],
        ['file' => 'about7.jpeg', 'alt' => $tr('خط تجميع هياكل الثلاجات', 'Refrigerator cabinet assembly line', '冷柜箱体装配线')],
        ['file' => 'about5.jpeg', 'alt' => $tr('هياكل ثلاجات قيد التجميع', 'Refrigerator bodies in assembly', '组装中的冷柜箱体')],
        ['file' => 'aboutus1.jpeg', 'alt' => $tr('خط الطلاء بالبودرة', 'Powder coating line', '粉末喷涂线')],
        ['file' => 'about6.jpeg', 'alt' => $tr('إطارات معدنية بعد الطلاء', 'Metal frames after coating', '喷涂后的金属框架')],
        ['file' => 'about3.jpeg', 'alt' => $tr('صالة الطلاء في المصنع', 'The factory coating hall', '工厂喷涂车间')],
        ['file' => 'about9.jpeg', 'alt' => $tr('مكاتب المصنع', 'Factory offices', '工厂办公区')],
    ];
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">

            {{-- Hero --}}
            <section class="kn-about-hero" aria-labelledby="kn-about-title">
                <div>
                    <h1 id="kn-about-title" class="kn-page-title">{{ $about['title'] }}</h1>
                    <h2 class="kn-h3 kn-muted">{{ $about['heading'] }}</h2>
                    @foreach ($about['paragraphs'] as $paragraph)
                        <p class="kn-text">{{ $paragraph }}</p>
                    @endforeach
                </div>
                <figure class="kn-media">
                    <img src="{{ $img('about8.jpeg') }}"
                        alt="{{ $tr('خط إنتاج ثلاجات العرض في مصنع القناعة', 'Display refrigerator production line at the Al-Qanaa factory', 'Al-Qanaa 工厂的展示冷柜生产线') }}">
                </figure>
            </section>

            {{-- Key years from the story --}}
            <ul class="kn-figures">
                @foreach ($figures as $figure)
                    <li class="kn-figure">
                        <span class="kn-figure-value">{{ $figure['value'] }}</span>
                        <span class="kn-figure-label">{{ $figure['label'] }}</span>
                    </li>
                @endforeach
            </ul>

            {{-- Journey / path / strategy: alternating on desktop, stacked on mobile --}}
            @foreach ($stories as $i => $story)
                <section class="kn-story {{ $i % 2 === 1 ? 'is-flip' : '' }}" aria-labelledby="kn-story-{{ $i }}">
                    <div class="kn-story-body">
                        <h2 id="kn-story-{{ $i }}" class="kn-h2">{{ $story['title'] }}</h2>
                        @foreach ($story['paragraphs'] as $paragraph)
                            <p class="kn-text">{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    <figure class="kn-media kn-story-media">
                        <img src="{{ $img($story['image']) }}" alt="{{ $story['alt'] }}" loading="lazy">
                    </figure>
                </section>
            @endforeach

            {{-- Commitment --}}
            <section class="kn-panel kn-panel-steel" aria-labelledby="kn-commitment-title">
                <h2 id="kn-commitment-title" class="kn-h2">{{ $commitment['title'] }}</h2>
                <p class="kn-text">{{ $commitment['text'] }}</p>
                <div class="kn-actions">
                    <a href="{{ route('search') }}" class="kn-btn kn-btn-primary">{{ $tr('تصفح المنتجات', 'Browse products', '浏览产品') }}</a>
                    <a href="{{ route('contact.us') }}" class="kn-btn kn-btn-ghost">{{ $tr('تواصل معنا', 'Contact us', '联系我们') }}</a>
                </div>
            </section>

            {{-- Factory gallery --}}
            <section class="kn-section" aria-labelledby="kn-gallery-title">
                <div class="kn-section-head">
                    <h2 id="kn-gallery-title" class="kn-section-title">{{ $tr('داخل مصانعنا', 'Inside our factories', '走进我们的工厂') }}</h2>
                </div>
                <ul class="kn-gallery">
                    @foreach ($gallery as $photo)
                        <li>
                            <figure class="kn-media">
                                <img src="{{ $img($photo['file']) }}" alt="{{ $photo['alt'] }}" loading="lazy">
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </section>
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
