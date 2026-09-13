{{--
    Checkout progress: Cart → Shipping address → Delivery → Payment → Confirmation.
    Params:
      current    (int)   first active step, 1-5
      currentEnd (int)   last active step (single-page checkout covers 2-4); defaults to current
      anchors    (array) step number => in-page href, e.g. [2 => '#kn-step-shipping']
      links      (bool)  link completed steps back to the cart (default true)
--}}
@php
    $ksIsAr = in_array(app()->getLocale(), ['sa', 'ar', 'eg']);
    $ks = fn ($ar, $en) => $ksIsAr ? $ar : $en;
    $ksCurrent = (int) ($current ?? 1);
    $ksCurrentEnd = (int) ($currentEnd ?? $ksCurrent);
    $ksAnchors = $anchors ?? [];
    $ksLinks = $links ?? true;
    $ksSteps = [
        1 => ['label' => $ks('السلة', 'Cart'), 'href' => route('cart')],
        2 => ['label' => $ks('عنوان الشحن', 'Shipping address'), 'href' => null],
        3 => ['label' => $ks('التوصيل', 'Delivery'), 'href' => null],
        4 => ['label' => $ks('الدفع', 'Payment'), 'href' => null],
        5 => ['label' => $ks('التأكيد', 'Confirmation'), 'href' => null],
    ];
@endphp
<nav class="kn-co-progress" aria-label="{{ $ks('مراحل إتمام الطلب', 'Checkout steps') }}">
    <ol class="kn-co-progress-list">
        @foreach ($ksSteps as $ksNum => $ksStep)
            @php
                $ksState = $ksNum < $ksCurrent ? 'is-done' : ($ksNum <= $ksCurrentEnd ? 'is-current' : 'is-todo');
                $ksHref = $ksAnchors[$ksNum] ?? ($ksLinks && $ksState === 'is-done' ? $ksStep['href'] : null);
            @endphp
            <li class="kn-co-prog {{ $ksState }}" @if ($ksNum === $ksCurrent) aria-current="step" @endif>
                @if ($ksHref)
                    <a href="{{ $ksHref }}" class="kn-co-prog-inner">
                @else
                    <span class="kn-co-prog-inner">
                @endif
                    <span class="kn-co-prog-dot" aria-hidden="true">
                        @if ($ksState === 'is-done')
                            <i class="fa-solid fa-check"></i>
                        @else
                            {{ $ksNum }}
                        @endif
                    </span>
                    <span class="kn-co-prog-label">
                        {{ $ksStep['label'] }}
                        @if ($ksState === 'is-done')
                            <span class="kn-sr-only">({{ $ks('مكتملة', 'completed') }})</span>
                        @endif
                    </span>
                @if ($ksHref)
                    </a>
                @else
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
