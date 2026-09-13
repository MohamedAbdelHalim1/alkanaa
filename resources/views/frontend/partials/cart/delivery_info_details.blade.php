@php
    $physical = false;
    foreach ($products as $key => $cartItem){
        $product = get_single_product($cartItem);
        if ($product->digital == 0) {
            $physical = true;
        }
    }
@endphp
<div class="kn-co-dlv {{ $physical ? 'has-options' : '' }}">
    <!-- Product List -->
    <ul class="kn-co-dlv-items">
        @foreach ($products as $key => $cartItem)
            @php
                $product = get_single_product($cartItem);
            @endphp
            <li class="kn-co-dlv-item">
                <span class="kn-plinth kn-co-dlv-thumb" aria-hidden="true">
                    <img src="{{ get_image($product->thumbnail) }}" alt="" loading="lazy"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </span>
                <span class="kn-co-dlv-name">
                    {{ $product->getTranslation('name') }}
                    @if ($product_variation[$key] != '')
                        <small>{{ translate('Variation') }}: {{ $product_variation[$key] }}</small>
                    @endif
                </span>
            </li>
        @endforeach
    </ul>

    @if ($physical)
        <!-- Choose Delivery Type -->
        <div class="kn-co-dlv-options">
            <h4 class="kn-co-sub">{{ translate('Choose Delivery Type') }}</h4>
            <div class="kn-co-choice-grid">
                <!-- Home Delivery -->
                @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                    <label class="aiz-megabox kn-co-choice">
                        <input
                            type="radio"
                            name="shipping_type_{{ $owner_id }}"
                            value="home_delivery"
                            onchange="show_pickup_point(this, {{ $owner_id }})"
                            data-target=".pickup_point_id_{{ $owner_id }}"
                            checked required>
                        <span class="aiz-megabox-elem kn-co-choice-card">
                            <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                            <i class="las la-truck" aria-hidden="true"></i>
                            <span>{{ translate('Home Delivery') }}</span>
                        </span>
                    </label>
                <!-- Carrier -->
                @else
                    <label class="aiz-megabox kn-co-choice">
                        <input
                            type="radio"
                            name="shipping_type_{{ $owner_id }}"
                            value="carrier"
                            class="shipping-type-radio"
                            data-owner="{{ $owner_id }}"
                            onchange="show_pickup_point(this, {{ $owner_id }})"
                            data-target=".pickup_point_id_{{ $owner_id }}"
                            checked required>
                        <span class="aiz-megabox-elem kn-co-choice-card">
                            <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                            <i class="las la-shipping-fast" aria-hidden="true"></i>
                            <span>{{ translate('Carrier') }}</span>
                        </span>
                    </label>
                @endif
                <!-- Local Pickup -->
                @if ($pickup_point_list)
                    <label class="aiz-megabox kn-co-choice">
                        <input
                            type="radio"
                            name="shipping_type_{{ $owner_id }}"
                            value="pickup_point"
                            class="shipping-type-radio"
                            data-owner="{{ $owner_id }}"
                            onchange="show_pickup_point(this, {{ $owner_id }})"
                            data-target=".pickup_point_id_{{ $owner_id }}"
                            required>
                        <span class="aiz-megabox-elem kn-co-choice-card">
                            <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                            <i class="las la-store" aria-hidden="true"></i>
                            <span>{{ translate('Local Pickup') }}</span>
                        </span>
                    </label>
                @endif
            </div>

            <!-- Pickup Point List -->
            @if ($pickup_point_list)
                <div class="kn-co-dlv-pickup pickup_point_id_{{ $owner_id }} d-none">
                    <select
                        class="form-control aiz-selectpicker"
                        name="pickup_point_id_{{ $owner_id }}"
                        data-live-search="true"
                        aria-label="{{ translate('Select your nearest pickup point')}}"
                        onchange="updateDeliveryInfo('pickup_point', this.value, {{ $owner_id }})">
                        <option value="">{{ translate('Select your nearest pickup point')}}</option>
                        @foreach ($pickup_point_list as $pick_up_point)
                        <option
                            value="{{ $pick_up_point->id }}"
                            data-content="<span class='d-block'>
                                                        <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                        <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                        <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                    </span>">
                        </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Carrier Wise Shipping -->
            @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                <div class="kn-co-carrier-list carrier_id_{{ $owner_id }}">
                    @if($carrier_list->isEmpty())
                        <div class="alert alert-danger mb-0" role="alert">
                            <strong>{{ translate('Shipping is not available to your selected address.') }}</strong><br>
                            {{ translate('Please choose a different address.') }}
                        </div>
                        <span class="shipping-unavailable-flag" style="display: none;"></span>
                    @else
                        @foreach($carrier_list as $carrier_key => $carrier)
                            <label class="aiz-megabox kn-co-carrier">
                                <input
                                    type="radio"
                                    name="carrier_id_{{ $owner_id }}"
                                    value="{{ $carrier->id }}"
                                    @if($carrier_key==0) checked @endif
                                    onchange="updateDeliveryInfo('carrier', {{ $carrier->id }}, {{ $owner_id }})">
                                <span class="aiz-megabox-elem kn-co-carrier-card">
                                    <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                                    <span class="kn-plinth kn-co-carrier-logo" aria-hidden="true">
                                        <img src="{{ uploaded_asset($carrier->logo)}}" alt="">
                                    </span>
                                    <span class="kn-co-carrier-info">
                                        <span class="kn-co-carrier-name">{{ $carrier->name }}</span>
                                        <span class="kn-co-carrier-time">{{ translate('Transit in').' '.$carrier->transit_time }}</span>
                                    </span>
                                    <span class="kn-co-carrier-price">{{ single_price(carrier_base_price($carts, $carrier->id, $owner_id, $shipping_info)) }}</span>
                                </span>
                            </label>
                        @endforeach
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
