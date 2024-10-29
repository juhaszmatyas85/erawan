import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        legacy({
            targets: ['defaults', 'not IE 11'],
        }),
    ],
    build: {
        outDir: 'erawanpgnd.myplan.hu/wp-content/themes/generatepress_child/js/app',
        rollupOptions: {
            input: 'src/js/app/app.js',
            output: {
                entryFileNames: 'app.js',
                format: 'es',
            },
        },
    },
});
