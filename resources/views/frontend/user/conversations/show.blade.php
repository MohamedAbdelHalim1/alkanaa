@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <div>
            <a href="{{ route('conversations.index') }}" class="kn-auth-back" style="margin-top:0;">
                <i class="las la-arrow-left" aria-hidden="true"></i> {{ translate('Conversations') }}
            </a>
            <h1 class="kn-page-title">{{ $conversation->title }}</h1>
            <p class="kn-page-sub">
                <span>{{ translate('Conversations With ') }}</span>
                @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
                    <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="fw-700">{{ $conversation->receiver->shop->name }}</a>
                @endif
            </p>
        </div>
    </div>

    <section class="kn-panel">
        <div class="kn-panel-head">
            <!-- Conversation With -->
            <p class="kn-list-meta mb-0">
                <i class="las la-comments" aria-hidden="true"></i>
                {{ translate('Between you and') }}
                @if ($conversation->sender_id == Auth::user()->id)
                    {{ $conversation->receiver->shop ? $conversation->receiver->shop->name : $conversation->receiver->name }}
                @else
                    {{ $conversation->sender->name }}
                @endif
            </p>
        </div>

        <div class="kn-panel-body">
            <!-- Conversations -->
            <div id="messages">
                @include('frontend.partials.messages', ['conversation', $conversation])
            </div>

            <!-- Send message -->
            <form class="pt-3" action="{{ route('messages.store') }}" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <div class="kn-field">
                    <label for="conversation-message" class="kn-sr-only">{{ translate('Type your reply') }}</label>
                    <textarea class="form-control" id="conversation-message" rows="4" name="message" placeholder="{{ translate('Type your reply') }}" required></textarea>
                </div>
                <div class="kn-form-actions">
                    <button type="submit" class="kn-btn kn-btn-primary">{{ translate('Send') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
    function refresh_messages(){
        $.post('{{ route('conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages').html(data);
        })
    }

    refresh_messages(); // This will run on page load
    setInterval(function(){
        refresh_messages() // this will run after every 4 seconds
    }, 5000);
    </script>
@endsection
