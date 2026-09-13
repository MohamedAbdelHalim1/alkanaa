@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Download Your Products') }}</h1>
    </div>

    <section class="kn-panel">
        <div class="kn-panel-body is-flush">
            <div class="kn-table-scroll">
                <table class="table aiz-table">
                    <thead>
                        <tr>
                            <th>{{ translate('Product')}}</th>
                            <th class="kn-td-end" width="20%">{{ translate('Option')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = get_order_details($order_id->id);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('product', $order->product->slug) }}" class="d-flex align-items-center" style="gap: 10px;">
                                        <img class="lazyload img-fit size-60px rounded"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($order->product->thumbnail_img) }}"
                                            alt="{{  $order->product->getTranslation('name')  }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        <span>{{ $order->product->getTranslation('name') }}</span>
                                    </a>
                                </td>
                                <td class="kn-td-end">
                                    <a class="kn-icon-btn" href="{{route('digital-products.download', encrypt($order->product->id))}}" title="{{ translate('Download') }}" aria-label="{{ translate('Download') }}">
                                        <i class="las la-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="aiz-pagination">
                {{ $orders->links() }}
            </div>
        </div>
    </section>
@endsection
