{{--
    Mount once per layout (see backend/layouts/partials/scripts.blade.php and
    frontend/layouts/app.blade.php). Listens for `notify` events dispatched
    by window.notify(level, message) — the replacement for the removed
    AIZ.plugins.notify(level, message) — and renders them as dismissible
    toasts.
--}}
<div
    x-data="toastContainer"
    class="fixed top-4 right-4 z-[1000] flex flex-col gap-2 w-full max-w-sm pointer-events-none"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto rounded-md shadow-md border px-4 py-3 text-sm flex items-start gap-3 bg-white"
            :class="{
                'border-success text-success': toast.level === 'success',
                'border-danger text-danger': toast.level === 'error' || toast.level === 'danger',
                'border-warning text-warning': toast.level === 'warning',
                'border-info text-info': toast.level === 'info',
            }"
        >
            <span class="flex-1 text-neutral-900" x-text="toast.message"></span>
            <button type="button" class="text-neutral-500 hover:text-neutral-900" x-on:click="dismiss(toast.id)" aria-label="{{ translate('Close') }}">
                &times;
            </button>
        </div>
    </template>
</div>
