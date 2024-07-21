import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Icons from "unplugin-icons/vite";
import path from "path";

export default defineConfig({
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
        Icons({ autoInstall: true })
    ],
    resolve: {
        alias: {
            "@H": path.resolve(__dirname, "./resources/js/Shared/Helpers"),
            "@J": path.resolve(__dirname, "./resources/js/Jetstream"),
            "@S": path.resolve(__dirname, "./resources/js/Shared"),
            "@": path.resolve(__dirname, "./resources/js"),
        },
    },
});
