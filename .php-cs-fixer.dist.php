<?php

$providers = [
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(),
];

$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider($providers);

$config = PhpCsFixer\Config::create();
$config->setRules($rulesProvider->getRules());

$config->setUsingCache(false);
$config->setRiskyAllowed(true);

$finder = PhpCsFixer\Finder::create();
$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
$finder->in($autoloadPathProvider->getPaths());

$config->setFinder($finder);

return $config;
