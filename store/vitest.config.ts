import { defineConfig } from 'vitest/config';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
  plugins: [react()],
  test: {
    environment: 'jsdom',
    globals: true,
    setupFiles: './src/test/setup.ts',
    // Use forks pool to isolate memory per test file (prevents OOM)
    pool: 'forks',
    poolOptions: {
      forks: {
        minForks: 1,
        maxForks: 4, // Limit parallelism to prevent memory exhaustion
        isolate: true, // Each test file runs in fresh process
      },
    },
    // Limit concurrent tests within each file
    maxConcurrency: 5,
    coverage: {
      provider: 'v8',
      reporter: ['text', 'json', 'html'],
      exclude: [
        'node_modules/',
        'src/test/',
        '*.config.js',
        '*.config.ts',
      ],
    },
  },
  resolve: {
    alias: {
      'virtual:format-overrides': path.resolve(__dirname, './src/test/format-overrides-module.ts'),
      '@/': path.resolve(__dirname, './src/'),
      '@/components': path.resolve(__dirname, './src/components'),
      '@/lib': path.resolve(__dirname, './src/lib'),
      '@/api': path.resolve(__dirname, './src/server/api'),
      '@/db': path.resolve(__dirname, './src/server/db'),
      '@/layouts': path.resolve(__dirname, './src/layouts'),
      '@/patterns': path.resolve(__dirname, './src/patterns'),
      '@/pages': path.resolve(__dirname, './src/pages'),
      '@/hooks': path.resolve(__dirname, './src/hooks'),
      '@/styles': path.resolve(__dirname, './src/styles'),
    },
  },
});
