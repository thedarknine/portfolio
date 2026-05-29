<?php

$header = <<<HEADER
This file is part of Portfolio project.
(c) Caroline Noyer <hello@carolinenoyer.fr>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
HEADER;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/..') 
    
    ->exclude([
        'var', 
        'vendor', 
        'public/build', 
        'config',
        'node_modules',
        '.tools-config', 
        'src/DataFixtures',
    ])

    ->append([__DIR__ . '/phparkitect.php'])
    
    ->notPath('importmap.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'PHPDoc'
        ],

        // Manage imports order and remove unused imports
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        'no_unused_imports' => true,

        // Order class elements
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],

        // Yoda style, prefer `true === $value` over `$value === true`
        'yoda_style' => true,

        // Align vertical arrows `=>` in arrays and `=` assignments
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=>' => 'align_single_space',
                '='  => 'align_single_space',
            ],
        ],

        // Add space around concatenation operator (ex: $a . ' ' . $b)
        // By default, Symfony concatenates strings ($a.$b). Adding space makes the code more readable
        'concat_space' => ['spacing' => 'one'],

        // Trailing comma required for multiline arrays, arguments, and parameters
        // Ideal for clean Git diffs when adding an element
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],

        // Add empty line before control structures for better readability
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],

        // Force use of short syntax for arrays `[]` instead of `array()`
        'array_syntax' => ['syntax' => 'short'],

        // Clean up unnecessary blank lines (at the end of blocks, after trait declarations...)
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],

        // Proper spacing for typed arguments and parameters (Nullable, etc.)
        'compact_nullable_type_declaration' => true,
        'type_declaration_spaces' => true,

        // Remove unnecessary PHPDoc tags
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
        ],
        
        // Convert PHPDoc to comment when possible
        'phpdoc_to_comment' => true,
        
        // Remove unnecessary else statements
        'no_useless_else' => true,
        
        // PHPDoc line span
        'phpdoc_line_span' => [
            'const'    => 'single',
            'property' => 'single',
            'method'   => 'multi',
        ],
    ])
    ->setFinder($finder)
;
