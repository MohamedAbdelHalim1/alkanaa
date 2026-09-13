@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <div>
            <a href="{{ route('support_ticket.index') }}" class="kn-auth-back" style="margin-top:0;">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Support Ticket') }}
            </a>
            <h1 class="kn-page-title">{{ $ticket->subject }} <bdi>#{{ $ticket->code }}</bdi></h1>
            <p class="kn-page-sub">
                <span class="fw-700">{{ $ticket->user->name }}</span>
                · {{ $ticket->created_at }}
                · <span class="kn-status">{{ translate(ucfirst($ticket->status)) }}</span>
            </p>
        </div>
    </div>

    <section class="kn-panel">
        <div class="kn-panel-body">
            <!-- Reply form -->
            <form action="{{route('support_ticket.seller_store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id" value="{{$ticket->id}}" required>
                <input type="hidden" name="user_id" value="{{$ticket->user_id}}">
                <div class="kn-field">
                    <textarea class="aiz-text-editor" name="reply" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' required></textarea>
                </div>
                <div class="kn-field">
                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="attachments" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
                <div class="kn-form-actions">
                    <button type="submit" class="kn-btn kn-btn-primary" onclick="submit_reply('pending')">{{ translate('Send Reply') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="kn-panel">
        <div class="kn-panel-body">
            <!-- Replies -->
            @foreach($ticket->ticketreplies as $ticketreply)
                <div class="kn-thread-msg">
                    <div class="kn-thread-head">
                        <span class="kn-list-avatar">
                            @if($ticketreply->user->avatar_original != null)
                                <img src="{{ uploaded_asset($ticketreply->user->avatar_original) }}" alt="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="">
                            @endif
                        </span>
                        <div>
                            <div class="kn-list-title">{{ $ticketreply->user->name }}</div>
                            <div class="kn-list-meta">{{ date('d.m.Y h:i:m', strtotime($ticketreply->created_at)) }}</div>
                        </div>
                    </div>
                    <div class="kn-thread-body">
                        {!! $ticketreply->reply !!}
                        <div class="kn-thread-files">
                            @foreach ((explode(",",$ticketreply->files)) as $key => $file)
                                @php $file_detail = get_single_uploaded_file($file) @endphp
                                @if($file_detail != null)
                                    <a href="{{ uploaded_asset($file) }}" download="" class="kn-file-chip">
                                        <i class="las la-download" aria-hidden="true"></i>
                                        <span>{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Ticket Details -->
            <div class="kn-thread-msg">
                <div class="kn-thread-head">
                    <span class="kn-list-avatar">
                        @if($ticket->user->avatar_original != null)
                            <img src="{{ uploaded_asset($ticket->user->avatar_original) }}" alt="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="">
                        @endif
                    </span>
                    <div>
                        <div class="kn-list-title">{{ $ticket->user->name }}</div>
                        <div class="kn-list-meta">{{ date('d.m.Y h:i:m', strtotime($ticket->created_at)) }}</div>
                    </div>
                </div>
                <div class="kn-thread-body">
                    {!! $ticket->details !!}
                    <div class="kn-thread-files">
                        @foreach ((explode(",",$ticket->files)) as $key => $file)
                            @php $file_detail = get_single_uploaded_file($file) @endphp
                            @if($file_detail != null)
                                <a href="{{ uploaded_asset($file) }}" download="" class="kn-file-chip">
                                    <i class="las la-download" aria-hidden="true"></i>
                                    <span>{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
