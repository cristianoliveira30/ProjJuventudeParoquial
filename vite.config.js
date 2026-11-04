import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/liturgia.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Permite que o Docker exponha a porta
        hmr: {
            host: 'localhost', // O navegador vai buscar o CSS no seu localhost, não em um IP fixo
        },
        watch: {
            usePolling: true, // Necessário no Docker/Windows para detectar mudanças nos arquivos
        },
    },
});
