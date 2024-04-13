//vite.config.js
import { defineConfig } from 'vite';
export default defineConfig({
    plugins: [
        {
            name: 'no-public',
            enforce: 'pre',
            apply: 'build',
            configResolved(config) {
                if (config.publicDir !== false) {
                    config.publicDir = '';
                }
            },
        },
    ],
    build: {
        manifest: true,
        input: 'main.js',
        watch: false,
        outDir: '../app/assets/js/three/',
    },
});