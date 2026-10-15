import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/admin.css', 'resources/css/admin-summernote.css', 'resources/js/app.js', 'resources/js/admin.js', 'resources/css/frontend.css', 'resources/js/frontend.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 900,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/jquery')) return 'vendor-jquery';
                    if (id.includes('node_modules/summernote')) return 'vendor-summernote';
                    if (id.includes('node_modules/chart.js')) return 'vendor-chartjs';
                    if (id.includes('node_modules')) return 'vendor';
                },
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
