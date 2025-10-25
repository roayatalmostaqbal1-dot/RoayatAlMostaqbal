import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools'
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/vue.js', 'resources/css/vue.css'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        vueDevTools()
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '192.168.100.12',
            // host: '127.0.0.1',
        },
    },

});
