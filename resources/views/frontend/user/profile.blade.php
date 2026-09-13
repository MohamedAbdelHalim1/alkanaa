@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Manage Profile') }}</h1>
    </div>

    <!-- Basic Info-->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('Basic Info') }}</h2>
        </div>
        <div class="kn-panel-body">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="kn-fields-2">
                    <!-- Name-->
                    <div class="kn-field">
                        <label for="profile-name">{{ translate('Your Name') }}</label>
                        <input type="text" id="profile-name" class="form-control" autocomplete="name"
                            placeholder="{{ translate('Your Name') }}" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <!-- Phone-->
                    <div class="kn-field">
                        <label for="profile-phone">{{ translate('Your Phone') }}</label>
                        <input type="text" id="profile-phone" class="form-control" autocomplete="tel"
                            placeholder="{{ translate('Your Phone') }}" name="phone" value="{{ Auth::user()->phone }}">
                    </div>
                </div>
                <!-- Photo-->
                <div class="kn-field">
                    <span class="kn-label">{{ translate('Photo') }}</span>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="photo" value="{{ Auth::user()->avatar_original }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
                <div class="kn-fields-2">
                    <!-- Password-->
                    <div class="kn-field">
                        <label for="profile-new-password">{{ translate('Your Password') }}</label>
                        <input type="password" id="profile-new-password" class="form-control" autocomplete="new-password"
                            placeholder="{{ translate('New Password') }}" name="new_password">
                    </div>
                    <!-- Confirm Password-->
                    <div class="kn-field">
                        <label for="profile-confirm-password">{{ translate('Confirm Password') }}</label>
                        <input type="password" id="profile-confirm-password" class="form-control"
                            autocomplete="new-password" placeholder="{{ translate('Confirm Password') }}"
                            name="confirm_password">
                    </div>
                </div>
                <!-- Submit Button-->
                <div class="kn-form-actions">
                    <button type="submit" class="kn-btn kn-btn-primary">{{ translate('Update Profile') }}</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Address -->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('Address') }}</h2>
        </div>
        <div class="kn-panel-body">
            @if (count(Auth::user()->addresses) > 0)
                <div class="kn-addresses mb-3">
                    @foreach (Auth::user()->addresses as $key => $address)
                        <div class="kn-address {{ $address->set_default ? 'is-default' : '' }}">
                            @if ($address->set_default)
                                <span class="kn-status is-info">{{ translate('Default') }}</span>
                            @endif
                            <ul class="kn-address-lines">
                                <li><span>{{ translate('Address') }}:</span> {{ $address->address }}</li>
                                <li><span>{{ translate('Postal Code') }}:</span> {{ $address->postal_code }}</li>
                                <li><span>{{ translate('City') }}:</span> {{ optional($address->city)->name }}</li>
                                <li><span>{{ translate('State') }}:</span> {{ optional($address->state)->name }}</li>
                                <li><span>{{ translate('Country') }}:</span> {{ optional($address->country)->name }}</li>
                                <li><span>{{ translate('Phone') }}:</span> <bdi>{{ $address->phone }}</bdi></li>
                            </ul>
                            <div class="dropdown">
                                <button class="kn-icon-btn" type="button" data-toggle="dropdown"
                                    aria-label="{{ translate('Options') }}" aria-haspopup="true" aria-expanded="false">
                                    <i class="la la-ellipsis-v" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="edit_address('{{ $address->id }}')">
                                        {{ translate('Edit') }}
                                    </a>
                                    @if (!$address->set_default)
                                        <a class="dropdown-item" href="{{ route('addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <!-- Add New Address -->
            <button type="button" class="kn-add-tile" onclick="add_new_address()">
                <i class="la la-plus" aria-hidden="true"></i>
                <span>{{ translate('Add New Address') }}</span>
            </button>
        </div>
    </section>

    <!-- Change Email -->
    <form action="{{ route('user.change.email') }}" method="POST">
        @csrf
        <section class="kn-panel">
            <div class="kn-panel-head">
                <h2 class="kn-panel-title">{{ translate('Change your email') }}</h2>
            </div>
            <div class="kn-panel-body">
                <div class="kn-field">
                    <label for="profile-email">{{ translate('Your Email') }}</label>
                    <div class="input-group">
                        <input type="email" id="profile-email" class="form-control" autocomplete="email"
                            placeholder="{{ translate('Your Email') }}" name="email" value="{{ Auth::user()->email }}" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary new-email-verification">
                                <span class="d-none loading">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ translate('Sending Email...') }}
                                </span>
                                <span class="default">{{ translate('Verify') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="kn-form-actions">
                    <button type="submit" class="kn-btn kn-btn-primary">{{ translate('Update Email') }}</button>
                </div>
            </div>
        </section>
    </form>

@endsection

@section('modal')
    <!-- Address modal -->
    @include('frontend.partials.address.address_modal')
@endsection

@section('script')
    @include('frontend.partials.address.address_js')

    <script type="text/javascript">
        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if(data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if(data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif

@endsection
