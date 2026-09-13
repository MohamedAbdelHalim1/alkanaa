@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);

    $contactPhone = get_setting('contact_phone');
    $whatsapp = get_setting('whatsapp_number');
    $contactEmail = get_setting('contact_email');
    $contactAddress = get_setting('contact_address');
    $telHref = fn ($number) => 'tel:' . preg_replace('/[^\d+]/', '', $number);

    $branches = [
        [
            'icon' => 'fas fa-store-alt',
            'title' => $tr('معرض جدة', 'Jeddah Showroom', '吉达展厅'),
            'lines' => [
                ['icon' => 'fas fa-map-marker-alt', 'text' => $tr('طريق المدينة قبل مسجد الملك سعود مقابل مركز سلمى', 'Madina Road before King Saud Mosque, opposite Salma Center', '麦地那路，国王苏德清真寺前，对面萨尔玛中心')],
                ['icon' => 'fas fa-envelope', 'text' => $tr('ص-ب ٣٣٣٩٠ جدة ٢١٤٤٨', 'P.O. Box 33390, Jeddah 21448', '信箱 33390，吉达 21448')],
                ['icon' => 'fas fa-phone-alt', 'text' => '+966126324117', 'tel' => true],
            ],
        ],
        [
            'icon' => 'fas fa-store-alt',
            'title' => $tr('معرض الدمام', 'Dammam Showroom', '达曼展厅'),
            'lines' => [
                ['icon' => 'fas fa-map-marker-alt', 'text' => $tr('شارع الخزان - عمارة الحيمود', 'Al Khazan Street - Al Haimoud Building', 'Al Khazan 街 - Al Haimoud 大厦')],
                ['icon' => 'fas fa-phone-alt', 'text' => '0138342117', 'tel' => true],
            ],
        ],
        [
            'icon' => 'fas fa-industry',
            'title' => $tr('المصنع', 'Factory', '工厂'),
            'lines' => [
                ['icon' => 'fas fa-map-marker-alt', 'text' => $tr('جدة - المدينة الصناعية الأولى - المرحلة الثالثة', 'Jeddah - First Industrial City - Phase 3', '吉达 - 第一工业城 - 第三阶段')],
                ['icon' => 'fas fa-phone-alt', 'text' => '0509290374', 'tel' => true],
            ],
        ],
        [
            'icon' => 'fas fa-store-alt',
            'title' => $tr('معرض الرياض', 'Riyadh Showroom', '利雅得展厅'),
            'lines' => [
                ['icon' => 'fas fa-map-marker-alt', 'text' => $tr('٢٧٢٧ الوشم - حي المربع', '2727 Al Wushm - Al Murabba District', '2727 Al Wushm - Al Murabba 区')],
                ['icon' => 'fas fa-map-marker-alt', 'text' => $tr('المملكة العربية السعودية، الرياض', 'Saudi Arabia, Riyadh', '沙特阿拉伯，利雅得')],
                ['icon' => 'fas fa-phone-alt', 'text' => '+966 11 404 2417', 'tel' => true],
            ],
        ],
    ];

    $mapSrc = $isCn
        ? 'https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d14858208.470501209!2d46.7075344!3d24.6067117!3m2!1i1024!2i768!4f13.1!2m1!1z2LTYsdmD2Ycg2KfZhNmC2YbYp9i52Ycg2KfZhNiz2LnZiNiv2YrZh-KArQ!5e0!3m2!1sen!2eg!4v1752509905863!5m2!1sen!2eg'
        : 'https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d14858208.470501209!2d46.7075344!3d24.6067117!3m2!1i1024!2i768!4f13.1!2m1!1z2LTYsdmD2Ycg2KfZhNmC2YbYp9i52Ycg2KfZhNiz2LnZiNiv2YrZh-KArQ!5e0!3m2!1sen!2seg!4v1752509905863!5m2!1sen!2seg';

    $formId = $isAr ? 'contactFormSa' : ($isCn ? 'contactFormCn' : 'contactFormEn');

    // JS messages as plain variables: the Blade json directive splits its argument on commas.
    $msgError = $tr('حدث خطأ، حاول مرة أخرى.', 'Something went wrong, please try again.', '出错了，请重试。');
    $msgSuccess = $tr('تم إرسال طلبك بنجاح!', 'Your request has been sent successfully!', '您的请求已成功发送！');
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">
            <header class="kn-page-head">
                <h1 class="kn-page-title">{{ $tr('ابق على تواصل معنا', 'Stay Connected With Us', '与我们保持联系') }}</h1>
                <p class="kn-lead">
                    {{ $tr(
                        'القناعة مصنع سعودي رائد في مجال صناعة معدات المطابخ وحفظ الأطعمة، بخبرة تمتد لأكثر من 20 عامًا في تلبية احتياجات قطاع الضيافة والمطاعم.',
                        "Al Qana'a is a leading Saudi factory in the field of kitchen equipment and food preservation, with over 20 years of experience in meeting the needs of the hospitality and restaurant sector.",
                        "Al Qana'a 是沙特阿拉伯领先的厨房设备和食品保鲜工厂，在满足酒店和餐饮行业需求方面拥有 20 多年的经验。"
                    ) }}
                </p>
            </header>

            <div class="kn-contact">
                {{-- Direct channels --}}
                <section aria-labelledby="kn-channels-title">
                    <h2 id="kn-channels-title" class="kn-h3">{{ $tr('تواصل مباشر', 'Reach us directly', '直接联系我们') }}</h2>
                    <ul class="kn-channels">
                        @if ($contactPhone)
                            <li>
                                <a class="kn-channel" href="{{ $telHref($contactPhone) }}">
                                    <span class="kn-channel-icon" aria-hidden="true"><i class="fas fa-phone-alt"></i></span>
                                    <span class="kn-channel-text">
                                        <span class="kn-channel-label">{{ $tr('الهاتف', 'Phone', '电话') }}</span>
                                        <span class="kn-channel-value"><bdi dir="ltr">{{ $contactPhone }}</bdi></span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if ($whatsapp)
                            <li>
                                <a class="kn-channel" href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank" rel="noopener">
                                    <span class="kn-channel-icon is-whatsapp" aria-hidden="true"><i class="fab fa-whatsapp"></i></span>
                                    <span class="kn-channel-text">
                                        <span class="kn-channel-label">{{ $tr('واتساب', 'WhatsApp', 'WhatsApp') }}</span>
                                        <span class="kn-channel-value"><bdi dir="ltr">{{ $whatsapp }}</bdi></span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if ($contactEmail)
                            <li>
                                <a class="kn-channel" href="mailto:{{ $contactEmail }}">
                                    <span class="kn-channel-icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                                    <span class="kn-channel-text">
                                        <span class="kn-channel-label">{{ $tr('البريد الإلكتروني', 'Email', '电子邮箱') }}</span>
                                        <span class="kn-channel-value"><bdi dir="ltr">{{ $contactEmail }}</bdi></span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if ($contactAddress)
                            <li>
                                <div class="kn-channel">
                                    <span class="kn-channel-icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                                    <span class="kn-channel-text">
                                        <span class="kn-channel-label">{{ $tr('العنوان', 'Address', '地址') }}</span>
                                        <span class="kn-channel-value">{{ $contactAddress }}</span>
                                    </span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </section>

                {{-- Message form --}}
                <section class="kn-form-card" id="suggestions" aria-labelledby="kn-message-title">
                    <h2 id="kn-message-title" class="kn-h2">{{ $tr('اترك رسالتك', 'Leave Your Message', '留下您的留言') }}</h2>
                    <p class="kn-form-intro">
                        {{ $tr(
                            'إذا كان لديكم أي اقتراحات أو شكاوى، يرجى التواصل معنا عبر الارقام التالية أو اترك رسالتك',
                            'If you have any suggestions or complaints, please contact us via the numbers below or leave your message.',
                            '如有任何建议或投诉，请通过以下号码联系我们或留下您的留言。'
                        ) }}
                    </p>

                    <form id="{{ $formId }}" class="kn-contact-form" novalidate>
                        <div class="kn-field-grid">
                            <div class="kn-field">
                                <label class="kn-label" for="kn-contact-name">{{ $tr('الاسم', 'Name', '姓名') }}<span class="kn-req" aria-hidden="true">*</span></label>
                                <input type="text" id="kn-contact-name" class="form-control" name="name" autocomplete="name" required>
                            </div>
                            <div class="kn-field">
                                <label class="kn-label" for="kn-contact-phone">{{ $tr('الجوال', 'Mobile', '手机') }}<span class="kn-req" aria-hidden="true">*</span></label>
                                <input type="tel" id="kn-contact-phone" class="form-control" name="phone" autocomplete="tel" inputmode="tel" dir="ltr" required>
                            </div>
                        </div>
                        <div class="kn-field">
                            <label class="kn-label" for="kn-contact-email">{{ $tr('الايميل', 'Email', '邮箱') }}<span class="kn-req" aria-hidden="true">*</span></label>
                            <input type="email" id="kn-contact-email" class="form-control" name="email" autocomplete="email" dir="ltr" required>
                        </div>
                        <div class="kn-field">
                            <label class="kn-label" for="kn-contact-content">{{ $tr('رسالتك', 'Your message', '您的留言') }}<span class="kn-req" aria-hidden="true">*</span></label>
                            <textarea id="kn-contact-content" class="form-control" rows="5" name="content"
                                placeholder="{{ $tr('اترك رسالتك هنا ....', 'Your message...', '请在此输入您的留言...') }}" required></textarea>
                        </div>

                        <div class="kn-form-actions">
                            <button type="submit" class="kn-btn kn-btn-primary kn-btn-lg">{{ $tr('ارسال', 'Send', '发送') }}</button>
                            <p class="kn-form-status" role="status" aria-live="polite"></p>
                        </div>
                    </form>
                </section>
            </div>

            {{-- Branches --}}
            <section aria-labelledby="kn-branches-title">
                <div class="kn-section-head">
                    <h2 id="kn-branches-title" class="kn-section-title">{{ $tr('فروعنا', 'Our Branches', '我们的分店') }}</h2>
                </div>
                <ul class="kn-branches">
                    @foreach ($branches as $branch)
                        <li class="kn-branch">
                            <h3 class="kn-branch-title"><i class="{{ $branch['icon'] }}" aria-hidden="true"></i>{{ $branch['title'] }}</h3>
                            @foreach ($branch['lines'] as $line)
                                <p class="kn-branch-line">
                                    <i class="{{ $line['icon'] }}" aria-hidden="true"></i>
                                    @if (!empty($line['tel']))
                                        <a href="{{ $telHref($line['text']) }}"><bdi dir="ltr">{{ $line['text'] }}</bdi></a>
                                    @else
                                        <span>{{ $line['text'] }}</span>
                                    @endif
                                </p>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Map --}}
            <div class="kn-map">
                <iframe src="{{ $mapSrc }}" title="{{ $tr('خريطة مواقع القناعة', 'Al-Qanaa locations map', 'Al-Qanaa 位置地图') }}"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

    <script>
        // Contact form: AJAX submit to contact.send (the original script sat in a second, never-rendered script section).
        // Scoped to the contact form only, so the header search form keeps working.
        $('.kn-contact-form').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var $form = $(this);
            var $status = $form.find('.kn-form-status');
            var $submit = $form.find('[type="submit"]');

            if (!this.checkValidity()) {
                this.reportValidity();
                return false;
            }

            $status.removeClass('is-success is-error').text('');
            $submit.prop('disabled', true);

            $.ajax({
                url: '{{ route('contact.send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $form.find('[name="name"]').val(),
                    phone: $form.find('[name="phone"]').val(),
                    email: $form.find('[name="email"]').val(),
                    content: $form.find('[name="content"]').val(),
                },
                success: function(response) {
                    if (response && response.success === false) {
                        $status.addClass('is-error').text(response.message || @json($msgError));
                        return;
                    }
                    $status.addClass('is-success').text(@json($msgSuccess));
                    $form[0].reset();
                },
                error: function(xhr) {
                    $status.addClass('is-error').text((xhr.responseJSON && xhr.responseJSON.message) || @json($msgError));
                },
                complete: function() {
                    $submit.prop('disabled', false);
                }
            });

            return false;
        });
    </script>
@endsection
