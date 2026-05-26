<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __FILE__,
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withComposerBased(
        phpunit: true
    )
    ->withImportNames(importShortClasses: false)
    ->withTypeCoverageLevel(40)
    ->withDeadCodeLevel(40)
    ->withCodeQualityLevel(40);
