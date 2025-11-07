<?php

$header = 'UserFrosting Commerce Sprinkle (http://www.userfrosting.com)

@link      https://github.com/ssnukala/sprinkle-commerce
@copyright Copyright (c) 2025 Srinivas Nukala
@license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)';

$rules = [
    '@PSR12' => true,
    'header_comment' => [
        'header'       => $header,
        'comment_type' => 'comment',
        'location'     => 'after_open',
        'separate'     => 'both',
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
