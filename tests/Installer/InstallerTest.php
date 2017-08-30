<?php

namespace Facile\CodingStandardsTest\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Facile\CodingStandards\Installer\Installer;
use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriterInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class InstallerTest extends TestCase
{
    /**
     * @var string
     */
    private $composerFilePath;
    /**
     * @var
     */
    private $projectRoot;

    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    protected function setUp()
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup();

        $this->projectRoot = $this->vfsRoot->url();
        $this->composerFilePath = $this->vfsRoot->url() . '/composer.json';
        copy(__DIR__ . '/../data/config/composer.json', $this->composerFilePath);
    }

    public function testRequestCreateCsConfigWithAlreadyExistingFile()
    {
        touch($this->projectRoot . '/.php_cs.dist');

        $packageMock = $this->prophesize(PackageInterface::class);
        $ioMock = $this->prophesize(IOInterface::class);
        $composerMock = $this->prophesize(Composer::class);
        $phpCsWriterMock = $this->prophesize(PhpCsConfigWriterInterface::class);

        $phpCsWriterMock->writeConfigFile(Argument::any())->shouldNotBeCalled();
        $ioMock->write(Argument::any())->shouldBeCalled();
        $ioMock->askConfirmation(Argument::cetera())->shouldNotBeCalled();
        $composerMock->getPackage()->willReturn($packageMock);
        $packageMock->getAutoload()->willReturn([]);

        $installer = new Installer(
            $ioMock->reveal(),
            $composerMock->reveal(),
            $this->projectRoot,
            $this->composerFilePath
        );
        $installer->setPhpCsWriter($phpCsWriterMock->reveal());
        $installer->requestCreateCsConfig();
    }

    public function testRequestCreateCsConfigWithAnswerNo()
    {
        $packageMock = $this->prophesize(PackageInterface::class);
        $ioMock = $this->prophesize(IOInterface::class);
        $composerMock = $this->prophesize(Composer::class);
        $phpCsWriterMock = $this->prophesize(PhpCsConfigWriterInterface::class);

        $phpCsWriterMock->writeConfigFile(Argument::any())->shouldNotBeCalled();
        $ioMock->askConfirmation(Argument::any(), true)->shouldBeCalled()->willReturn(false);
        $composerMock->getPackage()->willReturn($packageMock);
        $packageMock->getAutoload()->willReturn([]);

        $installer = new Installer(
            $ioMock->reveal(),
            $composerMock->reveal(),
            $this->projectRoot,
            $this->composerFilePath
        );
        $installer->setPhpCsWriter($phpCsWriterMock->reveal());

        $installer->requestCreateCsConfig();
    }

    public function testRequestCreateCsConfig()
    {
        $packageMock = $this->prophesize(PackageInterface::class);
        $ioMock = $this->prophesize(IOInterface::class);
        $composerMock = $this->prophesize(Composer::class);
        $phpCsWriterMock = $this->prophesize(PhpCsConfigWriterInterface::class);

        $ioMock->write(Argument::any())->shouldBeCalled();
        $ioMock->askConfirmation(Argument::any(), true)->shouldBeCalled()->willReturn(true);
        $composerMock->getPackage()->willReturn($packageMock);
        $packageMock->getAutoload()->willReturn([]);
        $phpCsWriterMock->writeConfigFile($this->projectRoot . '/.php_cs.dist')->shouldBeCalled();

        $installer = new Installer(
            $ioMock->reveal(),
            $composerMock->reveal(),
            $this->projectRoot,
            $this->composerFilePath
        );
        $installer->setPhpCsWriter($phpCsWriterMock->reveal());

        $installer->requestCreateCsConfig();
    }
}
