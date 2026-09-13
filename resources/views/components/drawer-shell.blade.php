{{--
    A slide-over panel built on the same `modal` Alpine primitive as
    <x-modal-shell> (open/close-by-id, ESC to close, backdrop click to
    close) — just styled to slide in from the side instead of fading in
    centered. Used for the cart drawer and the mobile nav menu. Usage:

        <button x-on:click="$dispatch('open-modal', { id: 'cart-drawer' })">Cart</button>

        <x-drawer-shell id="cart-drawer" title="Your cart" side="end">
            ...
        </x-drawer-shell>
--}}
@props(['id', 'title' => null, 'side' => 'end', 'width' => 'max-w-md'])

@php
    $sideClasses = $side === 'start'
        ? ['inset-y-0 left-0', '-translate-x-full', 'translate-x-0']
        : ['inset-y-0 right-0', 'translate-x-full', 'translate-x-0'];
@endphp

<div x-data="modal('{{ $id }}')" x-show="open" x-cloak class="fixed inset-0 z-50" style="display: none;">
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-neutral-900/50"
        x-on:click="close"
    ></div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="{{ $sideClasses[1] }}"
        x-transition:enter-end="{{ $sideClasses[2] }}"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="{{ $sideClasses[2] }}"
        x-transition:leave-end="{{ $sideClasses[1] }}"
        x-on:keydown.escape.window="close"
        class="absolute {{ $sideClasses[0] }} w-full {{ $width }} bg-white shadow-md flex flex-col"
        role="dialog"
        aria-modal="true"
    >
        @if($title)
            <div class="flex items-center justify-between border-b border-neutral-200 px-5 py-4 shrink-0">
                <h5 class="font-semibold text-neutral-900">{{ $title }}</h5>
                <button type="button" class="text-neutral-500 hover:text-neutral-900" x-on:click="close" aria-label="{{ translate('Close') }}">
                    &times;
                </button>
            </div>
        @endif
        <div class="flex-1 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
