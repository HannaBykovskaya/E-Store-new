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
		host: '192.168.88.16', // Замените на ваш IP
		port: 5173,             // Замените на нужный порт
		strictPort: true,
	},
});
