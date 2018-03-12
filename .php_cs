<?php

/**
 * Config for PHP-CS-Fixer ver2
 */

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'increment_style' => ['style' => 'post'],
    'linebreak_after_opening_tag' => true,
    'mb_str_functions' => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'no_php4_constructor' => true,
    'no_short_echo_tag' => true,
    'no_unreachable_default_argument_value' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'not_operator_with_successor_space' => true,
    'ordered_imports' => ['sortAlgorithm' => 'length'],
    'php_unit_strict' => true,
    'phpdoc_order' => true,
    'strict_comparison' => true,
    'strict_param' => true,
];

$excludes = [
    'vendor',
    'node_modules',
];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude($excludes)
            ->notName('README.md')
            ->notName('*.xml')
            ->notName('*.yml')
    );
