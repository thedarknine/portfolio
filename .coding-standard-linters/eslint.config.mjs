import js from '@eslint/js';
import globals from 'globals';
import eslintConfigPrettier from 'eslint-config-prettier';
import { defineConfig } from 'eslint/config';

export default defineConfig([
  {
    files: ['assets/**/*.js'],

    extends: [
      js.configs.recommended,
      eslintConfigPrettier,
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