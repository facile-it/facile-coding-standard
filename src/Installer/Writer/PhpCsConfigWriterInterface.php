<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Writer;

interface PhpCsConfigWriterInterface
{
    /**
     * @param null|string $filename
     * @param bool $noDev
     * @param bool $noRisky
     */
    public function writeConfigFile(?string $filename = null, bool $noDev = false, bool $noRisky = false): void;
}
