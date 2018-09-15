<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer\Command;

use Facile\CodingStandards\Installer\Command\CreateConfigCommand;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

class CreateConfigCommandTest extends TestCase
{

    public function testGetConfigWriter(): void
    {
        $command = new CreateConfigCommand();
        $writer = $command->getConfigWriter();
        $this->assertSame($writer, $command->getConfigWriter());
    }

    public function testSetConfigWriter(): void
    {
        $command = new CreateConfigCommand();
        $writer = $this->prophesize(PhpCsConfigWriterInterface::class);
        $command->setConfigWriter($writer->reveal());
        $this->assertSame($writer->reveal(), $command->getConfigWriter());
    }

    /**
     * @dataProvider executeProvider
     *
     * @param array $args
     * @param bool $noDev
     * @param bool $noRisky
     * @throws \Exception
     */
    public function testExecute(array $args, bool $noDev, bool $noRisky): void
    {
        $command = new CreateConfigCommand();
        $writer = $this->prophesize(PhpCsConfigWriterInterface::class);
        $command->setConfigWriter($writer->reveal());

        $input = new ArgvInput($args, $command->getDefinition());
        $output = $this->prophesize(OutputInterface::class);

        $writer->writeConfigFile(
            '.php_cs.dist',
            $noDev,
            $noRisky
        )
            ->shouldBeCalled();

        $result = $command->run($input, $output->reveal());

        $this->assertSame(0, $result);
    }

    public function executeProvider(): array
    {
        return [
            [
                ['facile-cs-create-config'],
                false,
                false,
            ],
            [
                ['facile-cs-create-config', '--no-dev'],
                true,
                false,
            ],
            [
                ['facile-cs-create-config', '--no-risky'],
                false,
                true,
            ],
            [
                ['facile-cs-create-config', '--no-dev', '--no-risky'],
                true,
                true,
            ],
            [
                ['facile-cs-create-config', '-n'],
                true,
                false,
            ],
            [
                ['facile-cs-create-config', '-r'],
                false,
                true,
            ],
            [
                ['facile-cs-create-config', '-n', '-r'],
                true,
                true,
            ],
        ];
    }
}
