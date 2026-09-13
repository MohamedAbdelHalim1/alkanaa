<div class="aiz-user-sidenav-wrap position-relative z-1">
    <div class="aiz-user-sidenav">
        <!-- Close button (kept for the AIZ class-toggle mobile drawer; hidden in this layout) -->
        <div class="d-xl-none kn-acc-close">
            <button class="btn btn-sm p-2" type="button" data-toggle="class-toggle" data-backdrop="static"
                data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"
                aria-label="{{ translate('Close') }}">
                <i class="las la-times la-2x" aria-hidden="true"></i>
            </button>
        </div>
        @php
            $user = auth()->user();
            $user_avatar = null;
            $carts = [];
            if ($user && $user->avatar_original != null) {
                $user_avatar = uploaded_asset($user->avatar_original);
            }
        @endphp
        <!-- Customer info -->
        <div class="kn-acc-user">
            <span class="kn-acc-avatar">
                @if ($user->avatar_original != null)
                    <img src="{{ $user_avatar }}" alt=""
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @else
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt=""
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @endif
            </span>
            <div class="kn-acc-user-text">
                <p class="kn-acc-name">{{ $user->name }}</p>
                @if ($user->phone != null)
                    <div class="kn-acc-contact"><bdi>{{ $user->phone }}</bdi></div>
                @else
                    <div class="kn-acc-contact"><bdi>{{ $user->email }}</bdi></div>
                @endif
            </div>
        </div>

        <!-- Menus -->
        <nav class="sidemnenu" aria-label="{{ translate('My Account') }}">
            <ul class="aiz-side-nav-list kn-acc-links" data-toggle="aiz-side-menu">

                <!-- Dashboard -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['dashboard']) }}"
                        @if (areActiveRoutes(['dashboard'])) aria-current="page" @endif>
                        <i class="las la-home" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                @php
                    $delivery_viewed = get_count_by_delivery_viewed();
                    $payment_status_viewed = get_count_by_payment_status_viewed();
                @endphp

                <!-- Purchase History -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('purchase_history.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['purchase_history.index', 'purchase_history.details']) }}"
                        @if (areActiveRoutes(['purchase_history.index', 'purchase_history.details'])) aria-current="page" @endif>
                        <i class="las la-file-invoice" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
                        @if ($delivery_viewed > 0 || $payment_status_viewed > 0)
                            <span class="badge badge-inline badge-success">{{ translate('New') }}</span>
                        @endif
                    </a>
                </li>

                <!-- Preorder -->
                {{-- @if (addon_is_activated('preorder'))
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-clock" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Preorder') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('preorder.order_list') }}" class="aiz-side-nav-link {{ areActiveRoutes(['preorder.order_list', 'purchase_history.details']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Preorder List') }}</span>
                                </a>
                            </li>
                            @if (get_setting('conversation_system') == 1)
                                @php
                                    $preorderConversation = get_non_viewed_preorder_conversations();
                                @endphp
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('preorder-conversations.customer-index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['preorder-conversations.customer-show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Preorder Conversations') }}</span>
                                        @if ($preorderConversation > 0)
                                            <span class="badge badge-danger">({{ $preorderConversation }})</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                <!-- Downloads -->
                {{-- <li class="aiz-side-nav-item">
                    <a href="{{ route('digital_purchase_history.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['digital_purchase_history.index']) }}">
                        <i class="las la-download" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Downloads') }}</span>
                    </a>
                </li> --}}

                <!-- Refund Requests -->
                {{-- @if (addon_is_activated('refund_request'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customer_refund_request') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['customer_refund_request']) }}">
                            <i class="las la-undo" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Refund Requests') }}</span>
                        </a>
                    </li>
                @endif --}}

                <!-- Wishlist -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('wishlists.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['wishlists.index']) }}"
                        @if (areActiveRoutes(['wishlists.index'])) aria-current="page" @endif>
                        <i class="lar la-heart" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Wishlist') }}</span>
                    </a>
                </li>

                {{-- Disabled menu items (kept for re-enabling): Compare, Followed Sellers, Classified Products,
                     Auction, Conversations, My Wallet, Earning Points, Affiliate, Support Ticket.
                <li class="aiz-side-nav-item">
                    <a href="{{ route('compare') }}" class="aiz-side-nav-link {{ areActiveRoutes(['compare']) }}">
                        <i class="las la-sync" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Compare') }}</span>
                    </a>
                </li>

                @if (get_setting('vendor_system_activation') == 1)
                <li class="aiz-side-nav-item">
                    <a href="{{ route('followed_seller') }}" class="aiz-side-nav-link {{ areActiveRoutes(['followed_seller']) }}">
                        <i class="las la-store" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Followed Sellers') }}</span>
                    </a>
                </li>
                @endif

                @if (get_setting('classified_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customer_products.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['customer_products.index', 'customer_products.create', 'customer_products.edit']) }}">
                            <i class="las la-tags" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Classified Products') }}</span>
                        </a>
                    </li>
                @endif

                @if (addon_is_activated('auction'))
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-gavel" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Auction') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_product_bids.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Bidded Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_product.purchase_history') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (get_setting('conversation_system') == 1)
                    @php
                        $conversation = get_non_viewed_conversations();
                    @endphp
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('conversations.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['conversations.index', 'conversations.show']) }}">
                            <i class="las la-comments" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Conversations') }}</span>
                            @if (count($conversation) > 0)
                                <span class="badge badge-success">({{ count($conversation) }})</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (get_setting('wallet_system') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('wallet.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['wallet.index']) }}">
                            <i class="las la-wallet" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('My Wallet') }}</span>
                        </a>
                    </li>
                @endif

                @if (addon_is_activated('club_point'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('earnng_point_for_user') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['earnng_point_for_user']) }}">
                            <i class="las la-gem" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Earning Points') }}</span>
                        </a>
                    </li>
                @endif

                @if (addon_is_activated('affiliate_system') &&
                    Auth::user()->affiliate_user != null &&
                    Auth::user()->affiliate_user->status)
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-link" aria-hidden="true"></i>
                            <span class="aiz-side-nav-text">{{ translate('Affiliate') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('affiliate.user.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.user.index','affiliate.payment_settings']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Affiliate System') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('affiliate.user.payment_history') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Payment History') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Withdraw request history') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @php
                    $support_ticket = DB::table('tickets')
                        ->where('client_viewed', 0)
                        ->where('user_id', Auth::user()->id)
                        ->count();
                @endphp

                <li class="aiz-side-nav-item">
                    <a href="{{ route('support_ticket.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.index', 'support_ticket.show']) }}">
                        <i class="las la-life-ring" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Support Ticket') }}</span>
                        @if ($support_ticket > 0)
                            <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
                        @endif
                    </a>
                </li> --}}

                <!-- Manage Profile -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile') }}" class="aiz-side-nav-link {{ areActiveRoutes(['profile']) }}"
                        @if (areActiveRoutes(['profile'])) aria-current="page" @endif>
                        <i class="las la-user" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Manage Profile') }}</span>
                    </a>
                </li>

                <!-- Delete My Account -->
                {{-- <li class="aiz-side-nav-item">
                    <a href="javascript:void(0)" onclick="account_delete_confirm_modal('{{ route('account_delete') }}')" class="aiz-side-nav-link">
                        <i class="las la-user-minus" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Delete My Account') }}</span>
                    </a>
                </li> --}}

                <!-- logout -->
                <li class="aiz-side-nav-item kn-acc-signout-item">
                    <a href="{{ route('logout') }}" class="aiz-side-nav-link kn-acc-signout">
                        <i class="las la-sign-out-alt" aria-hidden="true"></i>
                        <span class="aiz-side-nav-text">{{ translate('Sign Out') }}</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</div>
<script>
    /* Mobile tab row: bring the active tab into view (works in RTL and LTR). */
    (function () {
        if (window.matchMedia && window.matchMedia('(min-width: 1200px)').matches) return;
        var row = document.querySelector('.kn-acc-links');
        var active = row ? row.querySelector('.aiz-side-nav-link.active') : null;
        if (!row || !active) return;
        var item = active.parentElement;
        var rowRect = row.getBoundingClientRect();
        var itemRect = item.getBoundingClientRect();
        if (itemRect.left < rowRect.left || itemRect.right > rowRect.right) {
            row.scrollLeft += (itemRect.left + itemRect.width / 2) - (rowRect.left + rowRect.width / 2);
        }
    })();
</script>
