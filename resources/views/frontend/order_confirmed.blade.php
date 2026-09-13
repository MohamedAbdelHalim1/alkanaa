@extends('frontend.layouts.app')

@section('content')
    @php
        $knIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $kt = fn ($ar, $en) => $knIsAr ? $ar : $en;
        $first_order = $combined_order->orders->first();
        $knShip = json_decode($first_order->shipping_address);
    @endphp

    <section class="kn-co-page">
        <div class="kn-wrap">
            <div class="kn-oc-wrap">
                @include('frontend.partials.cart.checkout_steps', ['current' => 5, 'links' => false])

                <!-- Order Confirmation Text -->
                <div class="kn-oc-hero">
                    <span class="kn-oc-hero-icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
                    <h1 class="kn-oc-hero-title">{{ translate('Thank You for Your Order!') }}</h1>
                    <p class="kn-oc-hero-text">
                        {{ translate('A copy or your order summary has been sent to') }}
                        <strong>{{ $knShip->email ?? '' }}</strong>
                    </p>
                    <div class="kn-oc-codes">
                        @foreach ($combined_order->orders as $order)
                            <span class="kn-oc-code">{{ translate('Order Code:') }} <strong>{{ $order->code }}</strong></span>
                        @endforeach
                    </div>
                    <div class="kn-oc-actions">
                        <a href="{{ route('orders.track') }}" class="kn-btn kn-co-btn-outline">
                            <i class="las la-map-marker" aria-hidden="true"></i>
                            {{ translate('Track Order') }}
                        </a>
                        <a href="{{ route('home') }}" class="kn-btn kn-btn-primary">{{ $kt('متابعة التسوق', 'Continue shopping') }}</a>
                    </div>
                </div>

                <!-- Order Summary -->
                <section class="kn-co-card" aria-labelledby="kn-oc-summary-title">
                    <header class="kn-co-card-head">
                        <h2 class="kn-co-card-title" id="kn-oc-summary-title">{{ translate('Order Summary') }}</h2>
                    </header>
                    <div class="kn-co-card-body">
                        <dl class="kn-oc-facts">
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Order date') }}</dt>
                                <dd>{{ date('d-m-Y H:i A', $first_order->date) }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Order status') }}</dt>
                                <dd>{{ translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Name') }}</dt>
                                <dd>{{ $knShip->name ?? '' }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Total order amount') }}</dt>
                                <dd>{{ single_price($combined_order->grand_total) }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Email') }}</dt>
                                <dd>{{ $knShip->email ?? '' }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Shipping') }}</dt>
                                <dd>{{ translate('Flat shipping rate') }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Shipping address') }}</dt>
                                <dd>{{ $knShip->address ?? '' }}, {{ $knShip->city ?? '' }}, {{ $knShip->country ?? '' }}</dd>
                            </div>
                            <div class="kn-oc-fact">
                                <dt>{{ translate('Payment method') }}</dt>
                                <dd>{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <!-- Orders Info -->
                @foreach ($combined_order->orders as $order)
                    <section class="kn-co-card" aria-label="{{ translate('Order Details') }} {{ $order->code }}">
                        <header class="kn-co-card-head">
                            <h2 class="kn-co-card-title">{{ translate('Order Details') }}</h2>
                            <span class="kn-oc-code">{{ translate('Order Code:') }} <strong>{{ $order->code }}</strong></span>
                        </header>
                        <div class="kn-co-card-body">
                            <!-- Product Details -->
                            <div class="kn-co-table-scroll" role="region" tabindex="0"
                                aria-label="{{ translate('Order Details') }} {{ $order->code }}">
                                <table class="table kn-co-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ translate('Product') }}</th>
                                            <th>{{ translate('Variation') }}</th>
                                            <th>{{ translate('Quantity') }}</th>
                                            <th>{{ translate('Delivery Type') }}</th>
                                            <th class="is-num">{{ translate('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderDetails as $key => $orderDetail)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="is-name">
                                                    @if ($orderDetail->product != null)
                                                        <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="kn-co-table-link">
                                                            {{ $orderDetail->product->getTranslation('name') }}
                                                            @php
                                                                if($orderDetail->combo_id != null) {
                                                                    $combo = \App\ComboProduct::findOrFail($orderDetail->combo_id);

                                                                    echo '('.$combo->combo_title.')';
                                                                }
                                                            @endphp
                                                        </a>
                                                    @else
                                                        <strong>{{ translate('Product Unavailable') }}</strong>
                                                    @endif
                                                </td>
                                                <td>{{ $orderDetail->variation }}</td>
                                                <td>{{ $orderDetail->quantity }}</td>
                                                <td>
                                                    @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                        {{ translate('Home Delivery') }}
                                                    @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                        {{ translate('Carrier') }}
                                                    @elseif ($order->shipping_type == 'pickup_point')
                                                        @if ($order->pickup_point != null)
                                                            {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="is-num">{{ single_price($orderDetail->price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Order Amounts -->
                            <div class="kn-oc-totals">
                                <dl class="kn-cart-sum-rows">
                                    <div class="kn-cart-sum-row">
                                        <dt>{{ translate('Subtotal') }}</dt>
                                        <dd>{{ single_price($order->orderDetails->sum('price')) }}</dd>
                                    </div>
                                    <div class="kn-cart-sum-row">
                                        <dt>{{ translate('Shipping') }}</dt>
                                        <dd>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</dd>
                                    </div>
                                    <div class="kn-cart-sum-row">
                                        <dt>{{ translate('Tax') }}</dt>
                                        <dd>{{ single_price($order->orderDetails->sum('tax')) }}</dd>
                                    </div>
                                    <div class="kn-cart-sum-row is-discount">
                                        <dt>{{ translate('Coupon Discount') }}</dt>
                                        <dd>{{ single_price($order->coupon_discount) }}</dd>
                                    </div>
                                </dl>
                                <div class="kn-cart-sum-total">
                                    <span>{{ translate('Total') }}</span>
                                    <strong>{{ single_price($order->grand_total) }}</strong>
                                </div>
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    @if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel purchase Event -->
    <script>
        $(document).ready(function(){
            var currend_code = '{{ get_system_currency()->code }}';
            var amount = 'single_price($combined_order->grand_total) }}';
            fbq('track', 'Purchase',
                {
                    value: amount,
                    currency: currend_code,
                    content_type: 'product'
                }
            );
        });
    </script>
    <!-- Facebook Pixel purchase Event -->
    @endif
@endsection
