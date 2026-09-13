import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Swiper from 'swiper';
import { Navigation, Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

Alpine.plugin(collapse);

// Swiper component for Alpine.js with touch-swipe, autoplay & optional pagination/navigation
Alpine.data('carousel', (options = {}) => ({
    swiper: null,
    init() {
        const config = {
            modules: [Navigation, Autoplay, Pagination],
            slidesPerView: 1,
            spaceBetween: 12,
            grabCursor: true,
            pagination: this.$refs.pagination ? {
                el: this.$refs.pagination,
                clickable: true,
            } : false,
            ...options,
        };

        if (this.$refs.prev && this.$refs.next) {
            config.navigation = {
                prevEl: this.$refs.prev,
                nextEl: this.$refs.next,
            };
        }

        this.swiper = new Swiper(this.$refs.swiper, config);
    },
    destroy() {
        this.swiper?.destroy();
    },
}));

window.Alpine = Alpine;
Alpine.start();

/*
 * Quick add-to-cart + mini-cart toast
 */
window.mountMiniCartToastNearCart = function (html) {
    const $ = window.jQuery;
    if (!$) return;

    $('.mini-cart-toast').remove();

    const isRTL = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'sa' || document.documentElement.lang === 'ar';
    const isMobile = window.innerWidth < 992;

    const $toast = $(html).appendTo('body');

    if (isMobile) {
        $toast.css({
            position: 'fixed',
            top: '70px',
            right: isRTL ? '12px' : 'auto',
            left: isRTL ? 'auto' : '12px',
            zIndex: 99999,
            visibility: 'visible',
            opacity: 1,
            transform: 'none'
        }).addClass('show');

        clearTimeout(window.__mctTimer);
        window.__mctTimer = setTimeout(() => $toast.fadeOut(300, () => $toast.remove()), 3500);
        return;
    }

    const cartEl = document.getElementById('nav-cart-area');
    if (!cartEl) {
        $toast.css({
            position: 'fixed',
            top: '75px',
            right: isRTL ? '20px' : 'auto',
            left: isRTL ? 'auto' : '20px',
            zIndex: 99999,
            visibility: 'visible',
            opacity: 1,
            transform: 'none'
        }).addClass('show');

        clearTimeout(window.__mctTimer);
        window.__mctTimer = setTimeout(() => $toast.fadeOut(300, () => $toast.remove()), 3500);
        return;
    }

    const rect = cartEl.getBoundingClientRect();
    const scrollY = window.scrollY || window.pageYOffset;
    const scrollX = window.scrollX || window.pageXOffset;
    const w = $toast.outerWidth() || 340;

    const gap = 10;
    const top = rect.bottom + scrollY + gap;
    let left = isRTL ? rect.left + scrollX : rect.right + scrollX - w;

    const minLeft = 8 + scrollX;
    const maxLeft = scrollX + document.documentElement.clientWidth - w - 8;
    left = Math.max(minLeft, Math.min(left, maxLeft));

    $toast.css({
        position: 'absolute',
        top: top + 'px',
        left: left + 'px',
        zIndex: 99999,
        visibility: 'visible',
        opacity: 1,
        transform: 'none'
    }).addClass('show');

    clearTimeout(window.__mctTimer);
    window.__mctTimer = setTimeout(() => $toast.fadeOut(300, () => $toast.remove()), 3500);
};

document.addEventListener('DOMContentLoaded', function () {
    const $ = window.jQuery;
    if (!$) return;

    // Password show/hide. Pages that include auth/login_register_js bind their
    // own click handler directly on the icon; leave those alone.
    $(document).on('click keydown', '.password-toggle', function (e) {
        if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
        const direct = $._data(this, 'events');
        if (e.type === 'click' && direct && direct.click) return;
        e.preventDefault();

        const $icon = $(this);
        const $input = $icon.siblings('input').first();
        const show = $input.attr('type') === 'password';
        $input.attr('type', show ? 'text' : 'password');
        $icon.toggleClass('la-eye', !show).toggleClass('la-eye-slash', show);
        $icon.attr('aria-pressed', show ? 'true' : 'false');
    });

    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const productId = $btn.data('id');
        if (!productId || $btn.prop('disabled')) return;

        $btn.prop('disabled', true);
        const originalHtml = $btn.html();
        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            type: 'POST',
            url: (window.AIZ && window.AIZ.routes && window.AIZ.routes.addToCart) || '/cart/addtocart',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: productId,
                quantity: 1,
            },
            success: function (data) {
                if (data.status === 1 || data.status === true || data.modal_view) {
                    if (data.modal_view) {
                        window.mountMiniCartToastNearCart(data.modal_view);
                    }
                    if (typeof updateNavCart === 'function') {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    } else if (data.cart_count !== undefined) {
                        $('.cart-count-span').text(data.cart_count);
                    }
                } else if (data.message) {
                    if (window.AIZ && window.AIZ.plugins && window.AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', data.message);
                    } else {
                        alert(data.message);
                    }
                }
            },
            error: function () {
                if (window.AIZ && window.AIZ.plugins && window.AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', (AIZ.local && AIZ.local.add_to_cart_failed) || 'Error adding to cart');
                }
            },
            complete: function () {
                // Buttons that were disabled never reach the request, so re-enable.
                $btn.prop('disabled', false).html(originalHtml);
            },
        });
    });
});
