@extends('frontend.layouts.app')
@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $customer_product->meta_title }}">
    <meta itemprop="description" content="{{ $customer_product->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($customer_product->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $customer_product->meta_title }}">
    <meta name="twitter:description" content="{{ $customer_product->meta_description }}">
    <meta name="twitter:creator"
        content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($customer_product->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($customer_product->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $customer_product->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $customer_product->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($customer_product->meta_img) }}" />
    <meta property="og:description" content="{{ $customer_product->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($customer_product->unit_price) }}" />
@endsection

@section('content')
    <div class="kn-pd-legacy">
    <section class="kn-pd-main">
        <div class="kn-wrap">
            <div class="kn-pd-panel-box">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 mb-4">
                        <div class="sticky-top z-3 row gutters-10">
                            @if ($customer_product->photos != null)
                                @php
                                    $photos = explode(',',$customer_product->photos);
                                @endphp
                                <!-- Gallery Images -->
                                <div class="col-12">
                                    <div class="aiz-carousel product-gallery arrow-lg-none" data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'  data-arrows='true'>
                                        @foreach ($photos as $key => $photo)
                                        <div class="carousel-box img-zoom rounded-0">
                                            <img class="img-fluid h-auto lazyload mx-auto"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Thumbnail Images -->
                                <div class="col-12 mt-3 d-none d-lg-block">
                                    <div class="aiz-carousel product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false' data-auto-height='true'>
                                        @foreach ($photos as $key => $photo)
                                        <div class="carousel-box c-pointer rounded-0">
                                            <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        <div class="text-left">
                            <!-- Product Name -->
                            <h1 class="kn-pd-title mb-3">
                                {{ $customer_product->getTranslation('name') }}
                            </h1>

                            <!-- Price -->
                            <div class="kn-pd-price">
                                <div class="kn-pd-price-row">
                                    <strong class="kn-pd-price-now">
                                        {{ single_price($customer_product->unit_price) }}
                                    </strong>
                                    @if ($customer_product->unit != null || $customer_product->unit != '')
                                        <span class="kn-pd-vat">/{{ $customer_product->getTranslation('unit') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <ul class="kn-pd-contact">
                                <li>
                                    <i class="la la-user" aria-hidden="true"></i>
                                    <span>{{ $customer_product->user->name }}</span>
                                </li>
                                <li>
                                    <i class="la la-map-marker" aria-hidden="true"></i>
                                    <span>{{ $customer_product->location }}</span>
                                </li>
                                <li>
                                    <button type="button" onclick="show_number(this)">
                                        <i class="la la-phone" aria-hidden="true"></i>
                                        <span>
                                            <bdi class="dummy">{{ str_replace(substr($customer_product->user->phone, 3), 'XXXXXXXX', $customer_product->user->phone) }}</bdi>
                                            <bdi class="real d-none">{{ $customer_product->user->phone }}</bdi>
                                            <small>{{ translate('Click to show phone number') }}</small>
                                        </span>
                                    </button>
                                </li>
                            </ul>

                            <!-- Share -->
                            <div class="row no-gutters mt-5">
                                <div class="col-sm-2">
                                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Share') }}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Description, Video & Downloads -->
    <section class="kn-section">
        <div class="kn-wrap">
            <div class="kn-pd-panel-box">
                <!-- Tabs -->
                <div class="nav aiz-nav-tabs">
                    <a href="#tab_default_1" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset active show">{{ translate('Description') }}</a>
                    @if ($customer_product->video_link != null)
                        <a href="#tab_default_2" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset">{{ translate('Video') }}</a>
                    @endif
                    @if ($customer_product->pdf != null)
                        <a href="#tab_default_3" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset">{{ translate('Downloads') }}</a>
                    @endif
                </div>

                <div class="tab-content pt-0">
                    <!-- Description -->
                    <div class="tab-pane active show" id="tab_default_1">
                        <div class="p-4">
                            <div class="mw-100 overflow-hidden text-left kn-pd-prose">
                                <?php echo $customer_product->getTranslation('description'); ?>
                            </div>
                        </div>
                    </div>
                    <!-- Video -->
                    <div class="tab-pane" id="tab_default_2">
                        <div class="p-4">
                            <div class="embed-responsive embed-responsive-16by9 mb-5">
                                @if ($customer_product->video_provider == 'youtube' && isset(explode('=', $customer_product->video_link)[1]))
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $customer_product->video_link)[1] }}"></iframe>
                                @elseif ($customer_product->video_provider == 'dailymotion' && isset(explode('video/', $customer_product->video_link)[1]))
                                    <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $customer_product->video_link)[1] }}"></iframe>
                                @elseif ($customer_product->video_provider == 'vimeo' && isset(explode('vimeo.com/', $customer_product->video_link)[1]))
                                    <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $customer_product->video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Download -->
                    <div class="tab-pane" id="tab_default_3">
                        <div class="p-4 text-center ">
                            <a href="{{ uploaded_asset($customer_product->pdf) }}" class="btn btn-primary">{{ translate('Download') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Other products -->
    <section class="kn-section">
        <div class="kn-wrap">
           <div>
                <div class="kn-section-head">
                    <h2 class="kn-section-title">
                        {{ translate('Other Ads of') }} {{ $customer_product->category->getTranslation('name') }}
                    </h2>
                    <a class="kn-link" href="{{ route('customer_products.category', $customer_product->category->slug) }}">{{ translate('View More') }}</a>
                </div>
                <div class="p-3">
                    <div class="aiz-carousel gutters-16 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                        @php
                            $products = get_similiar_classified_products($customer_product->category_id, $customer_product->id, 10);
                        @endphp
                        @foreach ($products as $key => $product)
                            <div class="carousel-box overflow-hidden has-transition hov-shadow-out z-1 border-right border-top border-bottom @if ($key == 0) border-left @endif">
                                <div class="aiz-card-box my-3">
                                    <div class="position-relative">
                                        <a href="{{ route('customer.product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </a>
                                        <div class="absolute-top-left">
                                            @if ($product->conditon == 'new')
                                                <span class="badge badge-inline badge-info fs-13 fw-700 p-3 text-white" style="border-radius: 20px;">{{ translate('New') }}</span>
                                            @elseif($product->conditon == 'used')
                                                <span class="badge badge-inline badge-secondary-base fs-13 fw-700 p-3 text-white" style="border-radius: 20px;">{{ translate('Used') }}</span> @endif
                                        </div>
                                    </div>
                                    <div class="p-md-3 p-2 text-center">
                                        <h3 class="fw-400 fs-14 text-truncate-2 lh-1-4 mb-0 h-35px">
                                            <a href="{{ route('customer.product', $product->slug) }}"
                                                class="d-block text-reset hov-text-primary">{{ $product->getTranslation('name') }}</a>
                                        </h3>
                                        <div class="fs-15 mt-2">
                                            <span class="fw-700 text-primary">{{ single_price($product->unit_price) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_number(el) {
            $(el).find('.dummy').addClass('d-none');
            $(el).find('.real').removeClass('d-none').addClass('d-block');
        }
    </script>
@endsection
