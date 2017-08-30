<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Writer;

interface PhpCsConfigWriterInterface
{
    /**
     * @param string $filename
     */
    public function writeConfigFile(string $filename);
}
