@extends('frontend.layouts.app')

@section('content')
    @php
        $knIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $kt = fn ($ar, $en) => $knIsAr ? $ar : $en;
    @endphp
    <section class="kn-co-page has-sumbar">
        <div class="kn-wrap">
            <div class="kn-co-head">
                <h1 class="kn-co-title">{{ $kt('إتمام الطلب', 'Checkout') }}</h1>
                <a href="{{ route('cart') }}" class="kn-link">{{ $kt('العودة إلى السلة', 'Back to cart') }}</a>
            </div>

            @include('frontend.partials.cart.checkout_steps', [
                'current' => 2,
                'currentEnd' => 4,
                'anchors' => [2 => '#kn-step-shipping', 3 => '#kn-step-delivery', 4 => '#kn-step-payment'],
            ])

            <div class="kn-co-layout">
                <div class="kn-co-main">
                    <form class="form-default" data-toggle="validator" action="{{ route('payment.checkout') }}" role="form"
                        method="POST" id="checkout-form" enctype="multipart/form-data">
                        @csrf

                        <div class="kn-co-sections" id="accordioncCheckoutInfo">

                            <!-- Shipping Info -->
                            <section class="kn-co-section" id="kn-step-shipping" aria-labelledby="kn-step-shipping-title">
                                <div class="kn-co-section-head" id="headingShippingInfo">
                                    <span class="kn-co-section-num" aria-hidden="true">2</span>
                                    <h2 class="kn-co-section-title" id="kn-step-shipping-title">{{ translate('Shipping Info') }}</h2>
                                    <svg class="kn-co-section-check" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                        <path
                                            d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z"
                                            transform="translate(-48 -48)" fill="#9d9da6" />
                                    </svg>
                                </div>
                                <div id="collapseShippingInfo" aria-labelledby="headingShippingInfo">
                                    <div class="kn-co-section-body" id="shipping_info">
                                        @include('frontend.partials.cart.shipping_info', [
                                            'address_id' => $address_id,
                                        ])
                                    </div>
                                </div>
                            </section>

                            @if ($service_request)
                                <!-- Service Info -->
                                <section class="kn-co-section" aria-labelledby="kn-step-service-title">
                                    <div class="kn-co-section-head" id="headingServiceInfo">
                                        <span class="kn-co-section-num is-icon" aria-hidden="true"><i class="las la-tools"></i></span>
                                        <h2 class="kn-co-section-title" id="kn-step-service-title">{{ translate('Service Info') }}</h2>
                                        <svg class="kn-co-section-check" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path
                                                d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z"
                                                transform="translate(-48 -48)" fill="#9d9da6" />
                                        </svg>
                                    </div>

                                    <div id="collapseServiceInfo" aria-labelledby="headingServiceInfo">
                                        <div class="kn-co-section-body" id="service_info">
                                            <div class="kn-co-field">
                                                <label for="file">
                                                    {{ app()->getLocale() == 'cn' ? '上传文件（图片或 PDF）' : $kt('ارفع ملف (صورة أو PDF)', 'Upload file (image or PDF)') }}
                                                    <span class="kn-co-req" aria-hidden="true">*</span>
                                                </label>
                                                <input type="hidden" name="service_request" value="1">
                                                <input type="file" id="file" name="service_file" class="form-control kn-co-file"
                                                    accept=".pdf,image/*" required>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            @endif

                            <!-- Delivery Info -->
                            <section class="kn-co-section" id="kn-step-delivery" aria-labelledby="kn-step-delivery-title">
                                <div class="kn-co-section-head" id="headingDeliveryInfo">
                                    <span class="kn-co-section-num" aria-hidden="true">3</span>
                                    <h2 class="kn-co-section-title" id="kn-step-delivery-title">{{ translate('Delivery Info') }}</h2>
                                    <svg class="kn-co-section-check" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                        <path
                                            d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z"
                                            transform="translate(-48 -48)" fill="#9d9da6" />
                                    </svg>
                                </div>
                                <div id="collapseDeliveryInfo" aria-labelledby="headingDeliveryInfo">
                                    <div class="kn-co-section-body" id="delivery_info">
                                        @include('frontend.partials.cart.delivery_info', [
                                            'carts' => $carts,
                                            'carrier_list' => $carrier_list,
                                            'shipping_info' => $shipping_info,
                                        ])
                                    </div>
                                </div>
                            </section>

                            <!-- Payment Info -->
                            <section class="kn-co-section" id="kn-step-payment" aria-labelledby="kn-step-payment-title">
                                <div class="kn-co-section-head" id="headingPaymentInfo">
                                    <span class="kn-co-section-num" aria-hidden="true">4</span>
                                    <h2 class="kn-co-section-title" id="kn-step-payment-title">{{ translate('Payment') }}</h2>
                                    <svg class="kn-co-section-check" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                        <path
                                            d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z"
                                            transform="translate(-48 -48)" fill="#9d9da6" />
                                    </svg>
                                </div>
                                <div id="collapsePaymentInfo" aria-labelledby="headingPaymentInfo">
                                    <div class="kn-co-section-body" id="payment_info">
                                        @include('frontend.partials.cart.payment_info', [
                                            'carts' => $carts,
                                            'total' => $total,
                                        ])

                                        <div class="kn-co-place" id="kn-place-order">
                                            <!-- Agree Box -->
                                            <div class="kn-co-agree">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" required id="agree_checkbox"
                                                        onchange="stepCompletionPaymentInfo()">
                                                    <span class="aiz-square-check"></span>
                                                    <span>{{ translate('I agree to the') }}</span>
                                                </label>
                                                <a href="{{ route('terms') }}">{{ translate('terms and conditions') }}</a>,
                                                <a href="{{ route('returnpolicy') }}">{{ translate('return policy') }}</a> &amp;
                                                <a href="{{ route('privacypolicy') }}">{{ translate('privacy policy') }}</a>
                                            </div>

                                            <div class="kn-co-actions">
                                                <!-- Back to cart -->
                                                <a href="{{ route('cart') }}" class="kn-btn kn-co-back">
                                                    <i class="las la-arrow-left kn-co-flip" aria-hidden="true"></i>
                                                    {{ $kt('العودة إلى السلة', 'Back to cart') }}
                                                </a>
                                                <!-- Complete Order -->
                                                <button type="button" onclick="submitOrder(this)" id="submitOrderBtn"
                                                    class="kn-btn kn-btn-primary kn-co-btn-lg submitOrderBtn">
                                                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                                                    {{ translate('Complete Order') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                    </form>
                </div>

                <!-- Cart Summary -->
                <aside class="kn-co-aside" id="cart_summary">
                    @include('frontend.partials.cart.cart_summary', ['proceed' => 0, 'carts' => $carts])
                </aside>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Address Modal -->
    @if (Auth::check())
        @include('frontend.partials.address.address_modal')
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        var carrierCount = 0;
        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                ;
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    var allIsOk = false;
                    var isOkShipping = stepCompletionShippingInfo();
                    var isOkDelivery = stepCompletionDeliveryInfo();
                    var isOkPayment = stepCompletionWalletPaymentInfo();
                    if (isOkShipping && isOkDelivery && isOkPayment) {
                        allIsOk = true;
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Please fill in all mandatory fields!') }}');
                        $('#checkout-form [required]').each(function(i, el) {
                            if ($(el).val() == '' || $(el).val() == undefined) {
                                var is_trx_id = $('.d-none #trx_id').length;
                                if (($(el).attr('name') != 'trx_id') || is_trx_id == 0) {
                                    $(el).focus();
                                    $(el).scrollIntoView({
                                        behavior: "smooth",
                                        block: "center"
                                    });
                                    return false;
                                }
                            }
                        });
                    }

                    if (allIsOk) {
                        $('#checkout-form').submit();
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
            }
        }

        function submitOrder(el, type = null) {
            $(el).prop('disabled', true);
            if ($('#agree_checkbox').is(":checked")) {
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    var offline_payment_active = '{{ addon_is_activated('offline_payment') }}';
                    if (offline_payment_active == '1' && $('.offline_payment_option').is(":checked") && $('#trx_id')
                        .val() == '') {
                        AIZ.plugins.notify('danger', '{{ translate('You need to put Transaction id') }}');
                        $(el).prop('disabled', false);
                    } else {
                        var allIsOk = false;
                        var isOkShipping = stepCompletionShippingInfo();
                        var isOkDelivery = stepCompletionDeliveryInfo();
                        var isOkPayment = stepCompletionPaymentInfo();
                        if (isOkShipping && isOkDelivery && isOkPayment) {
                            allIsOk = true;
                        } else {
                            AIZ.plugins.notify('danger', '{{ translate('Please fill in all mandatory fields!') }}');
                            $('#checkout-form [required]').each(function(i, el) {
                                if ($(el).val() == '' || $(el).val() == undefined) {
                                    var is_trx_id = $('.d-none #trx_id').length;
                                    if (($(el).attr('name') != 'trx_id') || is_trx_id == 0) {
                                        $(el).focus();
                                        $(el).scrollIntoView({
                                            behavior: "smooth",
                                            block: "center"
                                        });
                                        return false;
                                    }
                                }
                            });
                        }

                        if (allIsOk) {
                            if (type == "quote")
                                $('#order_type').value("quote");
                            $('#checkout-form').submit();
                        }
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }
        // coupon apply
        $(document).on("click", "#coupon-apply", function() {
            @if (Auth::check())
                @if (Auth::user()->user_type != 'customer')
                    AIZ.plugins.notify('warning',
                        "{{ translate('Please Login as a customer to apply coupon code.') }}");
                    return false;
                @endif

                var data = new FormData($('#apply-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.apply_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        AIZ.plugins.notify(data.response_message.response, data.response_message
                            .message);
                        $("#cart_summary").html(data.html);
                    }
                });
            @else
                $('#login_modal').modal('show');
            @endif
        });

        // coupon remove
        $(document).on("click", "#coupon-remove", function() {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                var data = new FormData($('#remove-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.remove_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        $("#cart_summary").html(data);
                    }
                });
            @endif
        });

        function updateDeliveryAddress(id, city_id = 0) {
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryAddress') }}', {
                _token: AIZ.data.csrf,
                address_id: id,
                city_id: city_id
            }, function(data) {
                $('#delivery_info').html(data.delivery_info);
                $('#cart_summary').html(data.cart_summary);
                $('.aiz-refresh').removeClass('active');
                carrierCount = data.carrier_count;
                checkCarrerShippingInfo();
            });

            AIZ.plugins.bootstrapSelect("refresh");
        }

        function stepCompletionShippingInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            @if (Auth::check())
                var length = $('input[name="address_id"]:checked').length;
                if (length > 0) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @else
                var count = 0;
                var length = $('#shipping_info [required]').length;
                $('#shipping_info [required]').each(function(i, el) {
                    if ($(el).val() != '' && $(el).val() != undefined && $(el).val() != null) {
                        count += 1;
                    }
                });
                if (count == length) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @endif

            $('#headingShippingInfo svg *').css('fill', headColor);
            $(".submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('#shipping_info [required]').each(function(i, el) {
            $(el).change(function() {
                if ($(el).attr('name') == 'address_id') {
                    updateDeliveryAddress($(el).val());
                }
                @if (get_setting('shipping_type') == 'area_wise_shipping')
                    if ($(el).attr('name') == 'city_id') {
                        let country_id = $('select[name="country_id"]').val();
                        let city_id = $(this).val();
                        updateDeliveryAddress(country_id, city_id);
                    }
                @endif
                stepCompletionShippingInfo();
            });
        });

        function stepCompletionDeliveryInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            var content = $('#delivery_info [required]');
            if (content.length > 0) {
                var content_checked = $('#delivery_info [required]:checked');
                if (content_checked.length > 0) {
                    content_checked.each(function(i, el) {
                        allOk = false;
                        if ($(el).val() == 'carrier') {
                            var owner = $(el).attr('data-owner');
                            if ($('input[name=carrier_id_' + owner + ']:checked').length > 0) {
                                allOk = true;
                            }
                        } else if ($(el).val() == 'pickup_point') {
                            var owner = $(el).attr('data-owner');
                            if ($('select[name="pickup_point_id_' + owner + '"]').val() != '') {
                                allOk = true;
                            }
                        } else {
                            allOk = true;
                        }

                        if (allOk == false) {
                            return false;
                        }
                    });

                    if (allOk) {
                        headColor = '#15a405';
                        btnDisable = false;
                    }
                }
            } else {
                allOk = true
                headColor = '#15a405';
                btnDisable = false;
            }

            $('#headingDeliveryInfo svg *').css('fill', headColor);
            $(".submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        function updateDeliveryInfo(shipping_type, type_id, user_id, country_id = 0, city_id = 0) {
            @if (get_setting('shipping_type') == 'area_wise_shipping' || get_setting('shipping_type') == 'carrier_wise_shipping')
                country_id = $('select[name="country_id"]').val() != null ? $('select[name="country_id"]').val() : 0;
                city_id = $('select[name="city_id"]').val() != null ? $('select[name="city_id"]').val() : 0;
            @endif
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryInfo') }}', {
                _token: AIZ.data.csrf,
                shipping_type: shipping_type,
                type_id: type_id,
                user_id: user_id,
                country_id: country_id,
                city_id: city_id
            }, function(data) {
                $('#cart_summary').html(data);
                checkCarrerShippingInfo();
                stepCompletionDeliveryInfo();
                $('.aiz-refresh').removeClass('active');
            });
            AIZ.plugins.bootstrapSelect("refresh");
        }

        function show_pickup_point(el, user_id) {
            var type = $(el).val();
            var target = $(el).data('target');
            var type_id = null;

            if (type == 'home_delivery' || type == 'carrier') {
                if (!$(target).hasClass('d-none')) {
                    $(target).addClass('d-none');
                }
                $('.carrier_id_' + user_id).removeClass('d-none');
            } else {
                $(target).removeClass('d-none');
                $('.carrier_id_' + user_id).addClass('d-none');
            }

            if (type == 'carrier') {
                type_id = $('input[name=carrier_id_' + user_id + ']:checked').val();
            } else if (type == 'pickup_point') {
                type_id = $('select[name=pickup_point_id_' + user_id + ']').val();
            }
            updateDeliveryInfo(type, type_id, user_id);
        }

        function stepCompletionPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var payment = false;
            var agree = false;
            var allOk = false;
            var length = $('input[name="payment_option"]:checked').length;
            if (length > 0) {
                if ($('input[name="payment_option"]:checked').hasClass('offline_payment_option')) {
                    if ($('#trx_id').val() != '' && $('#trx_id').val() != undefined && $('#trx_id').val() != null) {
                        payment = true;
                    }
                } else {
                    payment = true;
                }

                if ($('#agree_checkbox').is(":checked")) {
                    agree = true;
                }

                if (payment && agree) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $(".submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        function stepCompletionWalletPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            if ($('#agree_checkbox').is(":checked")) {
                headColor = '#15a405';
                btnDisable = false;
                allOk = true;
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $(".submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('input[name="payment_option"]').change(function() {
            stepCompletionPaymentInfo();
        });

        function checkCarrerShippingInfo() {
            const shippingType = @json(get_setting('shipping_type'));
            const isDisabled = carrierCount === 0;
            let carrierSelected = false;
            let pickupSelected = false;
            $('.shipping-type-radio').each(function() {
                if ($(this).is(':checked') && $(this).val() === 'carrier') {
                    carrierSelected = true;
                }
            });
            $('.shipping-type-radio').each(function() {
                if ($(this).is(':checked') && $(this).val() === 'pickup_point') {
                    pickupSelected = true;
                }
            });
            if (shippingType == 'carrier_wise_shipping' && carrierSelected) {
                if (carrierCount === 0) {
                    if ((carrierSelected && pickupSelected) || (carrierSelected && !pickupSelected)) {
                        $('.submitOrderBtn').prop('disabled', true);
                        $('#agree_checkbox').prop('checked', false).prop('disabled', true);
                        $('.online_payment, .offline_payment_option').prop('checked', false).prop('disabled', true);
                    }
                } else {
                    $('#agree_checkbox').prop('disabled', false);
                    $('.online_payment, .offline_payment_option').prop('disabled', false);
                }
            } else {
                $('#agree_checkbox').prop('disabled', false);
                $('.online_payment, .offline_payment_option').prop('disabled', false);
            }
        }

        $(document).ready(function() {
            carrierCount = parseInt(document.getElementById('carrierCount')?.value || 0);
            checkCarrerShippingInfo();
            stepCompletionShippingInfo();
            stepCompletionDeliveryInfo();
            stepCompletionPaymentInfo();
        });
    </script>

    @include('frontend.partials.address.address_js')


    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection
