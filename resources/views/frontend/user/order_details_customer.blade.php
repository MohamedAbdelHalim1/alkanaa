@extends('frontend.layouts.user_panel')

@section('panel_content')
    @php
        $shipping = json_decode($order->shipping_address);
    @endphp

    <!-- Order id -->
    <div class="kn-page-head">
        <div>
            <a href="{{ route('purchase_history.index') }}" class="kn-auth-back" style="margin-top:0;">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Purchase History') }}
            </a>
            <h1 class="kn-page-title">{{ translate('Order id') }}: <bdi>{{ $order->code }}</bdi></h1>
        </div>
        <a class="kn-btn kn-btn-outline" href="{{ route('invoice.download', $order->id) }}">
            <i class="las la-download" aria-hidden="true"></i>
            <span>{{ translate('Download Invoice') }}</span>
        </a>
    </div>

    <!-- Order Summary -->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('Order Summary') }}</h2>
            <span class="kn-status is-info">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
        </div>
        <div class="kn-panel-body">
            <dl class="kn-kv is-2col">
                <div>
                    <dt>{{ translate('Order Code') }}</dt>
                    <dd><bdi>{{ $order->code }}</bdi></dd>
                </div>
                <div>
                    <dt>{{ translate('Order date') }}</dt>
                    <dd>{{ date('d-m-Y H:i A', $order->date) }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Customer') }}</dt>
                    <dd>{{ $shipping->name }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Order status') }}</dt>
                    <dd>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Email') }}</dt>
                    <dd>
                        @if ($order->user_id != null)
                            {{ $order->user->email }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt>{{ translate('Total order amount') }}</dt>
                    <dd>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Shipping address') }}</dt>
                    <dd>{{ $shipping->address }},
                        {{ $shipping->city }},
                        @if (isset($shipping->state)) {{ $shipping->state }} - @endif
                        {{ $shipping->postal_code }},
                        {{ $shipping->country }}
                    </dd>
                </div>
                <div>
                    <dt>{{ translate('Shipping method') }}</dt>
                    <dd>{{ translate('Flat shipping rate') }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Payment method') }}</dt>
                    <dd>{{ ucfirst(translate(str_replace('_', ' ', $order->payment_type))) }}</dd>
                </div>
                <div>
                    <dt>{{ translate('Additional Info') }}</dt>
                    <dd>{{ $order->additional_info }}</dd>
                </div>
                @if ($order->tracking_code)
                    <div>
                        <dt>{{ translate('Tracking code') }}</dt>
                        <dd><bdi>{{ $order->tracking_code }}</bdi></dd>
                    </div>
                @endif
            </dl>
        </div>
    </section>

    <!-- Order Details -->
    <div class="kn-order-layout">
        <section class="kn-panel">
            <div class="kn-panel-head">
                <h2 class="kn-panel-title">{{ translate('Order Details') }}</h2>
            </div>
            <div class="kn-panel-body is-flush">
                <div class="kn-table-scroll">
                    <table class="aiz-table table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Product') }}</th>
                                <th>{{ translate('Variation') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th>{{ translate('Delivery Type') }}</th>
                                <th>{{ translate('Price') }}</th>
                                @if (addon_is_activated('refund_request'))
                                    <th>{{ translate('Refund') }}</th>
                                @endif
                                <th class="kn-td-end">{{ translate('Review') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ sprintf('%02d', $key + 1) }}</td>
                                    <td style="min-width: 180px;">
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $orderDetail->variation }}
                                    </td>
                                    <td>
                                        {{ $orderDetail->quantity }}
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->name }} ({{ translate('Pickip Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="fw-700">{{ single_price($orderDetail->price) }}</td>
                                    @if (addon_is_activated('refund_request'))
                                        @php
                                            $no_of_max_day = get_setting('refund_request_time');
                                            $last_refund_date = Carbon\Carbon::parse($order->delivered_date)->addDays($no_of_max_day);
                                            $today_date = Carbon\Carbon::now();
                                        @endphp
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->refundable != 0 && $orderDetail->refund_request == null && $today_date <= $last_refund_date && $order->payment_status == 'paid' && $order->delivery_status == 'delivered')
                                                <a href="{{ route('refund_request_send_page', $orderDetail->id) }}" class="btn btn-primary btn-sm">{{ translate('Send') }}</a>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                <b class="text-info">{{ translate('Pending') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2)
                                                <b class="text-success">{{ translate('Rejected') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                <b class="text-success">{{ translate('Approved') }}</b>
                                            @elseif ($orderDetail->product->refundable != 0)
                                                <b>{{ translate('N/A') }}</b>
                                            @else
                                                <b>{{ translate('Non-refundable') }}</b>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="kn-td-end">
                                        @if ($orderDetail->delivery_status == 'delivered')
                                            <a href="javascript:void(0);"
                                                onclick="product_review('{{ $orderDetail->product_id }}')"
                                                class="btn btn-primary btn-sm"> {{ translate('Review') }} </a>
                                        @else
                                            <span class="text-muted">{{ translate('Not Delivered Yet') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Order Amount -->
        <aside class="kn-order-side">
            <section class="kn-panel">
                <div class="kn-panel-head">
                    <h2 class="kn-panel-title">{{ translate('Order Ammount') }}</h2>
                </div>
                <div class="kn-panel-body">
                    <dl class="kn-kv">
                        <div>
                            <dt>{{ translate('Subtotal') }}</dt>
                            <dd>{{ single_price($order->orderDetails->sum('price')) }}</dd>
                        </div>
                        <div>
                            <dt>{{ translate('Shipping') }}</dt>
                            <dd>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</dd>
                        </div>
                        <div>
                            <dt>{{ translate('Tax') }}</dt>
                            <dd>{{ single_price($order->orderDetails->sum('tax')) }}</dd>
                        </div>
                        <div>
                            <dt>{{ translate('Coupon') }}</dt>
                            <dd>{{ single_price($order->coupon_discount) }}</dd>
                        </div>
                        <div class="is-total">
                            <dt>{{ translate('Total') }}</dt>
                            <dd>{{ single_price($order->grand_total) }}</dd>
                        </div>
                    </dl>
                    @if ($order->payment_status == 'unpaid' && $order->delivery_status == 'pending' && $order->manual_payment == 0)
                        <button type="button"
                            @if (addon_is_activated('offline_payment'))
                                onclick="select_payment_type({{ $order->id }})"
                            @else
                                onclick="online_payment({{ $order->id }})"
                            @endif
                            class="kn-btn kn-btn-primary">
                            {{ translate('Make Payment') }}
                        </button>
                    @endif
                </div>
            </section>
        </aside>
    </div>
@endsection

@section('modal')
    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="payment_type_select_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
                    <div class="kn-field">
                        <label>{{ translate('Payment Type') }}</label>
                        <select class="form-control aiz-selectpicker" onchange="payment_modal(this.value)"
                            data-minimum-results-for-search="Infinity">
                            <option value="">{{ translate('Select One') }}</option>
                            <option value="online">{{ translate('Online payment') }}</option>
                            <option value="offline">{{ translate('Offline payment') }}</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-light transition-3d-hover mr-1"
                            id="payment_select_type_modal_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online payment Modal -->
    <div class="modal fade" id="online_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Make Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body gry-bg px-3 pt-3" style="overflow-y: inherit;">
                    <form class="" action="{{ route('order.re_payment') }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="kn-field">
                            <label>{{ translate('Payment Method') }}</label>
                            <select class="form-control selectpicker" data-live-search="true" name="payment_option" required>
                                @includeIf('partials.online_payment_options')
                                @if (get_setting('wallet_system') == 1 && (auth()->user()->balance >= $order->grand_total))
                                    <option value="wallet">{{ translate('Wallet') }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-light transition-3d-hover mr-1"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- offline payment Modal -->
    <div class="modal fade" id="offline_order_re_payment_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Order Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_order_re_payment_modal_body"></div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">

        function product_review(product_id) {
            $.post('{{ route('product_review_modal') }}', {
                _token: '{{ @csrf_token() }}',
                product_id: product_id
            }, function(data) {
                $('#product-review-modal-content').html(data);
                $('#product-review-modal').modal('show', {
                    backdrop: 'static'
                });
                AIZ.extra.inputRating();
            });
        }

        function select_payment_type(id) {
            $('#payment_type_select_modal').modal('show');
        }

        function payment_modal(type) {
            if (type == 'online') {
                $("#payment_select_type_modal_cancel").click();
                online_payment();
            } else if (type == 'offline') {
                $("#payment_select_type_modal_cancel").click();
                $.post('{{ route('offline_order_re_payment_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    order_id: '{{ $order->id }}'
                }, function(data) {
                    $('#offline_order_re_payment_modal_body').html(data);
                    $('#offline_order_re_payment_modal').modal('show');
                });
            }
        }

        function online_payment() {
            $('input[name=customer_package_id]').val();
            $('#online_payment_modal').modal('show');
        }

    </script>
@endsection
