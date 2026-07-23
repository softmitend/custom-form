import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        {
            name: 'secure-svg-namespaces',
            generateBundle(_, bundle) {
                for (const asset of Object.values(bundle)) {
                    if (asset.type === 'asset' && typeof asset.source === 'string') {
                        asset.source = asset.source.replaceAll(
                            'http://www.w3.org/2000/svg',
                            'https://www.w3.org/2000/svg',
                        );
                    }
                }
            },
        },
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
