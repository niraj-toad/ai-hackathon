import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vitest/config'
import manifestSRI from 'vite-plugin-manifest-sri';

const env = process.env.NODE_ENV || 'development';

export default defineConfig({
    build: {
        sourcemap: env === 'development' ? 'inline' : 'hidden',
    },
    esbuild: {
        // Keep names on classes during minification to preserve error names for type guards
        keepNames: true,
    },
    plugins: [
        env === 'test' ? {} : laravel({
            input: ['resources/scripts/main.ts'],
            refresh: true,
        }),
        vue({
            base: null,
            includeAbsolute: false,
        }),
        manifestSRI(),
    ],
    resolve: {
        alias: {
            '@': '/resources/scripts',
            '@assets': '/resources/assets',
        }
    },
    server: {
        hmr: {
            host: 'aihack.docker',
        },
        host: '0.0.0.0',
        port: 5161,
    },
    test: {
        environment: 'jsdom',
        include: ['resources/scripts/**/*.test.ts'],
        setupFiles: ['./vitest.setup.ts'],
    },
});
