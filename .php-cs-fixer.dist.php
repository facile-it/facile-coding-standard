<?php

$providers = [
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(),
];

$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider($providers);

$config = new PhpCsFixer\Config('facile-it/facile-coding-standard');
$config->setRules($rulesProvider->getRules());

$config->setUsingCache(false);
$config->setRiskyAllowed(true);

$finder = new PhpCsFixer\Finder();
$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
$finder->in($autoloadPathProvider->getPaths());

$config->setFinder($finder);

return $config;
