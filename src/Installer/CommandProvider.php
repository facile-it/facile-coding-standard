<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands()
    {
        return [
            new Command\CsFix(),
        ];
    }
}
