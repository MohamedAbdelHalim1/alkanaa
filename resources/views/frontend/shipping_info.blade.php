@extends('frontend.layouts.app')

@section('content')
    @php
        $knIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $kt = fn ($ar, $en) => $knIsAr ? $ar : $en;
    @endphp

    <!-- Shipping Info (legacy multi-step checkout) -->
    <section class="kn-co-page">
        <div class="kn-wrap">
            <div class="kn-co-head">
                <h1 class="kn-co-title">{{ translate('Shipping Info') }}</h1>
                <a href="{{ route('cart') }}" class="kn-link">{{ $kt('العودة إلى السلة', 'Back to cart') }}</a>
            </div>

            @include('frontend.partials.cart.checkout_steps', ['current' => 2])

            <div class="kn-co-narrow">
                <form class="form-default" id="shipping_info_form" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                    @csrf
                    <div class="kn-co-section">
                        <div class="kn-co-section-body">
                            @if(Auth::check())
                                <p class="kn-co-sub">{{ $kt('اختر عنوان التوصيل', 'Choose a delivery address') }}</p>
                                <div class="kn-co-addr-grid">
                                    @foreach (Auth::user()->addresses as $key => $address)
                                        <div class="kn-co-addr">
                                            <label class="aiz-megabox kn-co-addr-option">
                                                <input type="radio" name="address_id" value="{{ $address->id }}" @if ($address->set_default)
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

                            <div class="kn-co-actions">
                                <!-- Return to shop -->
                                <a href="{{ route('home') }}" class="kn-btn kn-co-back">
                                    <i class="las la-arrow-left kn-co-flip" aria-hidden="true"></i>
                                    {{ translate('Return to shop')}}
                                </a>
                                <!-- Continue to Delivery Info -->
                                <button
                                    @if(Auth::check()) type="submit" @else type="button" onclick="submitShippingInfoForm(this)" @endif
                                    class="kn-btn kn-btn-primary kn-co-btn-lg"
                                >
                                    {{ translate('Continue to Delivery Info')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Address Modal -->
    @if(Auth::check())
        @include('frontend.partials.address.address_modal')
    @endif
@endsection

@section('script')
    @include('frontend.partials.address.address_js')
@endsection
