<?php

$header = 'UserFrosting Commerce Sprinkle (http://www.userfrosting.com)

@link      https://github.com/ssnukala/sprinkle-commerce
@copyright Copyright (c) 2025 Srinivas Nukala
@license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)';

$rules = [
    'header_comment' => [
        'header'       => $header
    ]
];
$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app/src',
        __DIR__ . '/app/tests',
    ]);
$config = new PhpCsFixer\Config();

return $config
    ->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRiskyAllowed(true);
