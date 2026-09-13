@extends('frontend.layouts.user_panel')

@section('panel_content')

<div class="kn-page-head">
    <h1 class="kn-page-title">{{ translate('Notifications') }}</h1>
    <button type="button" onclick="bulk_notification_delete()" class="kn-btn kn-btn-outline">
        <i class="las la-trash" aria-hidden="true"></i>
        <span>{{ translate('Delete Selection') }}</span>
    </button>
</div>

<section class="kn-panel">
    <div class="kn-panel-head">
        <label class="aiz-checkbox mb-0">
            <input type="checkbox" class="check-all">
            <span class="aiz-square-check"></span>{{ translate('Select All') }}
        </label>
    </div>
    <!-- Notifications -->
    <ul class="kn-list">
        @php
            $notificationShowDesign = get_setting('notification_show_type');
            if($notificationShowDesign != 'only_text'){
                $notifyImageDesign = '';
                if($notificationShowDesign == 'design_2'){
                    $notifyImageDesign = 'rounded-1';
                }
                elseif($notificationShowDesign == 'design_3'){
                    $notifyImageDesign = 'rounded-circle';
                }
            }
        @endphp
        @forelse($notifications as $notification)
            @php
                $showNotification = true;
                if (($notification->type == 'App\Notifications\PreorderNotification') && !addon_is_activated('preorder'))
                {
                    $showNotification = false;
                }
            @endphp
            @if($showNotification)
                @php
                    $notificationType = get_notification_type($notification->notification_type_id, 'id');
                    $notifyContent = $notificationType->getTranslation('default_text');
                @endphp
                <li class="kn-list-item">
                    <label class="aiz-checkbox">
                        <input type="checkbox" class="check-one" name='id[]' value="{{$notification->id}}"
                            aria-label="{{ translate('Select') }}">
                        <span class="aiz-square-check"></span>
                    </label>
                    @if($notificationShowDesign != 'only_text')
                        <div class="size-35px flex-shrink-0">
                            <img
                                src="{{ uploaded_asset($notificationType->image) }}" alt=""
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/notification.png') }}';"
                                class="img-fit h-100 {{ $notifyImageDesign }}" >
                        </div>
                    @endif
                    <div class="kn-list-body">
                        <p class="kn-list-text mt-0 mb-1">
                            @if($notification->type == 'App\Notifications\OrderNotification')
                                @php
                                    $orderCode  = $notification->data['order_code'];
                                        $route = route('purchase_history.details', encrypt($notification->data['order_id']));
                                        $orderCode = "<a href='".$route."'>".$orderCode."</a>";
                                    $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                @endphp
                            @elseif($notification->type == 'App\Notifications\PreorderNotification')
                                @php
                                    $orderCode  = $notification->data['order_code'];
                                        $route = route('preorder.order_details', encrypt($notification->data['preorder_id']));
                                        $orderCode = "<a href='".$route."'>".$orderCode."</a>";
                                    $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                @endphp
                            @elseif($notification->type == 'App\Notifications\CustomNotification')
                                @php
                                    $link = $notification->data['link'];
                                    if($link != null){
                                        $notifyContent = "<a href='".$link."'>".$notifyContent."</a>";
                                    }
                                @endphp
                            @endif
                            {!! $notifyContent !!}
                        </p>
                        <span class="kn-list-meta">
                            {{ date("F j Y, g:i a", strtotime($notification->created_at)) }}
                        </span>
                    </div>
                </li>
            @endif
        @empty
            <li class="kn-list-item">
                <div class="kn-empty w-100">
                    <p class="kn-empty-title">{{ translate('No notification found') }}</p>
                </div>
            </li>
        @endforelse
    </ul>
    <!-- Pagination -->
    <div class="aiz-pagination">
        {{ $notifications->links() }}
    </div>
</section>

@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

    <!-- Rrder details modal -->
    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- Payment modal -->
    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            $('.check-one:checkbox').prop('checked', this.checked);
        });

        function bulk_notification_delete() {
            let notificationIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                notificationIds.push($(this).val());
            });
            $.post('{{ route('notifications.bulk_delete') }}', {_token:'{{ csrf_token() }}', notification_ids:notificationIds}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Notification Deleted successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
                location.reload();
            });
        }
    </script>
@endsection
