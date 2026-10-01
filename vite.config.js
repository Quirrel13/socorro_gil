import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // endereço que o NAVEGADOR deve usar (vai para o arquivo public/hot)
        hmr: {
            host: 'localhost',
        },
        // no Windows, o Docker nem sempre avisa quando um arquivo muda
        watch: {
            usePolling: true,
        },
    },
});