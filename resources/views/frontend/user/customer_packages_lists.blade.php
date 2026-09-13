@extends('frontend.layouts.app')

@section('content')
    <section class="kn-account">
        <div class="kn-wrap">
            <div class="kn-page-head">
                <h1 class="kn-page-title">{{ translate('Premium Packages for Customers') }}</h1>
            </div>

            <div class="kn-packages">
                @foreach ($customer_packages as $key => $customer_package)
                    <div class="kn-package">
                        <!-- Package name -->
                        <img src="{{ uploaded_asset($customer_package->logo) }}" alt="">
                        <h2 class="kn-package-name">{{ $customer_package->getTranslation('name') }}</h2>
                        <!-- No. of product upload -->
                        <div class="kn-package-feature">
                            <i class="las la-check text-success" aria-hidden="true"></i>
                            {{ $customer_package->product_upload }} {{ translate('Product Upload') }}
                        </div>
                        <!-- Amount -->
                        <div class="kn-package-price">
                            @if ($customer_package->amount == 0)
                                {{ translate('Free') }}
                            @else
                                {{ single_price($customer_package->amount) }}
                            @endif
                        </div>
                        <!-- Purchase Button -->
                        @if ($customer_package->amount == 0)
                            <button type="button" class="kn-btn kn-btn-primary"
                                onclick="get_free_package({{ $customer_package->id }})">{{ translate('Free Package') }}</button>
                        @else
                            @if (addon_is_activated('offline_payment'))
                                <button type="button" class="kn-btn kn-btn-primary"
                                    onclick="select_payment_type({{ $customer_package->id }})">{{ translate('Purchase Package') }}</button>
                            @else
                                <button type="button" class="kn-btn kn-btn-primary"
                                    onclick="show_price_modal({{ $customer_package->id }})">{{ translate('Purchase Package') }}</button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('modal')

    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="select_payment_type_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="package_id" name="package_id" value="">
                    <div class="kn-field">
                        <label>{{ translate('Payment Type') }}</label>
                        <select class="form-control aiz-selectpicker" onchange="payment_type(this.value)"
                            data-minimum-results-for-search="Infinity">
                            <option value="">{{ translate('Select One') }}</option>
                            <option value="online">{{ translate('Online payment') }}</option>
                            <option value="offline">{{ translate('Offline payment') }}</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-light transition-3d-hover mr-1"
                            id="select_type_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Online payment Modal -->
    <div class="modal fade" id="price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Purchase Your Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body gry-bg px-3 pt-3" style="overflow-y: inherit;">
                    <form class="" id="package_payment_form" action="{{ route('customer_packages.purchase') }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="customer_package_id" value="">
                        <div class="kn-field">
                            <label>{{ translate('Payment Method') }}</label>
                            <select class="form-control selectpicker" data-live-search="true" name="payment_option">
                                @includeIf('partials.online_payment_options')
                                @if (get_setting('wallet_system') == 1)
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
    <div class="modal fade" id="offline_customer_package_purchase_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Package Purchase ') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_customer_package_purchase_modal_body"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function select_payment_type(id) {
            $('input[name=package_id]').val(id);
            $('#select_payment_type_modal').modal('show');
        }

        function payment_type(type) {
            var package_id = $('#package_id').val();
            if (type == 'online') {
                $("#select_type_cancel").click();
                show_price_modal(package_id);
            } else if (type == 'offline') {
                $("#select_type_cancel").click();
                $.post('{{ route('offline_customer_package_purchase_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    package_id: package_id
                }, function(data) {
                    $('#offline_customer_package_purchase_modal_body').html(data);
                    $('#offline_customer_package_purchase_modal').modal('show');
                });
            }
        }

        function show_price_modal(id) {
            $('input[name=customer_package_id]').val(id);
            $('#price_modal').modal('show');
        }

        function get_free_package(id) {
            $('input[name=customer_package_id]').val(id);
            $('#package_payment_form').submit();
        }
    </script>
@endsection
