@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <div>
            <h1 class="kn-page-title">{{ translate('Conversations') }}</h1>
            <p class="kn-page-sub">{{ translate('Select a conversation to view all messages') }}</p>
        </div>
    </div>

    <!-- Conversations -->
    @if (count($conversations) > 0)
        <section class="kn-panel">
            <ul class="kn-list">
                @foreach ($conversations as $key => $conversation)
                    @if ($conversation->receiver != null && $conversation->sender != null)
                        <li class="kn-list-item">
                            <!-- Receiver/Shop Image -->
                            <span class="kn-list-avatar">
                                @if (Auth::user()->id == $conversation->sender_id)
                                    @if ($conversation->receiver->shop != null)
                                        <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" tabindex="-1" aria-hidden="true">
                                            <img src="{{ uploaded_asset($conversation->receiver->shop->logo) }}" alt=""
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                        </a>
                                    @else
                                        <img @if ($conversation->receiver->avatar_original == null) src="{{ static_asset('assets/img/avatar-place.png') }}"
                                            @else src="{{ uploaded_asset($conversation->receiver->avatar_original) }}" @endif alt=""
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    @endif
                                @else
                                    <img @if ($conversation->sender->avatar_original == null) src="{{ static_asset('assets/img/avatar-place.png') }}" @else src="{{ uploaded_asset($conversation->sender->avatar_original) }}" @endif alt="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>

                            <div class="kn-list-body">
                                <!-- Receiver/Shop Name & Time -->
                                <div class="kn-list-meta">
                                    @if (Auth::user()->id == $conversation->sender_id)
                                        @if ($conversation->receiver->shop != null)
                                            <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="fw-700">{{ $conversation->receiver->shop->name }}</a>
                                        @else
                                            <span class="fw-700">{{ $conversation->receiver->name }}</span>
                                        @endif
                                    @else
                                        <span class="fw-700">{{ $conversation->sender->name }}</span>
                                    @endif
                                    · {{ date('d.m.Y h:i:m', strtotime($conversation->messages->last()->created_at)) }}
                                </div>
                                <!-- Title -->
                                <a href="{{ route('conversations.show', encrypt($conversation->id)) }}" class="kn-list-title">
                                    {{ $conversation->title }}
                                </a>
                                @if ((Auth::user()->id == $conversation->sender_id && $conversation->sender_viewed == 0) || (Auth::user()->id == $conversation->receiver_id && $conversation->receiver_viewed == 0))
                                    <span class="kn-status is-danger">{{ translate('New') }}</span>
                                @endif
                                <!-- Last Message -->
                                <p class="kn-list-text">
                                    {{ $conversation->messages->last()->message }}
                                </p>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </section>
    @else
        <div class="kn-empty">
            <img src="{{ static_asset('assets/img/nothing.svg') }}" alt="">
            <p class="kn-empty-title">{{ translate("There isn't anything added yet") }}</p>
        </div>
    @endif
    <!-- Pagination -->
    <div class="aiz-pagination">
      	{{ $conversations->links() }}
    </div>
@endsection
