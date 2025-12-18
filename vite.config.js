import { defineConfig } from 'vite';
import liveReload from 'vite-plugin-live-reload';
import autoprefixer from 'autoprefixer';
import { resolve } from 'path';

export default defineConfig({
  css: {
    postcss: {
      plugins: [
        autoprefixer(),
      ],
    },
  },

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
    cssCodeSplit: false,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        format: 'iife',
        entryFileNames: 'assets/[name]-[hash].js',
        assetFileNames: 'assets/[name]-[hash][extname]',
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
