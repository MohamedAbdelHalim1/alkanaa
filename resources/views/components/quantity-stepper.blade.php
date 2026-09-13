{{--
    Replaces AIZ.extra.plusMinus() — a +/- stepper bound to a number input.
    Self-contained Alpine component, no external library needed. Usage:

        <x-quantity-stepper name="quantity" :value="1" :min="1" :max="$stock->qty" />

    Listens for its own `change` event same as a plain input, so existing
    onchange="..." handlers on the input itself keep working — only the +/-
    buttons are new behavior.
--}}
@props(['name', 'value' => 1, 'min' => 1, 'max' => null])

<div
    x-data="{
        value: {{ (int) $value }},
        min: {{ (int) $min }},
        max: {{ $max !== null ? (int) $max : 'null' }},
        decrement() {
            this.value = Math.max(this.min, this.value - 1);
            $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        increment() {
            this.value = this.max !== null ? Math.min(this.max, this.value + 1) : this.value + 1;
            $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
    }"
    class="inline-flex items-stretch rounded-md border border-neutral-200 overflow-hidden"
>
    <button type="button" x-on:click="decrement" class="px-3 text-neutral-500 hover:bg-neutral-50 hover:text-neutral-900" aria-label="{{ translate('Decrease quantity') }}">
        &minus;
    </button>
    <input
        type="number"
        x-ref="input"
        x-model.number="value"
        {{ $attributes->merge(['name' => $name, 'class' => 'w-12 border-x border-neutral-200 text-center text-sm focus:outline-none']) }}
        :min="min"
        :max="max ?? undefined"
    >
    <button type="button" x-on:click="increment" class="px-3 text-neutral-500 hover:bg-neutral-50 hover:text-neutral-900" aria-label="{{ translate('Increase quantity') }}">
        &plus;
    </button>
</div>
