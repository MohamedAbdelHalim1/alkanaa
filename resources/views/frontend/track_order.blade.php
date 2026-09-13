@extends('frontend.layouts.app')

@section('content')
    @php
        $knIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $kt = fn ($ar, $en) => $knIsAr ? $ar : $en;
    @endphp
    <section class="kn-co-page">
        <div class="kn-wrap">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 kn-trk-crumbs">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ translate('Track Order') }}</li>
                </ol>
            </nav>

            <div class="kn-trk-card">
                <h1 class="kn-co-title">{{ translate('Track Order') }}</h1>
                <p class="kn-trk-lead">{{ translate('Check Your Order Status') }}</p>
                <form class="kn-trk-form" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                    <label for="kn-trk-code">{{ translate('Order Code') }}</label>
                    <div class="kn-trk-field">
                        <input type="text" id="kn-trk-code" class="form-control" placeholder="{{ translate('Order Code')}}"
                            name="order_code" value="{{ request('order_code') }}" autocomplete="off" required>
                        <button type="submit" class="kn-btn kn-btn-primary">{{ translate('Track Order')}}</button>
                    </div>
                </form>
            </div>

            @if (request()->filled('order_code') && !isset($order))
                <div class="alert alert-warning kn-trk-msg" role="status">
                    {{ $kt('لم نعثر على طلب بهذا الرمز. تأكد من الرمز وحاول مرة أخرى.', 'No order found with this code. Please check the code and try again.') }}
                </div>
            @endif

            @isset($order)
                @php
                    $knShip = json_decode($order->shipping_address);
                    $status = $order->delivery_status;
                    $knFlow = [
                        'pending' => translate('Pending'),
                        'confirmed' => translate('Confirmed'),
                        'picked_up' => translate('Picked Up'),
                        'on_the_way' => translate('On The Way'),
                        'delivered' => translate('Delivered'),
                    ];
                    $knPos = array_search($status, array_keys($knFlow), true);
                @endphp

                <div class="kn-trk-result">
                    <!-- Status timeline -->
                    <section class="kn-co-card" aria-labelledby="kn-trk-status-title">
                        <header class="kn-co-card-head">
                            <h2 class="kn-co-card-title" id="kn-trk-status-title">{{ translate('Delivery Status') }}</h2>
                            <span class="kn-oc-code">{{ translate('Order Code') }}: <strong>{{ $order->code }}</strong></span>
                        </header>
                        <div class="kn-co-card-body">
                            @if ($status == 'cancelled')
                                <div class="alert alert-danger mb-0" role="status">
                                    {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                </div>
                            @else
                                <ol class="kn-trk-timeline">
                                    @foreach ($knFlow as $knKey => $knLabel)
                                        @php
                                            $knIdx = $loop->index;
                                            if ($knPos === false) {
                                                $knState = 'is-todo';
                                            } elseif ($knIdx < $knPos || ($status == 'delivered' && $knIdx == $knPos)) {
                                                $knState = 'is-done';
                                            } elseif ($knIdx === $knPos) {
                                                $knState = 'is-current';
                                            } else {
                                                $knState = 'is-todo';
                                            }
                                        @endphp
                                        <li class="kn-trk-tl-item {{ $knState }}" @if ($knIdx === $knPos) aria-current="step" @endif>
                                            <span class="kn-trk-tl-dot" aria-hidden="true">
                                                @if ($knState === 'is-done')
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    {{ $knIdx + 1 }}
                                                @endif
                                            </span>
                                            <span class="kn-trk-tl-label">{{ $knLabel }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                                @if ($knPos === false)
                                    <p class="kn-trk-lead mb-0">{{ translate('Delivery Status') }}: {{ translate(ucfirst(str_replace('_', ' ', $status))) }}</p>
                                @endif
                            @endif
                        </div>
                    </section>

                    <!-- Order Summary -->
                    <section class="kn-co-card" aria-labelledby="kn-trk-summary-title">
                        <header class="kn-co-card-head">
                            <h2 class="kn-co-card-title" id="kn-trk-summary-title">{{ translate('Order Summary') }}</h2>
                        </header>
                        <div class="kn-co-card-body">
                            <dl class="kn-oc-facts">
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Order Code') }}</dt>
                                    <dd>{{ $order->code }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Order date') }}</dt>
                                    <dd>{{ date('d-m-Y H:i A', $order->date) }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Customer') }}</dt>
                                    <dd>{{ $knShip->name ?? '' }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Total order amount') }}</dt>
                                    <dd>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Email') }}</dt>
                                    <dd>
                                        @if ($order->user_id != null)
                                            {{ optional($order->user)->email }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Shipping method') }}</dt>
                                    <dd>{{ translate('Flat shipping rate') }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Shipping address') }}</dt>
                                    <dd>{{ $knShip->address ?? '' }}, {{ $knShip->city ?? '' }}, {{ $knShip->country ?? '' }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Payment method') }}</dt>
                                    <dd>{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</dd>
                                </div>
                                <div class="kn-oc-fact">
                                    <dt>{{ translate('Delivery Status') }}</dt>
                                    <dd>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</dd>
                                </div>
                                @if ($order->tracking_code)
                                    <div class="kn-oc-fact">
                                        <dt>{{ translate('Tracking code') }}</dt>
                                        <dd>{{ $order->tracking_code }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </section>

                    <!-- Items -->
                    <section class="kn-co-card" aria-label="{{ translate('Product Name') }}">
                        <div class="kn-co-card-body">
                            <div class="kn-co-table-scroll" role="region" tabindex="0" aria-label="{{ translate('Order Code') }} {{ $order->code }}">
                                <table class="table kn-co-table">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Product Name') }}</th>
                                            <th>{{ translate('Quantity') }}</th>
                                            <th>{{ translate('Shipped By') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderDetails as $key => $orderDetail)
                                            @if ($orderDetail->product != null)
                                                <tr>
                                                    <td class="is-name">
                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                        @if ($orderDetail->variation)
                                                            ({{ $orderDetail->variation }})
                                                        @endif
                                                    </td>
                                                    <td>{{ $orderDetail->quantity }}</td>
                                                    <td>{{ $orderDetail->product->user->name }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            @endisset
        </div>
    </section>
@endsection
