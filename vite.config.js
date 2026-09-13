import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/storefront.css',
                'resources/css/storefront-compat.css',
                'resources/js/storefront.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
