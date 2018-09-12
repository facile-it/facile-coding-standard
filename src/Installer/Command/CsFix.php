<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsFix extends BaseCommand
{
    protected function configure()
    {
        $this->setName('facile-cs-fix');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('This is a test');
    }

    /**
     * Whether or not this command is meant to call another command.
     *
     * This is mainly needed to avoid duplicated warnings messages.
     *
     * @return bool
     */
    public function isProxyCommand()
    {
        return true;
    }
}
