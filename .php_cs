<?php

$config = PhpCsFixer\Config::create();
$config->setUsingCache(false);
$config->setRiskyAllowed(false);
$config->setRules([
    '@PSR2' => true,
    '@PHP70Migration' => true,
    'random_api_migration' => false,
    'pow_to_exponentiation' => false,
    'not_operator_with_successor_space' => true,
    'array_syntax' => ['syntax' => 'short'],
]);

return $config;