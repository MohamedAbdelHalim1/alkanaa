{{--
    Renders a status pill with a consistent color/shape, driven by a status ->
    tone map passed from the caller, so status colors stop being hand-picked
    per view. Usage:

        <x-status-badge
            :status="$order->delivery_status"
            :map="['pending' => 'warning', 'delivered' => 'success', 'cancelled' => 'danger']"
        />

        {{-- or specify the tone directly: --}}
        <x-status-badge status="Out of stock" tone="danger" />
--}}
@props(['status', 'map' => [], 'tone' => null])

@php
    $resolvedTone = $tone ?? ($map[$status] ?? 'neutral');
    $toneClasses = [
        'success' => 'bg-success/10 text-success',
        'danger' => 'bg-danger/10 text-danger',
        'warning' => 'bg-warning/10 text-warning',
        'info' => 'bg-info/10 text-info',
        'primary' => 'bg-primary/10 text-primary',
        'neutral' => 'bg-neutral-200 text-neutral-700',
    ][$resolvedTone] ?? 'bg-neutral-200 text-neutral-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold whitespace-nowrap before:content-[''] before:size-2 before:rounded-full before:bg-current $toneClasses"]) }}>
    {{ translate($status) }}
</span>
