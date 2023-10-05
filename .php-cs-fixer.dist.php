<?php

$providers = [
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(),
    new Facile\CodingStandards\Rules\ArrayRulesProvider([
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'], // TODO: drop when PHP 8.0+ is required
        ],
    ]),
];

$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider($providers);

$config = new PhpCsFixer\Config('facile-it/facile-coding-standard');
$config->setRules($rulesProvider->getRules());

$config->setUsingCache(false);
$config->setRiskyAllowed(true);

$finder = new PhpCsFixer\Finder();
$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
$finder->in($autoloadPathProvider->getPaths());
$finder->append([
    __DIR__ . '/.php-cs-fixer.dist.php',
    __DIR__ . '/dump_rules.php',
]);
$config->setFinder($finder);

return $config;
