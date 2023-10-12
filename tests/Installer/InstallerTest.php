<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Facile\CodingStandards\Installer\Installer;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;
use Facile\CodingStandardsTest\Framework\TestCase;
use Facile\CodingStandardsTest\Util;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Prophecy\Argument;

class InstallerTest extends TestCase
{
    /**
     * @var string
     */
    private $composerFilePath;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup();

        $this->projectRoot = $this->vfsRoot->url();
        $this->composerFilePath = $this->vfsRoot->url() . '/composer.json';
        file_put_contents($this->composerFilePath, Util::getComposerContent());
    }

    /**
     * @return array{array{string, string}, array{string, string}}[]
     */
    public static function invalidUpgradeProvider(): array
    {
        return [
            [['0.1.0.0', '0.1.0'], ['0.1.0.0', '0.1.0']],
            [['0.1.0.0', '0.1.0'], ['0.1.1.0', '0.1.1']],
            [['1.1.0.0', '1.1.0'], ['1.2.0.0', '1.2.0']],
            [['dev-master', 'dev-master'], ['dev-master', 'dev-master']],
            [['dev-master#12345', 'dev-master'], ['dev-master#12345', 'dev-master']],
            [['dev-master#12345', 'dev-master'], ['dev-master#12346', 'dev-master']],
        ];
    }

    /**
     * @return array{array{string, string}, array{string, string}}[]
     */
    public static function validUpgradeProvider(): array
    {
        return [
            [['0.1.0.0', '0.1.0'], ['0.2.0.0', '0.2.0']],
            [['0.1.0.0', '0.1.0'], ['1.0.0.0', '1.0.0']],
            [['1.0.0.0', '1.0.0'], ['2.0.0.0', '2.0.0']],
            [['dev-master', 'dev-master'], ['dev-feature', 'dev-feature']],
        ];
    }

    /**
     * @dataProvider invalidUpgradeProvider
     *
     * @param array{string, string} $currentPackageV
     * @param array{string, string} $targetPackageV
     */
    public function testCheckUpgradeTestNotNecessary(array $currentPackageV, array $targetPackageV): void
    {
        $currentPackage = new Package('dummy', $currentPackageV[0], $currentPackageV[1]);
        $targetPackage = new Package('dummy', $targetPackageV[0], $targetPackageV[1]);

        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $io->isInteractive()
            ->willReturn(true);
        $io->askConfirmation(Argument::cetera())
            ->shouldNotBeCalled();

        $installer->checkUpgrade($currentPackage, $targetPackage);
    }

    /**
     * @dataProvider validUpgradeProvider
     *
     * @param array{string, string} $currentPackageV
     * @param array{string, string} $targetPackageV
     */
    public function testCheckUpgradeTestNecessary(array $currentPackageV, array $targetPackageV): void
    {
        $currentPackage = new Package('dummy', $currentPackageV[0], $currentPackageV[1]);
        $targetPackage = new Package('dummy', $targetPackageV[0], $targetPackageV[1]);

        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $io->isInteractive()
            ->willReturn(true);
        $io->askConfirmation(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(false);

        $installer->checkUpgrade($currentPackage, $targetPackage);
    }

    public function testCheckUpgrade(): void
    {
        $currentPackage = new Package('dummy', '0.1.0', '0.1.0');
        $targetPackage = new Package('dummy', '0.2.0', '0.2.0');

        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $io->isInteractive()
            ->willReturn(true);
        $io->askConfirmation(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(true);

        $io->write(Argument::type('string'))->shouldBeCalled();
        $phpCsWriter->writeConfigFile($this->projectRoot . '/.php-cs-fixer.dist.php', false, true)
            ->shouldBeCalled();

        $installer->checkUpgrade($currentPackage, $targetPackage);
    }

    public function testCheckUpgradeShouldntWriteWithNoInteractiveInput(): void
    {
        $currentPackage = new Package('dummy', '0.1.0', '0.1.0');
        $targetPackage = new Package('dummy', '0.2.0', '0.2.0');

        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $io->isInteractive()
            ->willReturn(false);
        $io->askConfirmation(Argument::cetera())
            ->shouldNotBeCalled();

        $io->write(Argument::containingString('Skip'))->shouldBeCalled();
        $phpCsWriter->writeConfigFile(Argument::cetera())
            ->shouldNotBeCalled();

        $installer->checkUpgrade($currentPackage, $targetPackage);
    }

    public function testRequestCreateCsConfigWithAlreadyExistingFile(): void
    {
        touch($this->projectRoot . '/.php-cs-fixer.dist.php');

        $package = $this->prophesize(PackageInterface::class);
        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $phpCsWriter->writeConfigFile(Argument::cetera())->shouldNotBeCalled();
        $io->write(Argument::any())->shouldBeCalled();
        $io->askConfirmation(Argument::cetera())->shouldNotBeCalled();
        $composer->getPackage()->willReturn($package);
        $package->getAutoload()->willReturn([]);
        $package->getDevAutoload()->willReturn([]);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );
        $installer->requestCreateCsConfig();
    }

    public function testRequestCreateCsConfigWithAnswerNo(): void
    {
        $package = $this->prophesize(PackageInterface::class);
        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $phpCsWriter->writeConfigFile(Argument::any())->shouldNotBeCalled();
        $io->askConfirmation(Argument::any(), true)->shouldBeCalled()->willReturn(false);
        $composer->getPackage()->willReturn($package);
        $package->getAutoload()->willReturn([]);
        $package->getDevAutoload()->willReturn([]);

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $installer->requestCreateCsConfig();
    }

    public function testRequestCreateCsConfig(): void
    {
        $package = $this->prophesize(PackageInterface::class);
        $io = $this->prophesize(IOInterface::class);
        $composer = $this->prophesize(Composer::class);
        $phpCsWriter = $this->prophesize(PhpCsConfigWriterInterface::class);

        $io->write(Argument::any())->shouldBeCalled();
        $io->askConfirmation(Argument::any(), true)->shouldBeCalled()->willReturn(true);
        $composer->getPackage()->willReturn($package);
        $package->getAutoload()->willReturn([]);
        $package->getDevAutoload()->willReturn([]);
        $phpCsWriter->writeConfigFile($this->projectRoot . '/.php-cs-fixer.dist.php', false, true)
            ->shouldBeCalled();

        $installer = new Installer(
            $io->reveal(),
            $composer->reveal(),
            $this->projectRoot,
            $this->composerFilePath,
            $phpCsWriter->reveal()
        );

        $installer->requestCreateCsConfig();
    }
}
