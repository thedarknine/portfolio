import js from '@eslint/js';
import globals from 'globals';
import { defineConfig } from 'eslint/config';

export default defineConfig([
  {
    files: ['assets/**/*.js'],

    extends: [
      js.configs.recommended,
    ],

    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',

      globals: {
        ...globals.browser,
      },
    },

    rules: {
      'no-unused-vars': 'warn',
    },
  },
]);