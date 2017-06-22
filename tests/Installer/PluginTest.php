<?php

namespace Facile\CodingStandardsTest\Installer;

use Composer\Composer;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Facile\CodingStandards\Installer\Installer;
use Facile\CodingStandards\Installer\Plugin;
use PHPUnit\Framework\TestCase;
use Composer\DependencyResolver\Operation\InstallOperation;

class PluginTest extends TestCase
{
    const PACKAGE_NAME = 'facile-it/facile-coding-standard';

    public function testGetPackageName()
    {
        $packageName = Plugin::getPackageName();
        $this->assertEquals(self::PACKAGE_NAME, $packageName);
    }

    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin();
        $events = Plugin::getSubscribedEvents();

        $this->assertCount(2, $events);

        $this->assertArrayHasKey(PackageEvents::POST_PACKAGE_INSTALL, $events);
        $this->assertArrayHasKey(PackageEvents::POST_PACKAGE_UPDATE, $events);

        $this->assertTrue(method_exists($plugin, $events[PackageEvents::POST_PACKAGE_INSTALL]));
        $this->assertTrue(method_exists($plugin, $events[PackageEvents::POST_PACKAGE_UPDATE]));
    }

    public function testGetInstallerWithNew()
    {
        $plugin = new Plugin();
        $composerMock = $this->prophesize(Composer::class);
        $ioMock = $this->prophesize(IOInterface::class);
        $packageMock = $this->prophesize(PackageInterface::class);
        $composerMock->getPackage()->willReturn($packageMock);
        $packageMock->getAutoload()->willReturn([]);

        $installer = $plugin->getInstaller($composerMock->reveal(), $ioMock->reveal());

        $this->assertInstanceOf(Installer::class, $installer);
    }

    public function testGetInstallerAfterSetter()
    {
        $composerMock = $this->prophesize(Composer::class);
        $ioMock = $this->prophesize(IOInterface::class);
        $installerMock = $this->prophesize(Installer::class);

        $plugin = new Plugin(null, $installerMock->reveal());

        $installer = $plugin->getInstaller($composerMock->reveal(), $ioMock->reveal());

        $this->assertInstanceOf(Installer::class, $installer);
        $this->assertSame($installerMock->reveal(), $installer);
    }

    /**
     * @depends testGetPackageName
     */
    public function testOnPostPackageInstall()
    {
        $eventMock = $this->prophesize(PackageEvent::class);
        $operationMock = $this->prophesize(InstallOperation::class);
        $packageMock = $this->prophesize(PackageInterface::class);
        $installerMock = $this->prophesize(Installer::class);
        $composerMock = $this->prophesize(Composer::class);
        $ioMock = $this->prophesize(IOInterface::class);

        $eventMock->getOperation()->willReturn($operationMock->reveal());
        $eventMock->isDevMode()->willReturn(true);
        $eventMock->getComposer()->willReturn($composerMock->reveal());
        $eventMock->getIO()->willReturn($ioMock->reveal());
        $operationMock->getPackage()->willReturn($packageMock->reveal());
        $packageMock->getName()->willReturn(self::PACKAGE_NAME);

        $plugin = new Plugin(null, $installerMock->reveal());
        $installerMock->installCommands()->shouldBeCalled();

        $plugin->onPostPackageInstall($eventMock->reveal());
    }

    /**
     * @depends testGetPackageName
     */
    public function testOnPostPackageInstallInNoDevMode()
    {
        $eventMock = $this->prophesize(PackageEvent::class);
        $operationMock = $this->prophesize(InstallOperation::class);
        $installerMock = $this->prophesize(Installer::class);

        $eventMock->getOperation()->willReturn($operationMock->reveal());
        $eventMock->isDevMode()->willReturn(false);
        $installerMock->installCommands()->shouldNotBeCalled();

        $plugin = new Plugin(null, $installerMock->reveal());

        $plugin->onPostPackageInstall($eventMock->reveal());
    }

    /**
     * @depends testGetPackageName
     */
    public function testOnPostPackageInstallWithAnotherPackage()
    {
        $eventMock = $this->prophesize(PackageEvent::class);
        $operationMock = $this->prophesize(InstallOperation::class);
        $packageMock = $this->prophesize(PackageInterface::class);
        $installerMock = $this->prophesize(Installer::class);
        $composerMock = $this->prophesize(Composer::class);
        $ioMock = $this->prophesize(IOInterface::class);

        $eventMock->getOperation()->willReturn($operationMock->reveal());
        $eventMock->isDevMode()->willReturn(true);
        $eventMock->getComposer()->willReturn($composerMock->reveal());
        $eventMock->getIO()->willReturn($ioMock->reveal());
        $operationMock->getPackage()->willReturn($packageMock->reveal());
        $packageMock->getName()->shouldBeCalled()->willReturn('foo');
        $installerMock->installCommands()->shouldNotBeCalled();

        $plugin = new Plugin(null, $installerMock->reveal());
        $plugin->onPostPackageInstall($eventMock->reveal());
    }
}