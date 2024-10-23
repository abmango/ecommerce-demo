import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: true,  // Permitir que se acceda desde una IP externa
        port: 5173,  // O el puerto que prefieras
        hmr: {
          host: 'localhost',  // Tu dominio público
          port: 5173
        }
      },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
