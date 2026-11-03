import js from '@eslint/js';
import globals from 'globals';
import { defineConfig } from 'eslint/config';
import html from '@html-eslint/eslint-plugin';
import htmlParser, { TEMPLATE_ENGINE_SYNTAX } from '@html-eslint/parser';

export default defineConfig([
  // JS Files
  {
    files: ['assets/**/*.js', 'templates/**/*.twig'],

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

  // Twig Templates
  {
    ignores: [
      'templates/admin/**',
      'templates/security/**',
    ],
  },
  {
    files: ['templates/**/*.twig'],
    extends: [html.configs.recommended],
    // extends: [html.configs.all],
    languageOptions: {
      parser: htmlParser,
      parserOptions: {
        templateEngineSyntax: TEMPLATE_ENGINE_SYNTAX.TWIG,
      },
    },
    rules: {
      'html/no-duplicate-class': 'error',
      'html/attrs-newline': ['error', {
        closeStyle: 'sameline',
        ifAttrsMoreThan: 4,
        maxLen: undefined,
        skip: [],
        inline: ['img', 'svg'],
      }],
      'html/use-baseline': ['error', { available: 'newly' }],
    },
  },
]);