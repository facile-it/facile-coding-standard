<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\PackageInterface;
use Composer\Semver\Semver;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriter;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;

class Installer
{
    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var array<string, mixed>
     */
    private $composerDefinition;

    /**
     * @var JsonFile
     */
    private $composerJson;

    /**
     * @var PhpCsConfigWriterInterface
     */
    private $phpCsWriter;

    /**
     * @param IOInterface $io
     * @param Composer    $composer
     * @param null|string $projectRoot
     * @param null|string $composerPath
     * @param null|PhpCsConfigWriterInterface $phpCsWriter
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null,
        ?string $composerPath = null,
        ?PhpCsConfigWriterInterface $phpCsWriter = null
    ) {
        $this->io = $io;
        // Get composer.json location
        $composerFile = $composerPath ?? Factory::getComposerFile();
        // Calculate project root from composer.json, if necessary
        $projectRootPath = $projectRoot ?: realpath(\dirname($composerFile));

        if (! $projectRootPath) {
            throw new \RuntimeException('Unable to get project root.');
        }

        $this->projectRoot = rtrim($projectRootPath, '/\\');

        // Parse the composer.json
        $this->parseComposerDefinition($composerFile);
        $this->phpCsWriter = $phpCsWriter ?: new PhpCsConfigWriter();
    }

    /**
     * @throws \Exception
     */
    public function installCommands(): void
    {
        $this->io->write('<info>Setting up Facile.it Coding Standards</info>');
        $this->requestCreateCsConfig();
        $this->requestAddComposerScripts();
        $this->composerJson->write($this->composerDefinition);
    }

    /**
     * Check if we need to do some upgrades
     *
     * @param PackageInterface $currentPackage
     * @param PackageInterface $targetPackage
     */
    public function checkUpgrade(PackageInterface $currentPackage, PackageInterface $targetPackage): void
    {
        if (! $this->io->isInteractive()) {
            $this->io->write(sprintf("\n  <info>Skipping configuration upgrade due to --no-interactive flag.</info>"));

            return;
        }

        if (false === $this->isBcBreak($currentPackage, $targetPackage)) {
            return;
        }

        $question = [
            '  <error>You are upgrading "' . $currentPackage->getPrettyName() . '" with possible BC breaks.</error>',
            sprintf(
                '  <question>%s</question>',
                'Do you want to write the new configuration? (Y/n)'
            ),
        ];

        $answer = $this->io->askConfirmation(implode("\n", $question), true);

        if (! $answer) {
            return;
        }

        $this->io->write(sprintf("\n  <info>Writing configuration in project root...</info>"));

        $this->phpCsWriter->writeConfigFile($this->projectRoot . '/.php-cs-fixer.dist.php', false, true);
    }

    private function isBcBreak(PackageInterface $currentPackage, PackageInterface $targetPackage): bool
    {
        if ($targetPackage->getFullPrettyVersion() === $currentPackage->getFullPrettyVersion()) {
            return false;
        }

        $constraint = $currentPackage->getVersion();
        if (0 !== strpos($constraint, 'dev-')) {
            $constraint = '^' . $constraint;
        }

        if ($targetPackage->getVersion() && Semver::satisfies($targetPackage->getVersion(), $constraint)) {
            // it needs an immediate semver-compliant upgrade
            return false;
        }

        // it needs an upgrade but has potential BC breaks so is not urgent
        return true;
    }

    /**
     * @param PhpCsConfigWriterInterface $phpCsWriter
     */
    public function setPhpCsWriter(PhpCsConfigWriterInterface $phpCsWriter): void
    {
        $this->phpCsWriter = $phpCsWriter;
    }

    /**
     * @param string   $composerFile
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function parseComposerDefinition(string $composerFile): void
    {
        $this->composerJson = new JsonFile($composerFile);
        /** @var array<string, mixed> $definition */
        $definition = $this->composerJson->read();
        $this->composerDefinition = $definition;
    }

    public function requestCreateCsConfig(): void
    {
        $destPath = $this->projectRoot . '/.php-cs-fixer.dist.php';

        if (file_exists($destPath)) {
            $this->io->write(sprintf("\n  <comment>Skipping... CS config file already exists.</comment>"));
            $this->io->write(sprintf('  <info>Delete .php-cs-fixer.dist.php if you want to install it.</info>'));

            return;
        }

        $question = [
            sprintf(
                "  <question>%s</question>\n",
                'Do you want to create the CS configuration in your project root? (Y/n)'
            ),
            '  <info>It will create a .php-cs-fixer.dist.php file in your project root directory.</info> ',
        ];

        $answer = $this->io->askConfirmation(implode("\n", $question), true);

        if (! $answer) {
            return;
        }

        $this->io->write(sprintf("\n  <info>Writing configuration in project root...</info>"));

        $this->phpCsWriter->writeConfigFile($this->projectRoot . '/.php-cs-fixer.dist.php', false, true);
    }

    public function requestAddComposerScripts(): void
    {
        $scripts = [
            'cs-check' => 'php-cs-fixer fix --dry-run --diff',
            'cs-fix' => 'php-cs-fixer fix --diff',
        ];

        /** @var mixed $scriptsDefinition */
        $scriptsDefinition = $this->composerDefinition['scripts'] ?? [];

        if (\is_array($scriptsDefinition) && 0 === \count(array_diff_key($scripts, $scriptsDefinition))) {
            $this->io->write(sprintf("\n  <comment>Skipping... Scripts already exist in composer.json.</comment>"));

            return;
        }

        $question = [
            sprintf(
                "  <question>%s</question>\n",
                'Do you want to add scripts to composer.json? (Y/n)'
            ),
            '  <info>It will add two scripts:</info>',
            '  - <info>cs-check</info>',
            '  - <info>cs-fix</info>',
            'Answer: ',
        ];

        $answer = $this->io->askConfirmation(implode("\n", $question), true);

        if (! $answer) {
            return;
        }

        if (! \array_key_exists('scripts', $this->composerDefinition)) {
            $this->composerDefinition['scripts'] = [];
        }

        foreach ($scripts as $key => $command) {
            if (isset($this->composerDefinition['scripts'][$key]) && $this->composerDefinition['scripts'][$key] !== $command) {
                $this->io->write([
                    sprintf('  <error>Another script "%s" exists!</error>', $key),
                    '  If you want, you can replace it manually with:',
                    sprintf("\n  <comment>\"%s\": \"%s\"</comment>", $key, $command),
                ]);
                continue;
            }

            $this->addComposerScript($key, $command);
        }
    }

    /**
     * @param string $composerCommand
     * @param string $command
     */
    protected function addComposerScript(string $composerCommand, string $command): void
    {
        /** @var array<string, mixed> $scripts */
        $scripts = $this->composerDefinition['scripts'] ?? [];

        $scripts[$composerCommand] = $command;

        $this->composerDefinition['scripts'] = $scripts;
    }
}
