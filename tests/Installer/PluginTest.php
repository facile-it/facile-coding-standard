<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Facile\CodingStandards\Installer\CommandProvider;
use Facile\CodingStandards\Installer\Installer;
use Facile\CodingStandards\Installer\Plugin;
use Facile\CodingStandardsTest\Framework\TestCase;
use Prophecy\Argument;

class PluginTest extends TestCase
{
    private const PACKAGE_NAME = 'facile-it/facile-coding-standard';

    public function testGetPackageName(): void
    {
        $packageName = Plugin::getPackageName();
        $this->assertEquals(self::PACKAGE_NAME, $packageName);
    }

    public function testGetSubscribedEvents(): void
    {
        $plugin = new Plugin();
        $this->assertInstanceOf(PluginInterface::class, $plugin);
        $this->assertInstanceOf(EventSubscriberInterface::class, $plugin);
        $events = Plugin::getSubscribedEvents();

        $this->assertCount(2, $events);

        $this->assertArrayHasKey(PackageEvents::POST_PACKAGE_INSTALL, $events);
        $this->assertArrayHasKey(PackageEvents::POST_PACKAGE_UPDATE, $events);

        $this->assertTrue(method_exists($plugin, $events[PackageEvents::POST_PACKAGE_INSTALL]));
        $this->assertTrue(method_exists($plugin, $events[PackageEvents::POST_PACKAGE_UPDATE]));
    }

    public function testActive(): void
    {
        $plugin = new Plugin();

        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $plugin->activate($composer->reveal(), $io->reveal());

        // assert no exceptions
        $this->assertTrue(true);
    }

    public function testGetInstallerAfterSetter(): void
    {
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);
        $installer = $this->prophesize(Installer::class);

        $plugin = new Plugin($installer->reveal());

        $installerInstance = $plugin->getInstaller($composer->reveal(), $io->reveal());

        $this->assertInstanceOf(Installer::class, $installerInstance);
        $this->assertSame($installer->reveal(), $installerInstance);
    }

    public function testOnPostPackageUpdate(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(UpdateOperation::class);
        $package = $this->prophesize(PackageInterface::class);
        $targetPackage = $this->prophesize(PackageInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $operation->getInitialPackage()->willReturn($package->reveal());
        $operation->getTargetPackage()->willReturn($targetPackage->reveal());
        $package->getName()->willReturn(self::PACKAGE_NAME);

        $plugin = new Plugin($installer->reveal());
        $installer->checkUpgrade($package, $targetPackage)->shouldBeCalled();

        $plugin->onPostPackageUpdate($event->reveal());
    }

    public function testOnPostPackageUpdateInNoDevMode(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(UpdateOperation::class);
        $installer = $this->prophesize(Installer::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(false);
        $installer->checkUpgrade(Argument::cetera())->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());

        $plugin->onPostPackageUpdate($event->reveal());
    }

    public function testOnPostPackageUpdateWithAnotherOperation(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(OperationInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $installer->checkUpgrade(Argument::cetera())->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());
        $plugin->onPostPackageUpdate($event->reveal());
    }

    public function testOnPostPackageUpdateWithAnotherPackage(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(UpdateOperation::class);
        $package = $this->prophesize(PackageInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $operation->getInitialPackage()->willReturn($package->reveal());
        $package->getName()->shouldBeCalled()->willReturn('foo');
        $installer->checkUpgrade(Argument::cetera())->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());
        $plugin->onPostPackageUpdate($event->reveal());
    }

    public function testOnPostPackageInstall(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(InstallOperation::class);
        $package = $this->prophesize(PackageInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $operation->getPackage()->willReturn($package->reveal());
        $package->getName()->willReturn(self::PACKAGE_NAME);

        $plugin = new Plugin($installer->reveal());
        $installer->installCommands()->shouldBeCalled();

        $plugin->onPostPackageInstall($event->reveal());
    }

    public function testOnPostPackageInstallInNoDevMode(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(InstallOperation::class);
        $installer = $this->prophesize(Installer::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(false);
        $installer->installCommands()->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());

        $plugin->onPostPackageInstall($event->reveal());
    }

    public function testOnPostPackageInstallWithAnotherOperation(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(OperationInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $installer->installCommands()->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());
        $plugin->onPostPackageInstall($event->reveal());
    }

    public function testOnPostPackageInstallWithAnotherPackage(): void
    {
        $event = $this->prophesize(PackageEvent::class);
        $operation = $this->prophesize(InstallOperation::class);
        $package = $this->prophesize(PackageInterface::class);
        $installer = $this->prophesize(Installer::class);
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);

        $event->getOperation()->willReturn($operation->reveal());
        $event->isDevMode()->willReturn(true);
        $event->getComposer()->willReturn($composer->reveal());
        $event->getIO()->willReturn($io->reveal());
        $operation->getPackage()->willReturn($package->reveal());
        $package->getName()->shouldBeCalled()->willReturn('foo');
        $installer->installCommands()->shouldNotBeCalled();

        $plugin = new Plugin($installer->reveal());
        $plugin->onPostPackageInstall($event->reveal());
    }

    public function testCapabilities(): void
    {
        $plugin = new Plugin();

        $this->assertInstanceOf(Capable::class, $plugin);

        $capabilities = $plugin->getCapabilities();

        $this->assertArrayHasKey(\Composer\Plugin\Capability\CommandProvider::class, $capabilities);
        $this->assertSame(CommandProvider::class, $capabilities[\Composer\Plugin\Capability\CommandProvider::class]);
    }
}
