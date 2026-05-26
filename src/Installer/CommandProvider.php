<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

use Facile\CodingStandards\Installer\Command\CreateConfigCommand;
use Composer\Command\BaseCommand;

class CommandProvider implements \Composer\Plugin\Capability\CommandProvider
{
    /**
     * Retrieves an array of commands.
     *
     * @return BaseCommand[]
     */
    public function getCommands(): array
    {
        return [
            new CreateConfigCommand(),
        ];
    }
}
