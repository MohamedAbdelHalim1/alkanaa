{{--
    Replaces the old AIZ checkbox markup pattern:
        <label class="aiz-checkbox">
            <input type="checkbox" ...>
            <span class="aiz-square-check"></span>
        </label>
    with a plain native checkbox styled via Tailwind's accent-color, which
    needs no JS at all. Usage:

        <x-checkbox name="id[]" value="{{ $product->id }}" class="check-one" />
--}}
@props(['checked' => false])

<input
    type="checkbox"
    @checked($checked)
    {{ $attributes->merge(['class' => 'size-4 rounded border-neutral-300 accent-primary cursor-pointer']) }}
>
