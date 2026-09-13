@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Wishlist') }}</h1>
    </div>

    @if (count($wishlists) > 0)
        <div class="kn-products">
            @foreach ($wishlists as $key => $wishlist)
                @if ($wishlist->product != null)
                    <div class="kn-wish-item" id="wishlist_{{ $wishlist->id }}">
                        <x-product-card :product="$wishlist->product" />
                        <!-- Remove from wishlist -->
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
            <a href="{{ route('home') }}" class="kn-btn kn-btn-primary">{{ translate('Continue Shopping') }}</a>
        </div>
    @endif
    <!-- Pagination -->
    <div class="aiz-pagination">
        {{ $wishlists->links() }}
    </div>
@endsection

@section('modal')
    <!-- add To Cart Modal -->
    <div class="modal fade" id="addToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function removeFromWishlist(id){
            $.post('{{ route('wishlists.remove') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
                $('#wishlist').html(data);
                $('#wishlist_'+id).hide();
                AIZ.plugins.notify('success', '{{ translate("Item has been renoved from wishlist") }}');
            })
        }
    </script>
@endsection
