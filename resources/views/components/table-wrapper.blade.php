{{--
    Wraps a <table> with horizontal-scroll safety and consistent styling, so
    pages stop needing to remember to add responsive wrapping themselves
    (most of the app's admin tables currently don't). Usage:

        <x-table-wrapper>
            <table>
                ...
            </table>
        </x-table-wrapper>
--}}
@props(['maxHeight' => null])

<div
    class="w-full overflow-x-auto rounded-md border border-neutral-200 bg-white [&_table]:w-full [&_table]:border-collapse [&_thead_th]:sticky [&_thead_th]:top-0 [&_thead_th]:bg-neutral-50 [&_thead_th]:text-neutral-700 [&_thead_th]:text-sm [&_thead_th]:font-semibold [&_thead_th]:uppercase [&_thead_th]:tracking-wide [&_thead_th]:whitespace-nowrap [&_thead_th]:border-b [&_thead_th]:border-neutral-200 [&_thead_th]:px-4 [&_thead_th]:py-3 [&_thead_th]:text-left [&_tbody_td]:px-4 [&_tbody_td]:py-3 [&_tbody_td]:border-b [&_tbody_td]:border-neutral-100 [&_tbody_tr:hover]:bg-primary/5 [&_tbody_tr:last-child_td]:border-b-0"
    @if($maxHeight) style="max-height: {{ $maxHeight }}; overflow-y: auto;" @endif
>
    {{ $slot }}
</div>
