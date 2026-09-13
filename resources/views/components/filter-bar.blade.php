{{--
    The search/type/date filter row repeated across nearly every admin index
    page, centralized so it only needs styling/behavior fixes in one place.
    Usage:

        <x-filter-bar :action="route('products.index')">
            <input type="text" name="search" placeholder="{{ translate('Search') }}" value="{{ $sort_search }}">
            <x-slot:actions>
                <button type="submit">{{ translate('Filter') }}</button>
            </x-slot:actions>
        </x-filter-bar>
--}}
@props(['action' => null, 'method' => 'GET', 'actions' => null])

<form
    class="flex flex-wrap items-center gap-3 rounded-md border border-neutral-200 bg-white p-4 mb-5 [&_input]:rounded-md [&_input]:border [&_input]:border-neutral-200 [&_input]:px-3 [&_input]:py-2 [&_input]:text-sm [&_select]:rounded-md [&_select]:border [&_select]:border-neutral-200 [&_select]:px-3 [&_select]:py-2 [&_select]:text-sm"
    @if($action) action="{{ $action }}" @endif
    method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
>
    @if(strtoupper($method) !== 'GET')
        @csrf
    @endif
    <div class="flex-1 min-w-64">
        {{ $slot }}
    </div>
    @if($actions)
        <div class="flex items-center gap-2 ml-auto">
            {{ $actions }}
        </div>
    @endif
</form>
