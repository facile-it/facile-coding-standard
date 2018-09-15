<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

class CommandProvider implements \Composer\Plugin\Capability\CommandProvider
{
    /**
     * Retrieves an array of commands
     *
     * @return \Composer\Command\BaseCommand[]
     */
    public function getCommands(): array
    {
        return [
            new Command\CreateConfigCommand(),
        ];
    }
}
