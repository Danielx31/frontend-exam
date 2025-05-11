import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path'; // Import path for alias configuration

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(), // enable Vue support
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // Set alias for @
        },
    },
});
