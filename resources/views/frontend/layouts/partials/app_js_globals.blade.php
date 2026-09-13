{{-- Global JavaScript functions used across all frontend pages --}}
<script>
    function formatPrice(n, cur) {
        try {
            return new Intl.NumberFormat(document.documentElement.lang || 'sa').format(n) + (cur ? (' ' + cur) : '');
        } catch (e) {
            return n + (cur ? (' ' + cur) : '');
        }
    }

    function mountMiniCartToastNearCart(html) {
        $('.mini-cart-toast').remove();
        var $toast = $(html).appendTo('body').css({ position: 'absolute', top: 0, left: 0, visibility: 'hidden' });
        var cartEl = document.getElementById('nav-cart-area');
        if (!cartEl) {
            $toast.css({ position: 'fixed', visibility: 'visible', top: 80, right: 16 }).addClass('show');
            return;
        }
        var rect = cartEl.getBoundingClientRect();
        var scrollY = window.scrollY || window.pageYOffset;
        var scrollX = window.scrollX || window.pageXOffset;
        var w = $toast.outerWidth();
        var isRTL = $toast.hasClass('rtl');
        var gap = 10;
        var top = rect.bottom + scrollY + gap;
        var left = isRTL ? (rect.left + scrollX) : (rect.right + scrollX - w);
        var minLeft = 8 + scrollX;
        var maxLeft = scrollX + document.documentElement.clientWidth - w - 8;
        left = Math.max(minLeft, Math.min(left, maxLeft));
        $toast.css({ top: top, left: left, visibility: 'visible', opacity: 0, transform: 'translateY(-6px)' });
        requestAnimationFrame(function() {
            $toast.addClass('show').css({ opacity: 1, transform: 'translateY(0)' });
        });
        var iconCenterX = rect.left + rect.width / 2 + scrollX;
        var toastRight = left + w;
        var arrowOffset = isRTL ? (iconCenterX - left) : (toastRight - iconCenterX);
        var pad = 18;
        arrowOffset = Math.max(pad, Math.min(arrowOffset, w - pad));
        $toast[0].style.setProperty('--arrow-offset', arrowOffset + 'px');
        clearTimeout(window.__mctTimer);
        window.__mctTimer = setTimeout(function() {
            $toast.find('.mct-close').trigger('click');
        }, 2500);
        var targetY = Math.max(0, top - 120);
        window.scrollTo({ top: targetY, behavior: 'smooth' });
    }

    $(document).on('click', '.mct-close', function () {
        $(this).closest('.mini-cart-toast').remove();
    });

    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var id = $btn.data('id');
        var originalHtml = $btn.html();
        $btn.addClass('is-loading').prop('disabled', true).html('<span class="btn-spinner" aria-hidden="true"></span>');
        $.post('{{ route('cart.addToCart') }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            quantity: 1
        }).done(function (res) {
            if (typeof res.cart_count !== 'undefined') {
                $('.cart-count-span').text(res.cart_count);
            }
            if (res.modal_view) {
                mountMiniCartToastNearCart(res.modal_view);
            } else if (res.status != 1) {
                window.notify('warning', res.message || "{{ translate('Something went wrong') }}");
            }
        }).fail(function () {
            window.notify('danger', "{{ translate('Something went wrong') }}");
        }).always(function () {
            $btn.removeClass('is-loading').prop('disabled', false).html(originalHtml);
        });
    });

    function updateNavCart(view, count) {
        $('.cart-count').html(count);
        $('.cart-count-span').html(count);
        if (typeof view !== 'undefined') {
            $('#cart_items').html(view);
        }
    }

    function removeFromCart(key) {
        $.post('{{ route('cart.removeFromCart') }}', {
            _token: AIZ.data.csrf,
            id: key
        }, function(data) {
            updateNavCart(data.nav_cart_view, data.cart_count);
            $('#cart-details').html(data.cart_view);
            AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html()) - 1);
        });
    }

    function showLoginModal() {
        $('#login_modal').modal();
    }

    function addToCompare(id) {
        $.post('{{ route('compare.addToCompare') }}', { _token: AIZ.data.csrf, id: id }, function(data) {
            $('#compare').html(data);
            AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
            $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html()) + 1);
        });
    }

    function addToWishList(id) {
        @if (Auth::check() && Auth::user()->user_type == 'customer')
            $.post('{{ route('wishlists.store') }}', { _token: AIZ.data.csrf, id: id }, function(data) {
                if (data != 0) {
                    $('#wishlist').html(data);
                    AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                } else {
                    AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                }
            });
        @elseif(Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the WishList.') }}");
        @else
            AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
        @endif
    }

    function showAddToCartModal(id) {
        if (!$('#modal-size').hasClass('modal-lg')) {
            $('#modal-size').addClass('modal-lg');
        }
        $('#addToCart-modal-body').html(null);
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('cart.showCartModal') }}', { _token: AIZ.data.csrf, id: id }, function(data) {
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(data);
            AIZ.plugins.slickCarousel();
            AIZ.plugins.zoom();
            AIZ.extra.plusMinus();
            getVariantPrice();
        });
    }

    function showReviewImageModal(imageUrl, imagesJson) {
        try {
            var images = JSON.parse(imagesJson);
            var currentIndex = images.indexOf(imageUrl);
            $('#modalReviewImage').attr('src', imageUrl);
            $('#reviewImageModal').modal('show');
            $('#prevImageBtn').off('click').on('click', function() {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                $('#modalReviewImage').attr('src', images[currentIndex]);
            });
            $('#nextImageBtn').off('click').on('click', function() {
                currentIndex = (currentIndex + 1) % images.length;
                $('#modalReviewImage').attr('src', images[currentIndex]);
            });
        } catch (error) {
            console.error("Error parsing JSON:", error);
        }
    }

    $('#option-choice-form input').on('change', function() { getVariantPrice(); });

    function getVariantPrice() {
        if ($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajax({
                type: "POST",
                url: '{{ route('products.variant_price') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data) {
                    $('#option-choice-form #chosen_price_div').removeClass('d-none');
                    $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                    $('#available-quantity').html(data.quantity);
                    $('.input-number').prop('max', data.max_limit);
                    if (parseInt(data.in_stock) == 0 && data.digital == 0) {
                        $('.buy-now').addClass('d-none');
                        $('.add-to-cart').addClass('d-none');
                        $('.out-of-stock').removeClass('d-none');
                    } else {
                        $('.buy-now').removeClass('d-none');
                        $('.add-to-cart').removeClass('d-none');
                        $('.out-of-stock').addClass('d-none');
                    }
                    AIZ.extra.plusMinus();
                }
            });
        }
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#option-choice-form input:radio').each(function() {
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function() { count++; });
        return $('#option-choice-form input:radio:checked').length == count;
    }

    function addToCart() {
        @if (Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
            return false;
        @endif
        if (checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
                type: "POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data) {
                    $('#addToCart-modal-body').html(null);
                    $('.c-preloader').hide();
                    $('#modal-size').removeClass('modal-lg');
                    $('#addToCart-modal-body').html(data.modal_view);
                    AIZ.extra.plusMinus();
                    AIZ.plugins.slickCarousel();
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }
            });
            if ("{{ get_setting('facebook_pixel') }}" == 1) {
                fbq('track', 'AddToCart', { content_type: 'product' });
            }
        } else {
            AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
        }
    }

    function buyNow() {
        @if (Auth::check() && Auth::user()->user_type != 'customer')
            AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
            return false;
        @endif
        if (checkAddToCartValidity()) {
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
                type: "POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data) {
                    if (data.status == 1) {
                        $('#addToCart-modal-body').html(data.modal_view);
                        updateNavCart(data.nav_cart_view, data.cart_count);
                        window.location.replace("{{ route('cart') }}");
                    } else {
                        $('#addToCart-modal-body').html(null);
                        $('.c-preloader').hide();
                        $('#modal-size').removeClass('modal-lg');
                        $('#addToCart-modal-body').html(data.modal_view);
                    }
                }
            });
        } else {
            AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
        }
    }

    function bid_single_modal(bid_product_id, min_bid_amount) {
        @if (Auth::check() && (isCustomer() || isSeller()))
            var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}" + min_bid_amount + ")";
            $('#min_bid_amount').text(min_bid_amount_text);
            $('#bid_product_id').val(bid_product_id);
            $('#bid_amount').attr('min', min_bid_amount);
            $('#bid_for_product').modal('show');
        @elseif (Auth::check() && isAdmin())
            AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
        @else
            $('#login_modal').modal('show');
        @endif
    }

    function copyCouponCode(code) {
        navigator.clipboard.writeText(code);
        AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}");
    }

    function nonLinkableNotificationRead() {
        $.get('{{ route('non-linkable-notification-read') }}', function(data) {
            $('.unread-notification-count').html(data);
        });
    }

    $(document).ready(function() {
        $('.cart-animate').animate({ margin: 0 }, "slow");
        $({ deg: 0 }).animate({ deg: 360 }, {
            duration: 2000,
            step: function(now) {
                $('.cart-rotate').css({ transform: 'rotate(' + now + 'deg)' });
            }
        });
        setTimeout(function() { $('.cart-ok').css({ fill: '#d43533' }); }, 2000);

        $('.category-nav-element').each(function(i, el) {
            $(el).on('mouseover', function() {
                if (!$(el).find('.sub-cat-menu').hasClass('loaded')) {
                    $.post('{{ route('category.elements') }}', {
                        _token: AIZ.data.csrf,
                        id: $(el).data('id')
                    }, function(data) {
                        $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                    });
                }
            });
        });

        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var locale = $(this).data('flag');
                    $.post('{{ route('language.change.post') }}', { _token: AIZ.data.csrf, locale: locale }, function() {
                        location.reload();
                    });
                });
            });
        }

        if ($('#currency-change').length > 0) {
            $('#currency-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var currency_code = $(this).data('currency');
                    $.post('{{ route('currency.change') }}', { _token: AIZ.data.csrf, currency_code: currency_code }, function() {
                        location.reload();
                    });
                });
            });
        }
    });

    $('#search').on('keyup focus', function() {
        var searchKey = $(this).val();
        if (searchKey.length > 0) {
            $('body').addClass("typed-search-box-shown");
            $('.typed-search-box').removeClass('d-none');
            $('.search-preloader').removeClass('d-none');
            $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search: searchKey }, function(data) {
                if (data == '0') {
                    $('#search-content').html(null);
                    $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"' + searchKey + '"</strong>');
                    $('.search-preloader').addClass('d-none');
                } else {
                    $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                    $('#search-content').html(data);
                    $('.search-preloader').addClass('d-none');
                }
            });
        } else {
            $('.typed-search-box').addClass('d-none');
            $('body').removeClass("typed-search-box-shown");
        }
    });
</script>
