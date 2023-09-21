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
    '@PhpCsFixer' => true,
    '@Symfony' => true,
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
    'multiline_whitespace_before_semicolons' => array(
        'strategy' => 'new_line_for_chained_calls',
    ),
    'array_syntax' => array(
        'syntax' => 'long',
    ),
))
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
