@php
    $photos = explode(',',$product->photos);
    $qty = 0;
    foreach ($product->stocks as $key => $stock) {
        $qty += $stock->qty;
    }
@endphp
<div class="modal-body kn-qv c-scrollbar-light">
    <div class="kn-qv-grid">
        <!-- Product Image gallery -->
        <div class="kn-qv-gallery">
            <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'>
                @foreach ($photos as $key => $photo)
                <div class="carousel-box img-zoom">
                    <img class="img-fluid lazyload"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($photo) }}"
                        alt="{{ $product->getTranslation('name') }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                @endforeach
                @foreach ($product->stocks as $key => $stock)
                    @if ($stock->image != null)
                        <div class="carousel-box img-zoom">
                            <img class="img-fluid lazyload"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($stock->image) }}"
                                alt="{{ $product->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="mt-2">
                <div class="aiz-carousel carousel-thumb product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-focus-select='true'>
                    @foreach ($photos as $key => $photo)
                    <div class="carousel-box c-pointer">
                        <img class="lazyload mw-100 size-60px mx-auto"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($photo) }}"
                            alt=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                    @endforeach
                    @foreach ($product->stocks as $key => $stock)
                        @if ($stock->image != null)
                            <div class="carousel-box c-pointer" data-variation="{{ $stock->variant }}">
                                <img class="lazyload mw-100 size-50px mx-auto"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($stock->image) }}"
                                    alt=""
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="kn-pd-buy">
            <!-- Product name -->
            <h2 class="kn-pd-title">
                {{  $product->getTranslation('name')  }}
            </h2>

            <!-- Product Price & Club Point -->
            <div class="kn-pd-price">
                <div class="kn-pd-price-row">
                    <span class="kn-pd-price-now">{{ home_discounted_price($product) }}</span>
                    @if(home_price($product) != home_discounted_price($product))
                        <del class="kn-pd-price-was">{{ home_price($product) }}</del>
                        @if(discount_in_percentage($product) > 0)
                            <span class="kn-pd-badge is-sale">-{{discount_in_percentage($product)}}%</span>
                        @endif
                    @endif
                    @if($product->unit != null)
                        <span class="kn-pd-vat">/{{ $product->getTranslation('unit') }}</span>
                    @endif
                </div>

                <!-- Club Point -->
                @if (addon_is_activated('club_point') && $product->earn_point > 0)
                    <p class="kn-pd-vat">{{  translate('Club Point') }}: {{ $product->earn_point }}</p>
                @endif
            </div>

            <!-- Product Choice options form -->
            <form id="option-choice-form">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">

                @if($product->digital !=1)
                    <!-- Product Choice options -->
                    @if ($product->choice_options != null)
                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="kn-qv-row">
                                <div class="kn-qv-label">{{ get_single_attribute_name($choice->attribute_id) }}</div>
                                <div class="aiz-radio-inline">
                                    @foreach ($choice->values as $key => $value)
                                    <label class="aiz-megabox pl-0 mr-2 mb-0">
                                        <input
                                            type="radio"
                                            name="attribute_id_{{ $choice->attribute_id }}"
                                            value="{{ $value }}"
                                            @if($key == 0) checked @endif
                                        >
                                        <span class="aiz-megabox-elem d-flex align-items-center justify-content-center py-1 px-3">
                                            {{ $value }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Color -->
                    @if ($product->colors && count(json_decode($product->colors)) > 0)
                        <div class="kn-qv-row">
                            <div class="kn-qv-label">{{ translate('Color')}}</div>
                            <div class="aiz-radio-inline">
                                @foreach (json_decode($product->colors) as $key => $color)
                                <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="{{ get_single_color_name($color) }}">
                                    <input
                                        type="radio"
                                        name="color"
                                        value="{{ get_single_color_name($color) }}"
                                        aria-label="{{ get_single_color_name($color) }}"
                                        @if($key == 0) checked @endif
                                    >
                                    <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1">
                                        <span class="size-25px d-inline-block rounded" style="background: {{ $color }};"></span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div class="kn-qv-row">
                        <div class="kn-qv-label">{{ translate('Quantity')}}</div>
                        <div class="product-quantity d-flex align-items-center">
                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                <button class="btn col-auto btn-icon btn-sm btn-light" type="button" data-type="minus" data-field="quantity" disabled="" aria-label="{{ translate('Decrease quantity') }}">
                                    <i class="las la-minus"></i>
                                </button>
                                <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $product->min_qty }}" min="{{ $product->min_qty }}" max="10" lang="en" aria-label="{{ translate('Quantity') }}">
                                <button class="btn col-auto btn-icon btn-sm btn-light" type="button" data-type="plus" data-field="quantity" aria-label="{{ translate('Increase quantity') }}">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                            <div class="avialable-amount kn-qv-label">
                                @if($product->stock_visibility_state == 'quantity')
                                (<span id="available-quantity">{{ $qty }}</span> {{ translate('available')}})
                                @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                    (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Quantity -->
                    <input type="hidden" name="quantity" value="1">
                @endif

                <!-- Total Price -->
                <div class="kn-qv-row d-none" id="chosen_price_div">
                    <div class="kn-qv-label">{{ translate('Total Price')}}</div>
                    <div class="product-price">
                        <strong id="chosen_price" class="fs-20 fw-700 text-primary">

                        </strong>
                    </div>
                </div>

            </form>

            <!-- Add to cart -->
            <div class="kn-pd-actions">
                @if ($product->digital == 1)
                    <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
                    >
                        <i class="la la-shopping-cart"></i>
                        <span>{{ translate('Add to cart')}}</span>
                    </button>
                @elseif($qty > 0)
                    @if ($product->external_link != null)
                        <a type="button" class="btn btn-soft-primary add-to-cart fw-600" href="{{ $product->external_link }}">
                            <i class="las la-share"></i>
                            <span>{{ translate($product->external_link_btn)}}</span>
                        </a>
                    @else
                        <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart"
                            @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
                        >
                            <i class="la la-shopping-cart"></i>
                            <span>{{ translate('Add to cart')}}</span>
                        </button>
                    @endif
                @endif
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i>{{ translate('Out of Stock')}}
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#option-choice-form input').on('change', function () {
        getVariantPrice();
    });
</script>
