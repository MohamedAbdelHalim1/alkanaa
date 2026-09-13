import Drift from 'drift-zoom';

/**
 * Replaces AIZ.plugins.zoom() — product-image hover magnifier. Usage:
 *
 *   <img x-data="zoomImage" data-zoom="{{ $product->large_image_url }}" src="{{ $product->thumbnail_url }}">
 *
 * `data-zoom` is the full-resolution image Drift magnifies into; the img's
 * own `src` is whatever's already being displayed (thumbnail or full-size).
 */
export function registerZoomComponent(Alpine) {
    Alpine.data('zoomImage', () => ({
        drift: null,
        init() {
            if (!this.$el.dataset.zoom) return;
            this.drift = new Drift(this.$el, {
                zoomFactor: 2,
                paneContainer: document.body,
                inlinePane: false,
            });
        },
    }));
}
