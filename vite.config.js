import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/admin.css',
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    clearScreen: false,
    server: {
        host: '127.0.0.1',
        port: 5190,
        strictPort: true,
        hmr: {
            protocol: 'ws',
            host: '127.0.0.1',
            overlay: false,
        },
        watch: {
            usePolling: true,
            interval: 5000,
            ignored: [
                '**/storage/**',
                '**/bootstrap/cache/**',
                '**/node_modules/**',
                '**/vendor/**',
                '**/public/build/**',
            ],
        },
    },
});
