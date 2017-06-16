<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Provider\SourcePaths;

/**
 * Interface SourcePathsProviderInterface.
 */
interface ProviderInterface
{
    /**
     * Get PHP CS source paths.
     *
     * @return array
     */
    public function getSourcePaths(): array;
}
