@extends('frontend.layouts.user_panel')

@section('panel_content')
    @php
        $isAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
        $t = fn($ar, $en) => $isAr ? $ar : $en;
    @endphp

    <div class="kn-page-head">
        <div>
            <h1 class="kn-page-title">{{ translate('Dashboard') }}</h1>
            <p class="kn-page-sub">{{ $t('مرحباً', 'Welcome') }}, {{ Auth::user()->name }}</p>
        </div>
        <a href="{{ route('purchase_history.index') }}" class="kn-btn kn-btn-primary">
            <i class="las la-file-invoice" aria-hidden="true"></i>
            <span>{{ translate('View Order History') }}</span>
        </a>
    </div>

    @php
        $welcomeCoupon = ifUserHasWelcomeCouponAndNotUsed();
    @endphp
    @if ($welcomeCoupon)
        <div class="kn-coupon">
            @php
                $discount =
                    $welcomeCoupon->discount_type == 'amount'
                        ? single_price($welcomeCoupon->discount)
                        : $welcomeCoupon->discount . '%';
            @endphp
            <div>
                {{ translate('Welcome Coupon') }} <strong>{{ $discount }}</strong>
                {{ translate('Discount on your Purchase Within') }} <strong>{{ $welcomeCoupon->validation_days }}</strong>
                {{ translate('days of Registration') }}
            </div>
            <button type="button" class="kn-btn kn-btn-primary"
                onclick="copyCouponCode('{{ $welcomeCoupon->coupon_code }}')">{{ translate('Copy coupon Code') }}</button>
        </div>
    @endif

    {{-- <div class="row gutters-16">
        <!-- Wallet summary -->
        @if (get_setting('wallet_system') == 1)
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="h-100" style="background-image: url('{{ static_asset("assets/img/wallet-bg.png") }}'); background-size: cover; background-position: center center;">
                <div class="p-4 h-100 w-100 w-xl-50">
                    <p class="fs-14 fw-400 text-gray mb-3">{{ translate('Wallet Balance') }}</p>
                    <h1 class="fs-30 fw-700 text-white ">{{ single_price(Auth::user()->balance) }}</h1>
                    <hr class="border border-dashed border-white opacity-40 ml-0 mt-4 mb-4">
                    @php
                        $last_recharge = get_user_last_wallet_recharge();
                    @endphp
                    <p class="fs-14 fw-400 text-gray mb-1">{{ translate('Last Recharge') }} <strong>{{ $last_recharge ? date('d.m.Y', strtotime($last_recharge->created_at)) : '' }}</strong></p>
                    <h3 class="fs-20 fw-700 text-white ">{{ $last_recharge ? single_price($last_recharge->amount) : 0 }}</h3>
                    <button class="btn btn-block border border-soft-light hov-bg-dark text-white mt-5 py-3" onclick="show_wallet_modal()" style="border-radius: 30px; background: rgba(255, 255, 255, 0.1);">
                        <i class="la la-plus fs-18 fw-700 mr-2"></i>
                        {{ translate('Recharge Wallet') }}
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="col mb-4">
            <div class="h-100">
                <div class="row h-100 @if (get_setting('wallet_system') != 1 && addon_is_activated('club_point')) row-cols-md-2 @endif row-cols-1">
                    <!-- Expenditure summary -->
                    <div class="col">
                        <div class="p-4 bg-primary @if (!addon_is_activated('club_point')) h-100 @endif" style="margin-bottom: 2rem;">
                            <div class="ml-3 d-flex flex-column justify-content-between">
                                <span class="fs-14 fw-400 text-white mb-1">{{ translate('Total Expenditure') }}</span>
                                <span class="fs-20 fw-700 text-white">{{ single_price(get_user_total_expenditure()) }}</span>
                            </div>
                            <a href="{{ route('purchase_history.index') }}" class="fs-12 text-white">
                                {{ translate('View Order History') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Club Point summary -->
                    @if (addon_is_activated('club_point'))
                    <div class="col">
                        <div class="p-4 bg-secondary-base">
                            <div class="ml-3 d-flex flex-column justify-content-between">
                                <span class="fs-14 fw-400 text-white mb-1">{{ translate('Total Club Points') }}</span>
                                <span class="fs-20 fw-700 text-white">{{ get_user_total_club_point() }}</span>
                            </div>
                            <a href="{{ route('earnng_point_for_user') }}" class="fs-12 text-white">
                                {{ translate('Convert Club Points') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div> --}}

    <!-- Count summary -->
    @php
        $cart = get_user_cart();
        $total = get_user_total_ordered_products();
    @endphp
    <div class="kn-stats">
        <a href="{{ route('cart') }}" class="kn-stat">
            <span class="kn-stat-num">{{ count($cart) > 0 ? sprintf('%02d', count($cart)) : 0 }}</span>
            <span class="kn-stat-label">{{ translate('Products in Cart') }}</span>
        </a>
        <a href="{{ route('wishlists.index') }}" class="kn-stat">
            <span class="kn-stat-num">{{ count(Auth::user()->wishlists) > 0 ? sprintf('%02d', count(Auth::user()->wishlists)) : 0 }}</span>
            <span class="kn-stat-label">{{ translate('Products in Wishlist') }}</span>
        </a>
        <a href="{{ route('purchase_history.index') }}" class="kn-stat">
            <span class="kn-stat-num">{{ $total > 0 ? sprintf('%02d', $total) : 0 }}</span>
            <span class="kn-stat-label">{{ translate('Total Products Ordered') }}</span>
        </a>
    </div>

    @php
        $company = App\Models\Company::where('user_id', Auth::user()->id)->first();
    @endphp

    <div class="kn-dash-cards">
        <!-- Purchased Package -->
        @if (get_setting('classified_product'))
            <section class="kn-panel">
                <div class="kn-panel-head">
                    <h2 class="kn-panel-title">{{ translate('Purchased Package') }}</h2>
                </div>
                <div class="kn-panel-body">
                    @php
                        $customer_package = get_single_customer_package(Auth::user()->customer_package_id);
                    @endphp
                    @if ($customer_package != null)
                        <img src="{{ uploaded_asset($customer_package->logo) }}" class="img-fluid mb-3 h-70px" alt=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        <dl class="kn-kv mb-3">
                            <div>
                                <dt>{{ translate('Current Package') }}</dt>
                                <dd>{{ $customer_package->getTranslation('name') }}</dd>
                            </div>
                            <div>
                                <dt>{{ translate('Product Upload') }}</dt>
                                <dd>{{ $customer_package->product_upload }} {{ translate('Times') }}</dd>
                            </div>
                            <div>
                                <dt>{{ translate('Product Upload Remains') }}</dt>
                                <dd>{{ Auth::user()->remaining_uploads }} {{ translate('Times') }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="kn-muted-note">{{ translate('Package Not Found') }}</p>
                    @endif
                    <a href="{{ route('customer_packages_list_show') }}"
                        class="kn-btn kn-btn-primary kn-btn-block">{{ translate('Upgrade Package') }}</a>
                </div>
            </section>
        @endif

        <!-- Default Shipping Address -->
        <section class="kn-panel">
            <div class="kn-panel-head">
                <h2 class="kn-panel-title">{{ translate('Default Shipping Address') }}</h2>
            </div>
            <div class="kn-panel-body">
                @php $address = null; @endphp
                @if (Auth::user()->addresses != null)
                    @php
                        $address = Auth::user()->addresses->where('set_default', 1)->first();
                    @endphp
                    @if ($address != null)
                        <ul class="kn-lines">
                            <li>{{ $address->address }},</li>
                            <li>{{ $address->postal_code }} - {{ optional($address->city)->name }},</li>
                            <li>{{ optional($address->state)->name }},</li>
                            <li>{{ optional($address->country)->name }}.</li>
                            <li><bdi>{{ $address->phone }}</bdi></li>
                        </ul>
                    @endif
                @endif
                @if ($address == null)
                    <p class="kn-muted-note">{{ $t('لم تضف عنوان شحن افتراضي بعد.', 'You have not added a default shipping address yet.') }}</p>
                @endif
                <button type="button" class="kn-btn kn-btn-outline kn-btn-block" onclick="add_new_address()">
                    <i class="las la-plus" aria-hidden="true"></i>
                    <span>{{ translate('Add New Address') }}</span>
                </button>
            </div>
        </section>

        @if (Auth::user()->is_company)
            <!-- Default Company Data -->
            <section class="kn-panel">
                <div class="kn-panel-head">
                    <h2 class="kn-panel-title">{{ translate('Company Data') }}</h2>
                </div>
                <div class="kn-panel-body">
                    @if ($company != null)
                        <dl class="kn-kv mb-3">
                            <div>
                                <dt>{{ translate('company_name') }}</dt>
                                <dd>{{ $company->company_name }}</dd>
                            </div>
                            <div>
                                <dt>{{ translate('tax_number') }}</dt>
                                <dd>{{ $company->tax_number }}</dd>
                            </div>
                            <div>
                                <dt>{{ translate('commercial_register') }}</dt>
                                <dd>{{ $company->commercial_register }}</dd>
                            </div>
                        </dl>
                    @endif
                    <button type="button" class="kn-btn kn-btn-outline kn-btn-block" onclick="edit_company()">
                        <i class="las la-pen" aria-hidden="true"></i>
                        <span>{{ translate('Edit Company') }}</span>
                    </button>
                </div>
            </section>
        @endif
    </div>

    <!-- Wishlist preview -->
    <div class="kn-section-head">
        <h2 class="kn-section-title">{{ translate('My Wishlist') }}</h2>
        <a class="kn-link" href="{{ route('wishlists.index') }}">{{ translate('View All') }}</a>
    </div>
    @php
        $wishlists = get_user_wishlist();
    @endphp
    @if (count($wishlists) > 0)
        <div class="kn-products">
            @foreach ($wishlists->take(4) as $key => $wishlist)
                @if ($wishlist->product != null)
                    <div class="kn-wish-item" id="wishlist_{{ $wishlist->id }}">
                        <x-product-card :product="$wishlist->product" />
                        <button type="button" class="kn-wish-remove" onclick="removeFromWishlist({{ $wishlist->id }})"
                            aria-label="{{ translate('Remove from wishlist') }}" title="{{ translate('Remove from wishlist') }}">
                            <i class="las la-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="kn-empty">
            <img src="{{ static_asset('assets/img/nothing.svg') }}" alt="">
            <p class="kn-empty-title">{{ translate("There isn't anything added yet") }}</p>
        </div>
    @endif
@endsection

@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.partials.wallet_modal')
    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }
    </script>

    <!-- Address modal Modal -->
    @include('frontend.partials.address.address_modal')

    @if (app()->getLocale() == 'sa')
        <!-- Modal: edit company data (Arabic) -->
        <div class="modal fade kn-account-modal" id="edit-company-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('company.edit') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل بيانات الشركة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="إغلاق"></button>
                        </div>
                        <div class="modal-body">
                            <div class="kn-field">
                                <label class="form-label" for="company-name-sa">اسم الشركة</label>
                                <input type="text" id="company-name-sa" name="company_name"
                                    value="{{ $company?->company_name }}" class="form-control">
                            </div>
                            <div class="kn-field">
                                <label class="form-label" for="company-tax-sa">الرقم الضريبي</label>
                                <input type="text" id="company-tax-sa" name="tax_number"
                                    value="{{ $company?->tax_number }}" class="form-control">
                            </div>
                            <div class="kn-field mb-0">
                                <label class="form-label" for="company-cr-sa">السجل التجاري</label>
                                <input type="text" id="company-cr-sa" name="commercial_register"
                                    value="{{ $company?->commercial_register }}" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="kn-btn kn-btn-primary">حفظ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @elseif(app()->getLocale() == 'cn')
        <!-- Modal: 编辑公司信息 (Chinese) -->
        <div class="modal fade kn-account-modal" id="edit-company-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('company.edit') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">编辑公司信息</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                        </div>
                        <div class="modal-body">
                            <div class="kn-field">
                                <label class="form-label" for="company-name-cn">公司名称</label>
                                <input type="text" id="company-name-cn" name="company_name"
                                    value="{{ $company?->company_name }}" class="form-control">
                            </div>
                            <div class="kn-field">
                                <label class="form-label" for="company-tax-cn">税号</label>
                                <input type="text" id="company-tax-cn" name="tax_number"
                                    value="{{ $company?->tax_number }}" class="form-control">
                            </div>
                            <div class="kn-field mb-0">
                                <label class="form-label" for="company-cr-cn">商业登记号</label>
                                <input type="text" id="company-cr-cn" name="commercial_register"
                                    value="{{ $company?->commercial_register }}" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="kn-btn kn-btn-primary">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Modal: Edit Company Data (English) -->
        <div class="modal fade kn-account-modal" id="edit-company-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('company.edit') }}">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company?->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Company Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="kn-field">
                                <label class="form-label" for="company-name-en">Company Name</label>
                                <input type="text" id="company-name-en" name="company_name"
                                    value="{{ $company?->company_name }}" class="form-control">
                            </div>
                            <div class="kn-field">
                                <label class="form-label" for="company-tax-en">Tax Number</label>
                                <input type="text" id="company-tax-en" name="tax_number"
                                    value="{{ $company?->tax_number }}" class="form-control">
                            </div>
                            <div class="kn-field mb-0">
                                <label class="form-label" for="company-cr-en">Commercial Registration</label>
                                <input type="text" id="company-cr-en" name="commercial_register"
                                    value="{{ $company?->commercial_register }}" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="kn-btn kn-btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('script')
    @include('frontend.partials.address.address_js')

    <script type="text/javascript">
        function removeFromWishlist(id) {
            $.post('{{ route('wishlists.remove') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#wishlist').html(data);
                $('#wishlist_' + id).hide();
                AIZ.plugins.notify('success', '{{ translate('Item has been renoved from wishlist') }}');
            })
        }
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif


@endsection
