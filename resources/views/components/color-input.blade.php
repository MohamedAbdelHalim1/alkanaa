{{--
    Replaces the AIZ color-input widget. A native <input type="color"> swatch
    kept in sync with a hex text field, via Alpine — no external library
    needed. Usage:

        <x-color-input name="banner_color" :value="$setting->color ?? '#2f6fed'" />
--}}
@props(['name', 'value' => '#000000'])

<div x-data="{ color: '{{ $value }}' }" class="inline-flex items-stretch rounded-md border border-neutral-200 overflow-hidden">
    <input
        type="color"
        x-model="color"
        class="size-10 shrink-0 cursor-pointer border-0 border-r border-neutral-200 p-0"
        aria-label="{{ translate('Pick a color') }}"
    >
    <input
        type="text"
        name="{{ $name }}"
        x-model="color"
        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
        class="w-28 px-3 text-sm focus:outline-none"
        placeholder="#000000"
    >
</div>
