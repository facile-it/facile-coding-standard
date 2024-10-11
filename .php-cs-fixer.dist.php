<?php

$providers = [
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(),
    // TODO: drop when PHP 8.0+ is required
    new Facile\CodingStandards\Rules\ArrayRulesProvider([
        'get_class_to_class_keyword' => false,
    ]),
];

$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider($providers);

$config = new PhpCsFixer\Config('facile-it/facile-coding-standard');
$config->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());
$config->setRules($rulesProvider->getRules());

$config->setUsingCache(true);
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
