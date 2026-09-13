import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Replaces AIZ.plugins.slickCarousel(). Registered as an Alpine component —
 * pair with the <x-carousel> Blade component, which provides the required
 * .swiper > .swiper-wrapper > .swiper-slide structure.
 *
 *   <div x-data="carousel({ slidesPerView: 3, loop: true })">
 */
export function registerCarouselComponent(Alpine) {
    Alpine.data('carousel', (options = {}) => ({
        swiper: null,
        init() {
            this.swiper = new Swiper(this.$el.querySelector('.swiper'), {
                modules: [Navigation, Pagination, Autoplay],
                slidesPerView: 1,
                spaceBetween: 16,
                navigation: {
                    nextEl: this.$el.querySelector('.swiper-button-next'),
                    prevEl: this.$el.querySelector('.swiper-button-prev'),
                },
                pagination: { el: this.$el.querySelector('.swiper-pagination'), clickable: true },
                ...options,
            });
        },
        destroy() {
            this.swiper?.destroy();
        },
    }));
}
