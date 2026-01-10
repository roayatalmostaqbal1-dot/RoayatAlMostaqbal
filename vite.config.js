import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools'
import { createHtmlPlugin } from 'vite-plugin-html'
import manifestSRI from 'vite-plugin-manifest-sri';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/vue.js', 'resources/css/vue.css'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        vueDevTools(),
        createHtmlPlugin({}),
        manifestSRI(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '192.168.100.12',
            // host: '127.0.0.1',
        },
    },

});
