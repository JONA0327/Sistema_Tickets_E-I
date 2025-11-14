import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/Sistemas_IT/inventario-index.css', // Sistemas_IT page styles
                // Sistemas_IT page scripts
                'resources/js/Sistemas_IT/tickets-my.js',
                'resources/js/Sistemas_IT/tickets-create.js',
                'resources/js/Sistemas_IT/prestamos-create.js',
                'resources/js/Sistemas_IT/help-index.js',
                'resources/js/Sistemas_IT/inventario-create.js',
                'resources/js/Sistemas_IT/discos-uso-create.js',
                'resources/js/Sistemas_IT/discos-uso-retirar.js',
                'resources/js/Sistemas_IT/inventario-edit.js',
                'resources/js/Sistemas_IT/admin-users-index.js',
                'resources/js/Sistemas_IT/admin-users-edit.js',
                // Area: Recursos Humanos
                'resources/css/Recursos_Humanos/index.css',
                'resources/js/Recursos_Humanos/index.js',
                // Area: Logistica
                'resources/css/Logistica/index.css',
                'resources/js/Logistica/index.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined,
            }
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
