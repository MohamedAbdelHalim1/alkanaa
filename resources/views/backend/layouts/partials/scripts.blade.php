{{--
    Shared closing scripts for the admin panel's two layouts. The actual
    JS bundle is loaded via @vite(...) in partials/head.blade.php (module
    scripts are deferred by default, so loading it in <head> doesn't block
    rendering) — this partial just handles per-page scripts and flash
    messages.
--}}
@yield('script')

<script type="text/javascript">
    @foreach (session('flash_notification', collect())->toArray() as $message)
        window.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @if ($message['message'] == translate('Product has been inserted successfully'))
            var data_type = ['digital', 'physical', 'auction', 'wholesale'];
            data_type.forEach(element => {
                localStorage.setItem('tempdataproduct_'+element, '{}');
                localStorage.setItem('tempload_'+element, 'no');
            });
        @endif
    @endforeach
</script>
