<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer\Command;

use Facile\CodingStandards\Installer\Command\CreateConfigCommand;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;
use Facile\CodingStandardsTest\Framework\TestCase;
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
            '.php-cs-fixer.dist.php',
            $noDev,
            $noRisky
        )
            ->shouldBeCalled();

        $result = $command->run($input, $output->reveal());

        $this->assertSame(0, $result);
    }

    /**
     * @return array{string[], bool, bool}[]
     */
    public static function executeProvider(): array
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
        ];
    }
}
