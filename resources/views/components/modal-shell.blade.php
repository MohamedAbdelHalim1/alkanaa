{{--
    A slot-based modal built on the `modal` Alpine primitive (resources/js/
    alpine.js) — replaces the old Bootstrap data-toggle="modal" API. Usage:

        <button type="button" x-on:click="$dispatch('open-modal', { id: 'edit-user' })">
            {{ translate('Edit') }}
        </button>

        <x-modal-shell id="edit-user" title="Edit user" size="lg">
            ...form fields...
            <x-slot:footer>
                <button type="submit" class="btn-primary">{{ translate('Save') }}</button>
            </x-slot:footer>
        </x-modal-shell>

    Close it from inside with `x-on:click="close"` (available on the root
    scope), or from anywhere else by dispatching `close-modal` the same way.
--}}
@props(['id', 'title' => null, 'size' => null, 'footer' => null])

@php
    $widthClass = match ($size) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };
@endphp

<div
    x-data="modal('{{ $id }}')"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-neutral-900/50"
        x-on:click="close"
    ></div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:keydown.escape.window="close"
        class="relative w-full {{ $widthClass }} max-h-[90vh] overflow-y-auto rounded-lg bg-white shadow-md"
        role="dialog"
        aria-modal="true"
    >
        @if($title)
            <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4">
                <h5 class="font-semibold text-neutral-900">{{ $title }}</h5>
                <button type="button" class="text-neutral-500 hover:text-neutral-900" x-on:click="close" aria-label="{{ translate('Close') }}">
                    &times;
                </button>
            </div>
        @endif
        <div class="p-6">
            {{ $slot }}
        </div>
        @if($footer)
            <div class="flex items-center justify-end gap-2 border-t border-neutral-200 px-6 py-4">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
