<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Command;

use Composer\Command\BaseCommand;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriter;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateConfigCommand extends BaseCommand
{
    /**
     * @var PhpCsConfigWriterInterface
     */
    private $configWriter;

    public function __construct(string $name = null)
    {
        $this->configWriter = new PhpCsConfigWriter();

        parent::__construct($name);
    }

    /**
     * @return PhpCsConfigWriterInterface
     */
    public function getConfigWriter(): PhpCsConfigWriterInterface
    {
        return $this->configWriter;
    }

    /**
     * @param PhpCsConfigWriterInterface $configWriter
     */
    public function setConfigWriter(PhpCsConfigWriterInterface $configWriter): void
    {
        $this->configWriter = $configWriter;
    }

    protected function configure(): void
    {
        $this
            ->setName('facile-cs-create-config')
            ->setDescription('Write the facile-coding-standard configuration for php-cs-fixer')
            ->setDefinition([
                new InputOption('no-dev', null, InputOption::VALUE_NONE, 'Do not include autoload-dev directories'),
                new InputOption('no-risky', null, InputOption::VALUE_NONE, 'Do not include risky rules'),
            ])
            ->setHelp(
                <<<HELP
Write config file in <comment>.php-cs-fixer.dist.php</comment>.
HELP
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configWriter = $this->getConfigWriter();

        $configWriter->writeConfigFile(
            '.php-cs-fixer.dist.php',
            (bool) $input->getOption('no-dev'),
            (bool) $input->getOption('no-risky')
        );

        return 0;
    }
}
