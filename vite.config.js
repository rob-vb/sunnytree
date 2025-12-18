import { defineConfig } from 'vite';
import liveReload from 'vite-plugin-live-reload';
import { resolve } from 'path';

export default defineConfig({
  plugins: [
    liveReload([
      __dirname + '/**/*.php',
      __dirname + '/**/*.html',
    ]),
  ],

  resolve: {
    alias: {
      jquery: resolve(__dirname, 'src/js/jquery-shim.js'),
    },
  },

  root: 'src',
  base: process.env.NODE_ENV === 'development'
    ? '/'
    : '/wp-content/themes/sunnytree/dist/',

  build: {
    outDir: resolve(__dirname, 'dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        format: 'iife',
        entryFileNames: 'assets/[name]-[hash].js',
      },
    },
  },

  server: {
    cors: true,
    strictPort: true,
    port: 5173,
    hmr: {
      host: 'localhost',
    },
  },
});
