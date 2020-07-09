<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Facile\CodingStandards\Installer\Command\CreateConfigCommand;
use Facile\CodingStandards\Installer\CommandProvider;
use Facile\CodingStandardsTest\Framework\TestCase;

class CommandProviderTest extends TestCase
{
    public function testGetCommands(): void
    {
        $provider = new CommandProvider();

        $this->assertInstanceOf(ComposerCommandProvider::class, $provider);

        $commands = $provider->getCommands();
        $this->assertCount(1, $commands);
        $this->assertInstanceOf(CreateConfigCommand::class, $commands[0]);
    }
}
