{{-- Site-wide cart drawer (#cartOffcanvas). Styles live in resources/css/pages/cart.css, scoped to #cartOffcanvas.
     JS hooks kept: .cart-title span, .cart-items, .cart-summary, .cart-footer, .offcanvas-body,
     .qty-control[data-id] .qty-minus/.qty-plus/.qty-input, .qty-msg, #openFormBtn/#closeFormBtn/#bottomSheet. --}}
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Cookie;

    if (!session()->get('temp_user_id')) {
        $temp_id = Cookie::get('temp_user_id') ?? Str::random(15);
        session()->put('temp_user_id', $temp_id);
        Cookie::queue('temp_user_id', $temp_id, 60 * 24 * 30); // keep for a month
    }

    $user = auth()->user();
    $temp_user = session()->get('temp_user_id');

    // cart for the signed-in user or the temporary guest
    $carts = auth()->check()
        ? \App\Models\Cart::where('user_id', auth()->id())
        : \App\Models\Cart::where('temp_user_id', $temp_user);

    $carts = $carts->latest()->get();

    // totals
    $subtotal_for_min_order_amount = 0;
    $subtotal = 0;
    $tax = 0;
    $shipping = 0;
    $coupon_code = null;
    $coupon_discount = 0;
    $total_point = 0;
    $service_total = 0;

    // add-on service fee
    foreach ($carts as $cartItem) {
        if (isset($cartItem->add_service) && $cartItem->add_service == 1) {
            $product = get_single_product($cartItem->product_id);
            $service_total += ($product->service_fee ?? 0) * $cartItem->quantity;
        }
    }

    foreach ($carts as $cartItem) {
        $product = get_single_product($cartItem->product_id);

        $unitPrice = $product->unit_price;
        $unitTax = cart_product_tax($cartItem, $product, false);
        $quantity = $cartItem->quantity;

        $subtotal_for_min_order_amount += $unitPrice * $quantity;
        $subtotal += $unitPrice * $quantity;
        $tax += $unitTax * $quantity;
        $shipping += $cartItem->shipping_cost ?? 0;

        if (get_setting('coupon_system') == 1 && $cartItem->coupon_applied == 1) {
            $coupon_code = $cartItem->coupon_code;
            $coupon_discount = $carts->sum('discount');
        }

        if (addon_is_activated('club_point')) {
            $total_point += ($product->earn_point ?? 0) * $quantity;
        }
    }

    // grand total
    $total = $subtotal + $tax + $shipping + $service_total;

    if (Session::has('club_point')) {
        $total -= Session::get('club_point');
    }

    if ($coupon_discount > 0) {
        $total -= $coupon_discount;
    }
@endphp
@php
    $kcLocale = app()->getLocale();
    $kcIsAr = in_array($kcLocale, ['sa', 'ar', 'eg']);
    $kc3 = fn ($ar, $cn, $en) => $kcIsAr ? $ar : ($kcLocale == 'cn' ? $cn : $en);
    $kcQuoteLabel = $kc3('حمل عرض السعر', '下载报价', 'Download Quote');
    $kcPhName = $kc3('الاسم بالكامل', '全名', 'Full Name');
    $kcPhEmail = $kc3('البريد الإلكتروني', '电子邮箱', 'Email Address');
    $kcPhPhone = $kc3('رقم الجوال', '手机号码', 'Phone Number');
    $kcPhVat = $kc3('أدخل رقم ضريبة القيمة المضافة', '输入增值税号', 'Enter VAT Number');
@endphp

<!-- Cart drawer (include once per page) -->
<div class="offcanvas {{ app()->getLocale() == 'sa' ? 'offcanvas-start' : 'offcanvas-end' }} kn-cartdrawer" tabindex="-1" id="cartOffcanvas"
    aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header kn-cartdrawer-head">
        <h2 class="offcanvas-title cart-title" id="cartOffcanvasLabel">
            {{ $kc3('سلة الشراء', '购物车', 'Shopping Cart') }} <span>({{ sprintf('%d', $carts->count()) }})</span>
        </h2>
        <button type="button" class="kn-cartdrawer-close" data-bs-dismiss="offcanvas"
            aria-label="{{ translate('close') }}">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <div class="offcanvas-body kn-cartdrawer-body">
        <div class="cart-header">
            <div class="help-box">
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                <span>{{ $kc3('متردد أو بحاجة إلى مساعدة؟', '需要帮助吗？', 'Need help?') }}</span>
                <a href="#">{{ $kc3('تحدث مع مختص الآن', '立即与专家交谈', 'Talk to a specialist now') }}</a>
            </div>

            <div class="cart-top">
                @if ($carts->count() > 0)
                    @if ($shipping == 0)
                        <div class="free-shipping">
                            <i class="fa-solid fa-truck" aria-hidden="true"></i>
                            {{ $kc3('شحن مجانا', '免运费', 'Free Shipping') }}
                        </div>
                    @else
                        <div class="free-shipping">
                            <i class="fa-solid fa-truck" aria-hidden="true"></i>
                            {{ translate('Shipping') }}: {{ single_price($shipping) }}
                        </div>
                    @endif
                @else
                    <span></span>
                @endif

                <button type="button" id="openFormBtn" class="download-btn">
                    <i class="fa-solid fa-download" aria-hidden="true"></i>
                    {{ $kcQuoteLabel }}
                </button>

                <!-- Quote bottom sheet -->
                <div class="bottom-sheet" id="bottomSheet">
                    <div class="bottom-sheet-content" role="dialog" aria-modal="true" aria-labelledby="kn-quote-sheet-title">
                        <button type="button" class="close-btn" id="closeFormBtn" aria-label="{{ translate('close') }}">&times;</button>
                        <h3 class="sheet-title" id="kn-quote-sheet-title">{{ $kcQuoteLabel }}</h3>
                        <form action="{{ route('quotation.post') }}" method="POST" id="quotationForm">
                            @csrf
                            <div class="kn-sheet-field">
                                <input type="text" placeholder="{{ $kcPhName }}" aria-label="{{ $kcPhName }}" required>
                            </div>
                            <div class="kn-sheet-field">
                                <input type="email" placeholder="{{ $kcPhEmail }}" aria-label="{{ $kcPhEmail }}" required>
                            </div>
                            <div class="kn-sheet-field">
                                <input type="tel" placeholder="{{ $kcPhPhone }}" aria-label="{{ $kcPhPhone }}" required>
                            </div>
                            <div class="kn-sheet-field">
                                <input type="text" placeholder="{{ $kcPhVat }}" aria-label="{{ $kcPhVat }}">
                            </div>
                            <button type="submit" class="submit-btn kn-btn kn-btn-primary">{{ $kcQuoteLabel }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const openFormBtn = document.getElementById("openFormBtn");
                const closeFormBtn = document.getElementById("closeFormBtn");
                const bottomSheet = document.getElementById("bottomSheet");

                openFormBtn.addEventListener("click", () => {
                    bottomSheet.style.display = "flex";
                });

                closeFormBtn.addEventListener("click", () => {
                    bottomSheet.style.display = "none";
                });

                bottomSheet.addEventListener("click", (e) => {
                    if (e.target === bottomSheet) {
                        bottomSheet.style.display = "none";
                    }
                });
            </script>
        </div>

        <div class="cart-items">
            @forelse ($carts as $cartItem)
                @php
                    $product = get_single_product($cartItem->product_id);
                    $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
                    $unitPrice = $product->unit_price;
                    $lineTotal = $unitPrice * $cartItem->quantity;

                    $minQty = max(1, (int) ($product->min_qty ?? 1));
                    $maxQty = $product_stock ? (int) $product_stock->qty : null; // no stock row → no max
                @endphp

                <div class="cart-item">
                    <span class="kn-plinth cart-item-media" aria-hidden="true">
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="">
                    </span>
                    <div class="item-details">
                        <div class="top-row">
                            <div class="item-name">{{ $product->getTranslation('name') }}</div>
                            <div class="item-price">{{ single_price($lineTotal) }}</div>
                        </div>
                        <div class="actions">
                            <div class="qty-control" data-id="{{ $cartItem->id }}" data-min="{{ $minQty }}"
                                @if (!is_null($maxQty)) data-max="{{ $maxQty }}" @endif
                                data-name="{{ e($product->getTranslation('name')) }}">

                                <button type="button" class="qty-btn qty-minus"
                                    aria-label="{{ $kc3('إنقاص الكمية', '减少数量', 'Decrease quantity') }}">
                                    <i class="fa-solid fa-minus" aria-hidden="true"></i>
                                </button>
                                <input type="number" class="qty-input" value="{{ $cartItem->quantity }}"
                                    min="{{ $minQty }}" step="1"
                                    @if ($maxQty) max="{{ $maxQty }}" @endif
                                    inputmode="numeric" pattern="[0-9]*"
                                    aria-label="{{ translate('quantity') }}">
                                <button type="button" class="qty-btn qty-plus"
                                    aria-label="{{ $kc3('زيادة الكمية', '增加数量', 'Increase quantity') }}">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>

                            <button type="button" class="delete-box" data-id="{{ $cartItem->id }}"
                                onclick="removeFromCartView(event, {{ $cartItem->id }})"
                                title="{{ translate('Remove') }}"
                                aria-label="{{ translate('Remove') }}: {{ $product->getTranslation('name') }}">
                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                            </button>

                            <small class="qty-msg"></small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="kn-cartdrawer-empty">
                    <span class="kn-plinth kn-cartdrawer-empty-icon" aria-hidden="true">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span>
                    <p>{{ translate('Your cart is empty') }}</p>
                    <button type="button" class="kn-btn kn-co-btn-outline" data-bs-dismiss="offcanvas">
                        {{ $kc3('متابعة التسوق', '继续购物', 'Continue shopping') }}
                    </button>
                </div>
            @endforelse
        </div>


        <div class="cart-summary">
            <h3 class="summary-title">
                {{ $kc3('المبلغ الإجمالي', '总金额', 'Order Summary') }}
            </h3>

            <div class="summary-row">
                <span>{{ $kc3('منتجات', '产品', 'Products') }}</span>
                <span>{{ single_price($subtotal) }} <img src="{{ asset('currency-icon.png') }}" alt=""></span>
            </div>

            <div class="summary-row">
                <span>{{ $kc3('مصاريف الشحن', '运费', 'Shipping') }}</span>
                <span class="{{ $shipping == 0 ? 'free' : '' }}">
                    {{ $shipping == 0 ? $kc3('مجانا', '免费', 'Free') : single_price($shipping) }}
                    @if ($shipping != 0)
                        <img src="{{ asset('currency-icon.png') }}" alt="">
                    @endif
                </span>
            </div>

            <div class="summary-row">
                <span>{{ $kc3('المبلغ الإجمالي الخاضع للضريبة', '含税金额', 'Taxable Total') }}</span>
                <span>{{ single_price($subtotal + $shipping) }} <img src="{{ asset('currency-icon.png') }}"
                        alt=""></span>
            </div>

            <div class="summary-row">
                <span>{{ $kc3('إجمالي مبلغ ضريبة القيمة المضافة', '增值税金额', 'VAT Amount') }}</span>
                <span>{{ single_price($tax) }} <img src="{{ asset('currency-icon.png') }}" alt=""></span>
            </div>

            <div class="summary-row">
                <span>{{ $kc3('رصيد المتجر', '商店余额', 'Store Credit') }}</span>
                <span>- {{ single_price(Session::get('club_point') ?? 0) }}</span>
            </div>

            <div class="summary-total">
                <div>
                    <strong>{{ $kc3('المجموع', '总计', 'Total') }}</strong>
                    <span class="note">
                        {{ $kc3('(شامل ضريبة القيمة المضافة)', '(含增值税)', '(Inclusive of VAT)') }}
                    </span>
                </div>
                <strong class="price">{{ single_price($total) }} <img src="{{ asset('currency-icon.png') }}"
                        alt=""></strong>
            </div>

            <div class="success-msg">
                <i class="fa-regular fa-thumbs-up" aria-hidden="true"></i>
                {{ $kc3('تهانينا! لقد حصلت على أفضل الأسعار في السوق', '恭喜！您已获得市场上最优惠的价格', 'Congratulations! You’ve got the best prices in the market') }}
            </div>

            <div class="payment-methods">
                <img src="{{ static_asset('assets/new_logo_images/bank-transfer.png') }}" alt="Bank Transfer">
                <img src="{{ static_asset('assets/new_logo_images/cash-delivery.png') }}" alt="Cash on Delivery">
                <img src="{{ static_asset('assets/new_logo_images/apple-pay.png') }}" alt="Apple Pay">
                <img src="{{ static_asset('assets/new_logo_images/visa.png') }}" alt="Visa">
                <img src="{{ static_asset('assets/new_logo_images/mastercard.png') }}" alt="MasterCard">
                <img src="{{ static_asset('assets/new_logo_images/mada.png') }}" alt="Mada">
            </div>
        </div>


        @if ($carts->count() > 0)
            <div class="cart-footer">
                <div class="footer-wrap">
                    <div class="footer-info">
                        <div class="total-row">
                            <span class="total-label">
                                {{ translate('Total') }} <small>({{ translate('inclusive_of_vat') }})</small>
                            </span>
                        </div>

                        <div class="price-brand-row">
                            @php $header_logo = get_setting('header_logo'); @endphp
                            @if ($header_logo)
                                <img src="{{ uploaded_asset($header_logo) }}" alt="brand" class="brand-logo">
                            @endif
                            <span class="price-big">{{ single_price($total) }}</span>
                        </div>

                        @if ($shipping == 0)
                            <div class="best-price-note">
                                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                                {{ $kc3('ضمنت احسن سعر', '最佳价格', 'Best price guaranteed') }}
                            </div>
                        @endif
                    </div>

                    <div class="checkout-col">
                        <a href="{{ route('checkout') }}" class="checkout-btn kn-btn kn-btn-primary">
                            {{ translate('Proceed to Checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    /* ========= Cart Offcanvas + Qty (No Limits) ========= */
    (function waitForJQ() {
        if (!window.jQuery) {
            return setTimeout(waitForJQ, 30);
        }

        (function($) {
            'use strict';

            const LOCALE = '{{ app()->getLocale() }}';
            const $cart = $('#cartOffcanvas');
            const SEND_TO_SERVER = true; // set false to test the front end only

            /* ------- helpers ------- */
            function showMsg($wrap, txt) {
                if (!txt) return;
                let $m = $wrap.siblings('.qty-msg');
                if (!$m.length) {
                    $m = $('<small class="qty-msg text-danger d-block mt-1"></small>');
                    $wrap.after($m);
                }
                $m.stop(true, true).text(txt).show().delay(2000).fadeOut(200);
            }

            window.refreshCartToast = function() {
                const url = '{{ route('cart.offcanvas') }}';
                return $.get(url)
                    .done(function(res) {
                        const $html = $('<div>').html(res.html);

                        // items + summary
                        $('#cartOffcanvas .cart-items').replaceWith($html.find('.cart-items'));
                        $('#cartOffcanvas .cart-summary').replaceWith($html.find('.cart-summary'));

                        // ====== NEW: handle footer ======
                        const $newFooter = $html.find('.cart-footer'); // footer returned by the server
                        const $curFooter = $('#cartOffcanvas .cart-footer'); // footer currently shown

                        if ($newFooter.length) {
                            // there is a new footer
                            if ($curFooter.length) {
                                $curFooter.replaceWith($newFooter);
                            } else {
                                // none shown yet: append it at the end of the offcanvas body
                                $('#cartOffcanvas .offcanvas-body').append($newFooter);
                            }
                        } else {
                            // no footer returned (cart is empty) → remove it if present
                            $curFooter.remove();
                        }
                        // ================================

                        // counter and title
                        $('.cart-count-span').text(res.count);
                        $('#cartOffcanvas .cart-title span').text('(' + res.count + ')');
                    })
                    .fail(function() {
                        console.warn('refreshCartToast: request failed');
                    });
            };


            /* open the offcanvas safely even if refresh throws */
            window.openCartOffcanvas = function() {
                const el = document.getElementById('cartOffcanvas');
                if (!el) {
                    return;
                }
                try {
                    const p = window.refreshCartToast();
                    // if p is a jqXHR, open when it completes; otherwise open immediately
                    if (p && typeof p.always === 'function') {
                        p.always(function() {
                            bootstrap.Offcanvas.getOrCreateInstance(el).show();
                        });
                    } else {
                        bootstrap.Offcanvas.getOrCreateInstance(el).show();
                    }
                } catch (e) {
                    bootstrap.Offcanvas.getOrCreateInstance(el).show();
                }
            };

            // "go to cart" button, if present
            $(document).on('click', '.go-cart-btn', function(e) {
                e.preventDefault();
                openCartOffcanvas();
            });

            /* ------- API fallbacks ------- */
            window.updateQuantity = window.updateQuantity || function(key, el) {
                if (!SEND_TO_SERVER) return;
                $.post('{{ route('cart.updateQuantity') }}', {
                        _token: AIZ.data.csrf,
                        id: key,
                        quantity: el.value
                    })
                    .done(function(data) {
                        if (typeof updateNavCart === 'function') updateNavCart(data.nav_cart_view, data
                            .cart_count);
                        if ($('#cart-details').length) $('#cart-details').html(data.cart_view);
                        window.refreshCartToast && window.refreshCartToast();
                    });
            };

            window.removeFromCartView = window.removeFromCartView || function(e, key) {
                e && e.preventDefault();
                $.post('{{ route('cart.removeFromCart') }}', {
                        _token: AIZ.data.csrf,
                        id: key
                    })
                    .done(function(data) {
                        if (typeof updateNavCart === 'function') updateNavCart(data.nav_cart_view, data
                            .cart_count);
                        if ($('#cart-details').length) $('#cart-details').html(data.cart_view);
                        window.refreshCartToast && window.refreshCartToast();
                        AIZ?.plugins?.notify && AIZ.plugins.notify('success',
                            "{{ translate('Item_has_been_removed_from_cart') }}");
                    });
            };

            /* ------- QTY: no max limit ------- */
            $cart.on('click', '.qty-plus', function() {
                const $wrap = $(this).closest('.qty-control');
                const $input = $wrap.find('.qty-input');
                const id = $wrap.data('id');

                let v = parseInt($input.val(), 10);
                if (isNaN(v)) v = 0;
                v += 1;
                $input.val(v);
                updateQuantity(id, $input[0]);
            });

            $cart.on('click', '.qty-minus', function() {
                const $wrap = $(this).closest('.qty-control');
                const $input = $wrap.find('.qty-input');
                const id = $wrap.data('id');

                let v = parseInt($input.val(), 10);
                if (isNaN(v) || v <= 1) {
                    const msg = LOCALE === 'sa' ? 'أقل كمية هي 1' : LOCALE === 'cn' ? '最小数量为 1' :
                        'Minimum quantity is 1';
                    showMsg($wrap, msg);
                    v = 1;
                } else {
                    v -= 1;
                }
                $input.val(v);
                updateQuantity(id, $input[0]);
            });

            $cart.on('change', '.qty-input', function() {
                const $wrap = $(this).closest('.qty-control');
                const id = $wrap.data('id');
                let v = parseInt(this.value, 10);

                if (isNaN(v) || v < 1) {
                    v = 1;
                    const msg = LOCALE === 'sa' ? 'أقل كمية هي 1' : LOCALE === 'cn' ? '最小数量为 1' :
                        'Minimum quantity is 1';
                    showMsg($wrap, msg);
                }
                this.value = v;
                updateQuantity(id, this);
            });

        })(window.jQuery);
    })();
</script>
