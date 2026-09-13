@php
    $kpIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $kpt = fn ($ar, $en) => $kpIsAr ? $ar : $en;
@endphp
<div class="kn-co-field kn-co-pay-note">
    <label for="kn-additional-info" class="kn-co-sub">{{ translate('Any additional info') }}</label>
    <textarea id="kn-additional-info" name="additional_info" rows="3" class="form-control"
        placeholder="{{ translate('Type your text') }}"></textarea>
</div>

<fieldset class="kn-co-fieldset">
    <legend class="kn-co-sub">{{ translate('Select a payment option') }}</legend>

    <div class="kn-co-pay-grid">
        @foreach (get_activate_payment_methods() as $payment_method)
            <label class="aiz-megabox kn-co-pay-option">
                <input value="{{ $payment_method->name }}" class="online_payment" type="radio"
                    name="payment_option">
                <span class="aiz-megabox-elem kn-co-pay-tile">
                    <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                    <span class="kn-co-pay-name">{{ ucfirst(translate($payment_method->name)) }}</span>
                    <span class="kn-co-pay-logo" aria-hidden="true">
                        <img src="{{ static_asset('assets/img/cards/' . $payment_method->name . '.png') }}" alt="" loading="lazy">
                    </span>
                </span>
            </label>
        @endforeach

        {{-- Moyasar Online Payment --}}
        <label class="aiz-megabox kn-co-pay-option">
            <input value="moyasar" class="online_payment" type="radio" name="payment_option">
            <span class="aiz-megabox-elem kn-co-pay-tile">
                <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                <span class="kn-co-pay-name">{{ $kpt('ميسر (دفع إلكتروني)', 'Moyasar (Online)') }}</span>
                <span class="kn-co-pay-logo" aria-hidden="true">
                    <img src="{{ static_asset('assets/img/cards/moyasar.svg') }}" alt="" loading="lazy">
                </span>
            </span>
        </label>

        {{-- Tabby Online Payment --}}
        <label class="aiz-megabox kn-co-pay-option">
            <input value="tabby" class="online_payment" type="radio" name="payment_option">
            <span class="aiz-megabox-elem kn-co-pay-tile">
                <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                <span class="kn-co-pay-name">{{ $kpt('Tabby (التقسيط)', 'Tabby (Installments)') }}</span>
                <span class="kn-co-pay-logo" aria-hidden="true">
                    <img src="{{ static_asset('assets/img/cards/tabby.png') }}" alt="" loading="lazy">
                </span>
            </span>
        </label>

        <!-- Cash Payment -->
        @if (get_setting('cash_payment') == 1)
            @php
                $digital = 0;
                $cod_on = 1;
                foreach ($carts as $cartItem) {
                    $product = get_single_product($cartItem['product_id']);
                    if ($product['digital'] == 1) {
                        $digital = 1;
                    }
                    if ($product['cash_on_delivery'] == 0) {
                        $cod_on = 0;
                    }
                }
            @endphp
            @if ($digital != 1 && $cod_on == 1)
                <label class="aiz-megabox kn-co-pay-option">
                    <input value="cash_on_delivery" class="online_payment" type="radio" name="payment_option"
                        checked>
                    <span class="aiz-megabox-elem kn-co-pay-tile">
                        <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                        <span class="kn-co-pay-name">{{ translate('Cash on Delivery') }}</span>
                        <span class="kn-co-pay-logo" aria-hidden="true">
                            <img src="{{ static_asset('assets/img/cards/cod.png') }}" alt="" loading="lazy">
                        </span>
                    </span>
                </label>
            @endif
        @endif

        @if (Auth::check())
            <!-- Offline Payment -->
            @if (addon_is_activated('offline_payment'))
                @foreach (get_all_manual_payment_methods() as $method)
                    <label class="aiz-megabox kn-co-pay-option">
                        <input value="{{ $method->heading }}" type="radio" name="payment_option"
                            class="offline_payment_option" onchange="toggleManualPaymentData({{ $method->id }})"
                            data-id="{{ $method->id }}">
                        <span class="aiz-megabox-elem kn-co-pay-tile">
                            <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                            <span class="kn-co-pay-name">{{ $method->heading }}</span>
                            <span class="kn-co-pay-logo" aria-hidden="true">
                                <img src="{{ uploaded_asset($method->photo) }}" alt="" loading="lazy">
                            </span>
                        </span>
                    </label>
                @endforeach
            @endif
        @endif
    </div>

    @if (Auth::check() && addon_is_activated('offline_payment'))
        @foreach (get_all_manual_payment_methods() as $method)
            <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                @php echo $method->description @endphp
                @if ($method->bank_info != null)
                    <ul>
                        @foreach (json_decode($method->bank_info) as $key => $info)
                            <li>{{ translate('Bank Name') }} -
                                {{ $info->bank_name }},
                                {{ translate('Account Name') }} -
                                {{ $info->account_name }},
                                {{ translate('Account Number') }} -
                                {{ $info->account_number }},
                                {{ translate('Routing Number') }} -
                                {{ $info->routing_number }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Offline Payment Fields -->
    @if (addon_is_activated('offline_payment') && count(get_all_manual_payment_methods()) > 0)
        <div class="d-none kn-co-manual">
            <div id="manual_payment_description">

            </div>
            <div class="kn-co-fields">
                <div class="kn-co-field">
                    <label for="trx_id">{{ translate('Transaction ID') }} <span class="kn-co-req" aria-hidden="true">*</span></label>
                    <input type="text" class="form-control" name="trx_id"
                        onchange="stepCompletionPaymentInfo()" id="trx_id"
                        placeholder="{{ translate('Transaction ID') }}" required>
                </div>
                <div class="kn-co-field">
                    <label>{{ translate('Photo') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                {{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose image') }}
                        </div>
                        <input type="hidden" name="photo" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Wallet Payment -->
    @if (Auth::check() && get_setting('wallet_system') == 1)
        <div class="kn-co-wallet">
            <p class="kn-co-wallet-text">
                <span>{{ translate('Or, Your wallet balance :') }}</span>
                <strong>{{ single_price(Auth::user()->balance) }}</strong>
            </p>
            @if (Auth::user()->balance < $total)
                <button type="button" class="kn-btn kn-co-btn-outline" disabled>
                    {{ translate('Insufficient balance') }}
                </button>
            @else
                <button type="button" onclick="use_wallet()" class="kn-btn kn-btn-primary">
                    <i class="las la-wallet" aria-hidden="true"></i>
                    {{ translate('Pay with wallet') }}
                </button>
            @endif
        </div>
    @endif
</fieldset>
