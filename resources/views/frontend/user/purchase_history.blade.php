@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Purchase History') }}</h1>
    </div>

    @php
        $hasOrders = $orders->contains(fn($o) => count($o->orderDetails) > 0);
    @endphp

    @if ($hasOrders)
        <section class="kn-panel">
            <!-- Desktop / tablet: table -->
            <div class="kn-orders-table">
                <div class="kn-table-scroll">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Code') }}</th>
                                <th>{{ translate('Date') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Delivery Status') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                <th class="kn-td-end">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key => $order)
                                @if (count($order->orderDetails) > 0)
                                    <tr>
                                        <!-- Code -->
                                        <td>
                                            <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                                class="kn-order-code">{{ $order->code }}</a>
                                        </td>
                                        <!-- Date -->
                                        <td class="text-muted">{{ date('d-m-Y', $order->date) }}</td>
                                        <!-- Amount -->
                                        <td class="fw-700">{{ single_price($order->grand_total) }}</td>
                                        <!-- Delivery Status -->
                                        <td>
                                            {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                            @if ($order->delivery_viewed == 0)
                                                <span class="kn-new-dot" title="{{ translate('New') }}"></span>
                                            @endif
                                        </td>
                                        <!-- Payment Status -->
                                        <td>
                                            @if ($order->payment_status == 'paid')
                                                <span class="kn-status is-success">{{ translate('Paid') }}</span>
                                            @else
                                                <span class="kn-status is-danger">{{ translate('Unpaid') }}</span>
                                            @endif
                                            @if ($order->payment_status_viewed == 0)
                                                <span class="kn-new-dot" title="{{ translate('New') }}"></span>
                                            @endif
                                        </td>
                                        <!-- Options -->
                                        <td class="kn-td-end">
                                            <div class="kn-actions">
                                                <!-- Re-order -->
                                                <a class="kn-text-btn" href="{{ route('re_order', encrypt($order->id)) }}">
                                                    {{ translate('Reorder') }}
                                                </a>
                                                <!-- Cancel -->
                                                @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                                    <a href="javascript:void(0)" class="kn-icon-btn is-danger confirm-delete"
                                                        data-href="{{ route('purchase_history.destroy', $order->id) }}"
                                                        title="{{ translate('Cancel') }}" aria-label="{{ translate('Cancel') }}">
                                                        <i class="las la-trash" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                                <!-- Details -->
                                                <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                                    class="kn-icon-btn" title="{{ translate('Order Details') }}"
                                                    aria-label="{{ translate('Order Details') }}">
                                                    <i class="las la-eye" aria-hidden="true"></i>
                                                </a>
                                                <!-- Invoice -->
                                                <a class="kn-icon-btn" href="{{ route('invoice.download', $order->id) }}"
                                                    title="{{ translate('Download Invoice') }}"
                                                    aria-label="{{ translate('Download Invoice') }}">
                                                    <i class="las la-download" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Phones: stacked cards -->
            <div class="kn-orders-cards">
                @foreach ($orders as $key => $order)
                    @if (count($order->orderDetails) > 0)
                        <article class="kn-order-card">
                            <div class="kn-order-card-top">
                                <div>
                                    <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                        class="kn-order-code">{{ $order->code }}</a>
                                    <span class="kn-order-date">{{ date('d-m-Y', $order->date) }}</span>
                                </div>
                                <span class="kn-order-total">{{ single_price($order->grand_total) }}</span>
                            </div>
                            <div class="kn-order-meta">
                                <span class="kn-status is-info">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                    @if ($order->delivery_viewed == 0)
                                        <span class="kn-new-dot" title="{{ translate('New') }}"></span>
                                    @endif
                                </span>
                                @if ($order->payment_status == 'paid')
                                    <span class="kn-status is-success">{{ translate('Paid') }}
                                        @if ($order->payment_status_viewed == 0)
                                            <span class="kn-new-dot" title="{{ translate('New') }}"></span>
                                        @endif
                                    </span>
                                @else
                                    <span class="kn-status is-danger">{{ translate('Unpaid') }}
                                        @if ($order->payment_status_viewed == 0)
                                            <span class="kn-new-dot" title="{{ translate('New') }}"></span>
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <div class="kn-actions">
                                <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                    class="kn-text-btn is-primary">{{ translate('Order Details') }}</a>
                                <a class="kn-text-btn" href="{{ route('re_order', encrypt($order->id)) }}">{{ translate('Reorder') }}</a>
                                <a class="kn-icon-btn" href="{{ route('invoice.download', $order->id) }}"
                                    title="{{ translate('Download Invoice') }}" aria-label="{{ translate('Download Invoice') }}">
                                    <i class="las la-download" aria-hidden="true"></i>
                                </a>
                                @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                    <a href="javascript:void(0)" class="kn-icon-btn is-danger confirm-delete"
                                        data-href="{{ route('purchase_history.destroy', $order->id) }}"
                                        title="{{ translate('Cancel') }}" aria-label="{{ translate('Cancel') }}">
                                        <i class="las la-trash" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        </section>
    @else
        <div class="kn-empty">
            <img src="{{ static_asset('assets/img/nothing.svg') }}" alt="">
            <p class="kn-empty-title">{{ translate("There isn't anything added yet") }}</p>
            <a href="{{ route('home') }}" class="kn-btn kn-btn-primary">{{ translate('Continue Shopping') }}</a>
        </div>
    @endif

    <!-- Pagination -->
    <div class="aiz-pagination">
        {{ $orders->links() }}
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

@endsection
