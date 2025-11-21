import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

const viteHost = process.env.VITE_HOST || 'localhost';
const vitePort = process.env.VITE_PORT || 5173;

export default defineConfig({
    server: {
        host: viteHost,  // Permitir que se acceda desde una IP externa
        port: vitePort  // O el puerto que prefieras
      },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
            hmr: {
                host: viteHost,  // Tu dominio público
                port: vitePort
            }            
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
