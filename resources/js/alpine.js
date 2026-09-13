import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import { registerCarouselComponent } from './widgets/carousel';
import { registerZoomComponent } from './widgets/zoom';
import { registerFileUploadDirective } from './widgets/file-upload';
import { registerSearchableSelectDirective } from './widgets/select';

Alpine.plugin(Collapse);
window.Alpine = Alpine;

/**
 * Primitive interaction components — the replacement for the removed AIZ/
 * Bootstrap `data-toggle` API. Every panel's modal/dropdown/tabs/toast/
 * collapsible-nav markup composes from these instead of reinventing its own.
 */
document.addEventListener('alpine:init', () => {
    /**
     * Modal. Usage:
     *   <button x-on:click="$dispatch('open-modal', { id: 'edit-user' })">Edit</button>
     *   <div x-data="modal('edit-user')" x-show="open" ...>...</div>
     * (see components/modal-shell.blade.php for the full markup pattern)
     */
    Alpine.data('modal', (id) => ({
        id,
        open: false,
        init() {
            this.$watch('open', (value) => {
                document.documentElement.classList.toggle('overflow-hidden', value);
            });
            // Any element anywhere can open/close this modal by id without a
            // direct reference to this component, e.g.
            // $dispatch('open-modal', { id: 'edit-user' })
            window.addEventListener('open-modal', (e) => {
                if (e.detail?.id === this.id) this.open = true;
            });
            window.addEventListener('close-modal', (e) => {
                if (!e.detail?.id || e.detail.id === this.id) this.open = false;
            });
        },
        close() {
            this.open = false;
        },
    }));

    /**
     * Dropdown / disclosure (also used for collapsible nav sections).
     * Usage: <div x-data="dropdown()">
     *            <button x-on:click="toggle">...</button>
     *            <div x-show="open" x-on:click.outside="close">...</div>
     *        </div>
     */
    Alpine.data('dropdown', (startOpen = false) => ({
        open: startOpen,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));

    /**
     * Tabs. Usage: <div x-data="tabs('overview')">
     *   <button x-on:click="select('overview')" x-bind:aria-selected="isActive('overview')">...</button>
     *   <div x-show="isActive('overview')">...</div>
     * </div>
     */
    Alpine.data('tabs', (initial) => ({
        active: initial,
        select(id) {
            this.active = id;
        },
        isActive(id) {
            return this.active === id;
        },
    }));

    /**
     * Toast notifications — replaces AIZ.plugins.notify(level, message).
     * Mounted once via <x-toast-container> in the layout. window.notify()
     * (defined in the synchronous legacy-js-bridge, not here — see below)
     * pushes onto window.__notifyQueue rather than dispatching straight to
     * this component, since notify() can be called by legacy inline
     * <script> blocks that run before this Alpine component (a deferred ES
     * module) has booted — draining a queue means no message is ever lost
     * to that race, regardless of which side loads first.
     */
    Alpine.data('toastContainer', () => ({
        toasts: [],
        init() {
            this.drain();
            window.addEventListener('notify-pushed', () => this.drain());
        },
        drain() {
            window.__notifyQueue = window.__notifyQueue || [];
            while (window.__notifyQueue.length) {
                const toast = window.__notifyQueue.shift();
                this.toasts.push(toast);
                setTimeout(() => this.dismiss(toast.id), 5000);
            }
        },
        dismiss(id) {
            this.toasts = this.toasts.filter((t) => t.id !== id);
        },
    }));

    // Milestone 1B widgets — see resources/js/widgets/*.
    registerCarouselComponent(Alpine);
    registerZoomComponent(Alpine);
    registerFileUploadDirective(Alpine);
    registerSearchableSelectDirective(Alpine);
});

Alpine.start();
