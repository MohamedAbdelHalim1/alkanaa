@php
    $kaIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $kat = fn ($ar, $en) => $kaIsAr ? $ar : $en;
@endphp
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Auth::check())
    <p class="kn-co-sub">{{ $kat('اختر عنوان التوصيل', 'Choose a delivery address') }}</p>
    <div class="kn-co-addr-grid">
        @foreach (Auth::user()->addresses as $key => $address)
            <div class="kn-co-addr">
                <label class="aiz-megabox kn-co-addr-option">
                    <input type="radio" name="address_id" value="{{ $address->id }}" @if ($address->id == $address_id)
                        checked
                    @endif required>
                    <span class="aiz-megabox-elem kn-co-addr-card">
                        <span class="kn-co-addr-top">
                            <span class="aiz-rounded-check flex-shrink-0" aria-hidden="true"></span>
                            <span class="kn-co-addr-name">
                                {{ optional($address->city)->name }}@if (optional($address->state)->name), {{ optional($address->state)->name }}@endif
                            </span>
                        </span>
                        <span class="kn-co-addr-line">{{ $address->address }}</span>
                        <span class="kn-co-addr-meta">
                            {{ optional($address->country)->name }}
                            @if ($address->postal_code)
                                · {{ translate('Postal Code') }} {{ $address->postal_code }}
                            @endif
                        </span>
                        @if ($address->phone)
                            <span class="kn-co-addr-meta is-phone">
                                <i class="las la-phone" aria-hidden="true"></i>
                                <span class="kn-sr-only">{{ translate('Phone') }}:</span>
                                {{ $address->phone }}
                            </span>
                        @endif
                    </span>
                </label>
                <!-- Edit Address Button -->
                <button type="button" class="kn-co-addr-edit" onclick="edit_address('{{$address->id}}')">
                    <i class="las la-pen" aria-hidden="true"></i>
                    {{ translate('Change') }}
                </button>
            </div>
        @endforeach

        <!-- Add New Address -->
        <button type="button" class="kn-co-addr-add" onclick="add_new_address()">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Add New Address') }}</span>
        </button>
    </div>

    <input type="hidden" name="checkout_type" value="logged">
@else
    <!-- Guest Shipping a address -->
    @include('frontend.partials.cart.guest_shipping_info')
@endif
