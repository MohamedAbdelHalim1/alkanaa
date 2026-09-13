<div class="kn-co-fields">
    <!-- Name -->
    <div class="kn-co-field">
        <label for="kn-guest-name">{{ translate('Name')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <input type="text" id="kn-guest-name" class="form-control" placeholder="{{ translate('Your Name')}}" name="name" autocomplete="name" required>
    </div>

    <!-- Email -->
    <div class="kn-co-field">
        <label for="kn-guest-email">{{ translate('Email')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <input type="email" id="kn-guest-email" class="form-control" placeholder="{{ translate('Your Email')}}" name="email" value="" autocomplete="email" required>
    </div>

    <!-- Address -->
    <div class="kn-co-field is-wide">
        <label for="kn-guest-address">{{ translate('Address')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <textarea id="kn-guest-address" class="form-control" placeholder="{{ translate('Your Address')}}" rows="2" name="address" autocomplete="street-address" required></textarea>
    </div>

    <!-- Country -->
    <div class="kn-co-field">
        <label for="kn-guest-country">{{ translate('Country')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <select id="kn-guest-country" class="form-control aiz-selectpicker" @if (get_setting('shipping_type') == 'carrier_wise_shipping') onchange="updateDeliveryAddress(this.value)" @endif
            data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
            <option value="">{{ translate('Select your country') }}</option>
            @foreach (get_active_countries() as $key => $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- State -->
    <div class="kn-co-field">
        <label for="kn-guest-state">{{ translate('State')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <select id="kn-guest-state" class="form-control aiz-selectpicker" data-live-search="true" name="state_id" required>

        </select>
    </div>

    <!-- City -->
    <div class="kn-co-field">
        <label for="kn-guest-city">{{ translate('City')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <select id="kn-guest-city" class="form-control aiz-selectpicker" data-live-search="true" name="city_id" required>

        </select>
    </div>

    <!-- Postal code -->
    <div class="kn-co-field">
        <label for="kn-guest-postal">{{ translate('Postal code')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <input type="text" id="kn-guest-postal" class="form-control" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" autocomplete="postal-code" required>
    </div>

    @if (get_setting('google_map') == 1)
        <!-- Google Map -->
        <div class="kn-co-field is-wide kn-co-map">
            <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
            <div id="map"></div>
            <ul id="geoData">
                <li style="display: none;">Full Address: <span id="location"></span></li>
                <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                <li style="display: none;">Country: <span id="country"></span></li>
                <li style="display: none;">Latitude: <span id="lat"></span></li>
                <li style="display: none;">Longitude: <span id="lon"></span></li>
            </ul>
        </div>
        <!-- Longitude -->
        <div class="kn-co-field">
            <label for="longitude">{{ translate('Longitude')}}</label>
            <input type="text" class="form-control" id="longitude" name="longitude" readonly="">
        </div>
        <!-- Latitude -->
        <div class="kn-co-field">
            <label for="latitude">{{ translate('Latitude')}}</label>
            <input type="text" class="form-control" id="latitude" name="latitude" readonly="">
        </div>
    @endif

    <!-- Phone -->
    <div class="kn-co-field">
        <label for="phone-code">{{ translate('Phone')}} <span class="kn-co-req" aria-hidden="true">*</span></label>
        <input type="tel" id="phone-code" class="form-control" placeholder="" name="phone" autocomplete="off" required>
        <input type="hidden" name="country_code" value="">
    </div>
</div>

<p class="kn-co-note">
    {{ translate('If you have already used the same mail address or phone number before, please ') }}
    <a href="javascript:void(0);" data-toggle="modal" data-target="#login_modal" class="fw-700">{{ translate('Login') }}</a>
    {{ translate(' first to continue') }}
</p>
