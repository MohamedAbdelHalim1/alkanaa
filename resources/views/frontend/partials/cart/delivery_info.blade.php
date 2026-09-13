@php
    $admin_products = array();
    $seller_products = array();
    $admin_product_variation = array();
    $seller_product_variation = array();
    foreach ($carts as $key => $cartItem){
        $product = get_single_product($cartItem['product_id']);

        if($product->added_by == 'admin'){
            array_push($admin_products, $cartItem['product_id']);
            $admin_product_variation[] = $cartItem['variation'];
        }
        else{
            $product_ids = array();
            if(isset($seller_products[$product->user_id])){
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem['product_id']);
            $seller_products[$product->user_id] = $product_ids;
            $seller_product_variation[] = $cartItem['variation'];
        }
    }

    $pickup_point_list = array();
    if (get_setting('pickup_point') == 1) {
        $pickup_point_list = get_all_pickup_points();
    }
@endphp

<!-- Inhouse Products -->
@if (!empty($admin_products))
    <div class="kn-co-dlv-group">
        @include('frontend.partials.cart.delivery_info_details', ['products' => $admin_products, 'product_variation' => $admin_product_variation, 'owner_id' => get_admin()->id ])
    </div>
@endif
<input type="hidden" id="carrierCount" value="{{ count($carrier_list) }}">
<!-- Seller Products -->
@if (!empty($seller_products))
    @foreach ($seller_products as $key => $seller_product)
        <div class="kn-co-dlv-group">
            <h3 class="kn-co-dlv-shop">{{ get_shop_by_user_id($key)->name }} {{ translate('Products') }} ({{ sprintf("%02d", count($seller_product)) }})</h3>
            @include('frontend.partials.cart.delivery_info_details', ['products' => $seller_product, 'product_variation' => $seller_product_variation, 'owner_id' => $key ])
        </div>
    @endforeach
@endif
