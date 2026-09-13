@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Classified Products') }}</h1>
        <!-- Add New Product -->
        <a href="{{ route('customer_products.create') }}" class="kn-btn kn-btn-primary">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Add New Product') }}</span>
        </a>
    </div>

    @php $authUser = auth()->user(); @endphp
    @php
        $customer_package = get_single_customer_package($authUser->customer_package_id);
    @endphp
    <div class="kn-tiles">
        <!-- Remaining Uploads -->
        <div class="kn-stat">
            <span class="kn-stat-label">{{ translate('Remaining Uploads') }}</span>
            <span class="kn-stat-num">{{ max(0, $authUser->remaining_uploads) }}</span>
        </div>

        <!-- Current Package -->
        <div class="kn-stat">
            @if ($customer_package != null)
                <span class="kn-stat-label">{{ translate('Current Package') }}</span>
                <span class="kn-stat-num" style="font-size: 1.125rem;">{{ $customer_package->getTranslation('name') }}</span>
            @else
                <span class="kn-stat-label">{{ translate('No Package Found') }}</span>
            @endif
            <a href="{{ route('customer_packages_list_show') }}" class="kn-link mt-1">
                {{ translate('Upgrade Package') }}
            </a>
        </div>

        <a href="{{ route('customer_products.create') }}" class="kn-tile-action">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Add New Product') }}</span>
        </a>
    </div>

    <!-- All Products -->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('All Products') }}</h2>
        </div>
        <div class="kn-panel-body is-flush">
            <div class="kn-table-scroll">
                <table class="table aiz-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Price') }}</th>
                            <th>{{ translate('Available Status') }}</th>
                            <th>{{ translate('Admin Status') }}</th>
                            <th class="kn-td-end">{{ translate('Options') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                        <tr>
                            <td>{{ sprintf('%02d', $key+1) }}</td>
                            <td>
                                <a href="{{ route('customer.product', $product->slug) }}" class="text-reset hov-text-primary d-flex align-items-center" style="gap: 10px;">
                                    <img class="lazyload img-fit size-60px rounded"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{  $product->getTranslation('name')  }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <span>{{ $product->name }}</span>
                                </a>
                            </td>
                            <td class="fw-700">{{ single_price($product->unit_price) }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_status(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->status == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            </td>
                            <td>
                                @if ($product->published == '1')
                                    <span class="kn-status is-success">{{ translate('PUBLISHED') }}</span>
                                @else
                                    <span class="kn-status is-info">{{ translate('PENDING') }}</span>
                                @endif
                            </td>
                            <td class="kn-td-end">
                                <div class="kn-actions">
                                    <a class="kn-icon-btn" href="{{route('customer_products.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}" aria-label="{{ translate('Edit') }}">
                                        <i class="las la-pen" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="kn-icon-btn is-danger confirm-delete" data-href="{{route('customer_products.destroy', $product->id)}}" title="{{ translate('Delete') }}" aria-label="{{ translate('Delete') }}">
                                        <i class="las la-trash" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $products->links() }}
          	</div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('customer_products.update.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status has been updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
