<?php

$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/data/bin')
    ->in(__DIR__.'/test')
    ->append(array(__FILE__))
    // Exclude PHP classes templates/generators, which are not valid PHP files
    ->exclude('task/generator/skeleton/')
    ->exclude('plugins/sfDoctrinePlugin/data/generator/')
    // Exclude generated files (single files)
    ->notPath('unit/config/fixtures/sfDefineEnvironmentConfigHandler/prefix_result.php')
    ->notPath('unit/config/fixtures/sfFilterConfigHandler/result.php')
;

$config = new PhpCsFixer\Config();
$config->setRules(array(
    // '@PhpCsFixer' => true,
    '@Symfony' => true,
    'array_indentation' => true,
    'array_syntax' => array(
        'syntax' => 'long',
    ),
    'blank_line_before_statement' => array(
        'statements' => array(
            'break',
            'case',
            'continue',
            'declare',
            'default',
            'exit',
            'goto',
            'include',
            'include_once',
            'phpdoc',
            'require',
            'require_once',
            'return',
            'switch',
            'throw',
            'try',
            'yield',
            'yield_from',
        ),
    ),
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'empty_loop_body' => true,
    'escape_implicit_backslashes' => true,
    'explicit_indirect_variable' => true,
    'explicit_string_variable' => true,
    'heredoc_to_nowdoc' => true,
    'method_argument_space' => array(
        'on_multiline' => 'ensure_fully_multiline',
    ),
    'method_chaining_indentation' => true,
    'multiline_comment_opening_closing' => true,
    'multiline_whitespace_before_semicolons' => array(
        'strategy' => 'new_line_for_chained_calls',
    ),
    'no_extra_blank_lines' => array(
        'tokens' => array(
            'attribute',
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
        ),
    ),
    'no_superfluous_elseif' => true,
    'no_superfluous_phpdoc_tags' => array(
        'allow_mixed' => true,
        'remove_inheritdoc' => true,
    ),
    'no_unneeded_control_parentheses' => array(
        'statements' => array(
            'break',
            'clone',
            'continue',
            'echo_print',
            'negative_instanceof',
            'others',
            'return',
            'switch_case',
            'yield',
            'yield_from',
        ),
    ),
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'php_unit_internal_class' => true,
    'php_unit_test_class_requires_covers' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_no_empty_return' => true,
    'phpdoc_order_by_value' => true,
    'phpdoc_types_order' => true,
    'phpdoc_var_annotation_correct_order' => true,
    'protected_to_private' => true,
    'return_assignment' => true,
    'self_static_accessor' => true,
    'single_line_comment_style' => true,
    'whitespace_after_comma_in_array' => array(
        'ensure_single_space' => true,
    ),
    'nullable_type_declaration_for_default_null_value' => false,
    'single_line_throw' => false,
))
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
