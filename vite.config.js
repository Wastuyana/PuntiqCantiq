import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // Jagoan kita di v4

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/style.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});