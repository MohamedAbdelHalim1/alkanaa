@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('My Wallet') }}</h1>
        <button type="button" class="kn-btn kn-btn-primary" onclick="show_wallet_modal()">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Recharge Wallet') }}</span>
        </button>
    </div>

    <div class="kn-tiles">
        <!-- Wallet Balance -->
        <div class="kn-stat">
            <span class="kn-stat-label">{{ translate('Wallet Balance') }}</span>
            <span class="kn-stat-num">{{ single_price(Auth::user()->balance) }}</span>
        </div>

        <!-- Recharge Wallet -->
        <button type="button" class="kn-tile-action" onclick="show_wallet_modal()">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Recharge Wallet') }}</span>
        </button>

        <!-- Offline Recharge Wallet -->
        @if (addon_is_activated('offline_payment'))
            <button type="button" class="kn-tile-action" onclick="show_make_wallet_recharge_modal()">
                <i class="las la-university" aria-hidden="true"></i>
                <span>{{ translate('Offline Recharge Wallet') }}</span>
            </button>
        @endif
    </div>

    <!-- Wallet Recharge History -->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('Wallet recharge history') }}</h2>
        </div>
        <div class="kn-panel-body is-flush">
            <div class="kn-table-scroll">
                <table class="table aiz-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Payment Method') }}</th>
                            <th class="kn-td-end">{{ translate('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $key => $wallet)
                            <tr>
                                <td>{{ sprintf('%02d', ($key+1)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                                <td class="fw-700">{{ single_price($wallet->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $wallet->payment_method)) }}</td>
                                <td class="kn-td-end">
                                    @if ($wallet->offline_payment)
                                        @if ($wallet->approval)
                                            <span class="kn-status is-success">{{ translate('Approved') }}</span>
                                        @else
                                            <span class="kn-status is-info">{{ translate('Pending') }}</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $wallets->links() }}
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.partials.wallet_modal')

    <!-- Offline Wallet Recharge Modal -->
    <div class="modal fade" id="offline_wallet_recharge_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Recharge Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_wallet_recharge_modal_body"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }

        function show_make_wallet_recharge_modal() {
            $.post('{{ route('offline_wallet_recharge_modal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#offline_wallet_recharge_modal_body').html(data);
                $('#offline_wallet_recharge_modal').modal('show');
            });
        }
    </script>
@endsection
