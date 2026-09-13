window._ = require('lodash');

/**
 * jQuery is intentionally NOT bundled here — it's loaded classically/
 * synchronously via components/legacy-js-bridge.blade.php instead, so it's
 * available immediately for the app's many pre-existing inline <script>
 * blocks (see that file's header comment for why). Bundling a second jQuery
 * instance here would create two separate `$` objects with independently
 * registered plugins (e.g. the .modal() shim), which is exactly the kind of
 * bug this setup is trying to avoid.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
