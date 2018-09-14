<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;

class CsCheck extends BaseCommand
{
    protected function configure()
    {
        $this->setName('facile-cs-check')
            ->setDescription('Execute php-cs-fixer')
            ->setDefinition([
                new InputArgument(
                    'args',
                    InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                    'Arguments to pass to the binary. Use <info>--</info> to separate from composer arguments'
                ),
            ])
            ->setHelp(
                <<<EOT
Facile Coding Standards - CHECK


EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = [
            'exec',
            '--',
            'php-cs-fixer',
            'fix',
            '--dry-run',
            '--diff',
        ];

        if ($input->getArgument('args')) {
            $args = \array_merge($args, $input->getArgument('args'));
        }

        $input = new ArrayInput($args);

        return $this->getApplication()->run($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    public function isProxyCommand()
    {
        return true;
    }
}
