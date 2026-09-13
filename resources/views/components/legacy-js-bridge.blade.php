{{--
    Loaded synchronously (classic <script>, not a Vite ES module) and early
    in <head>, before @vite(...). This matters: Vite's output is always an
    ES module, which the spec defers until after the document has fully
    parsed — but this app has hundreds of pre-existing inline <script>
    blocks scattered through Blade views that assume jQuery/AIZ are already
    available the instant they run (true under the old setup, where
    vendors.js/aiz-core.js were classic blocking <script src> tags). Loading
    jQuery and this compat shim classically preserves that guarantee without
    having to rewrite every one of those inline scripts as part of this
    migration.

    window.AIZ here is NOT the removed proprietary bundle — it's a small,
    self-owned bridge kept only so existing unconverted screens don't throw
    ("AIZ is not defined") until each is actually converted. Delete an entry
    once nothing calls it anymore (grep the symbol first, e.g.
    `AIZ.plugins.slickCarousel`).
--}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script>
    window._ = window._ || {};

    // window.notify('success'|'error'|'warning'|'info', 'message') — the
    // replacement for the removed AIZ.plugins.notify(level, message).
    // Queued rather than dispatched directly: the toast UI is an Alpine
    // component (a deferred ES module) that may not have booted yet when
    // this fires from a synchronous inline script, so nothing is lost.
    window.__notifyQueue = window.__notifyQueue || [];
    window.notify = function (level, message) {
        window.__notifyQueue.push({ id: Date.now() + Math.random(), level, message });
        window.dispatchEvent(new CustomEvent('notify-pushed'));
    };

    window.AIZ = window.AIZ || {};
    window.AIZ.data = { csrf: document.head.querySelector('meta[name="csrf-token"]')?.content };
    window.AIZ.plugins = window.AIZ.plugins || {};
    window.AIZ.plugins.notify = function (level, message) { window.notify(level, message); };
    // Carousel, image zoom, quantity stepper: real replacements exist now as
    // opt-in Alpine components/directives (resources/js/widgets/*), consumed
    // via new markup (<x-carousel>, x-data="zoomImage", <x-quantity-stepper>)
    // — not drop-in overrides of these old imperative calls. A screen still
    // calling AIZ.plugins.slickCarousel()/.zoom()/AIZ.extra.plusMinus()
    // directly hasn't been converted to that new markup yet; these remain
    // safe no-ops until it is.
    window.AIZ.plugins.slickCarousel = window.AIZ.plugins.slickCarousel || function () {};
    window.AIZ.plugins.zoom = window.AIZ.plugins.zoom || function () {};
    window.AIZ.extra = window.AIZ.extra || {};
    window.AIZ.extra.plusMinus = window.AIZ.extra.plusMinus || function () {};

    // jQuery's Bootstrap `.modal()` plugin, reimplemented on the Alpine
    // `modal` primitive's open-modal/close-modal events (resources/js/
    // alpine.js). Once a given modal's markup is converted to
    // <x-modal-shell>, calls like $('#login_modal').modal('show') keep
    // working with zero further JS changes.
    jQuery.fn.modal = function (action) {
        var id = this.attr('id');
        if (!id) return this;
        window.dispatchEvent(new CustomEvent(action === 'hide' ? 'close-modal' : 'open-modal', { detail: { id: id } }));
        return this;
    };

    // .confirm-delete / .confirm-alert: generic delegated triggers used
    // throughout the admin panel to open a shared confirmation modal before
    // a destructive action. Reconstructed from the existing modal partials
    // (resources/views/modals/delete_modal.blade.php,
    // bulk_delete_modal.blade.php) since the original binding JS isn't
    // recoverable — this is a best-effort match of that markup's shape, not
    // a verified port. `.confirm-delete` sets #delete-link's href from
    // data-href, then opens #delete-modal; `.confirm-alert` just opens
    // whatever modal its data-target points at.
    jQuery(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        jQuery('#delete-link').attr('href', jQuery(this).data('href'));
        jQuery('#delete-modal').modal('show');
    });
    jQuery(document).on('click', '.confirm-alert', function (e) {
        e.preventDefault();
        var target = jQuery(this).data('target');
        if (target) jQuery(target).modal('show');
    });
</script>
