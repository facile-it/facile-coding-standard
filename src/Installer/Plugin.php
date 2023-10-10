<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 * Facile coding standards installer.
 */
class Plugin implements EventSubscriberInterface, PluginInterface, Capable
{
    /**
     * @var null|Installer
     */
    private $installer;

    /**
     * Constructor.
     *
     * Optionally accept the project root into which to install.
     */
    public function __construct(Installer $installer = null)
    {
        $this->installer = $installer;
    }

    /**
     * Return this package name.
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function getPackageName(): string
    {
        $composerJson = new JsonFile(\dirname(__DIR__, 2) . '/composer.json');
        /** @var array{name: string} $composerDefinition */
        $composerDefinition = $composerJson->read();

        return $composerDefinition['name'];
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     * * The method name to call (priority defaults to 0)
     * * An array composed of the method name to call and the priority
     * * An array of arrays composed of the method names to call and respective
     *   priorities, or 0 if unset
     *
     * For instance:
     *
     * * array('eventName' => 'methodName')
     * * array('eventName' => array('methodName', $priority))
     * * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array<string, string> The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
            PackageEvents::POST_PACKAGE_UPDATE => 'onPostPackageUpdate',
        ];
    }

    /**
     * Apply plugin modifications to Composer.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function activate(Composer $composer, IOInterface $io): void {}

    /**
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getInstaller(Composer $composer, IOInterface $io): Installer
    {
        if (! $this->installer) {
            $this->installer = new Installer($io, $composer);
        }

        return $this->installer;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function onPostPackageUpdate(PackageEvent $event): void
    {
        if (! $event->isDevMode()) {
            // Do nothing in production mode.
            return;
        }

        $operation = $event->getOperation();

        if (! $operation instanceof UpdateOperation) {
            return;
        }

        $package = $operation->getInitialPackage();
        $name = $package->getName();

        if ($name !== self::getPackageName()) {
            // we are not updating it
            return;
        }

        $installer = $this->getInstaller($event->getComposer(), $event->getIO());

        if (false === method_exists($installer, 'checkUpgrade')) {
            // it's an old version
            return;
        }

        $installer->checkUpgrade($operation->getInitialPackage(), $operation->getTargetPackage());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function onPostPackageInstall(PackageEvent $event): void
    {
        if (! $event->isDevMode()) {
            // Do nothing in production mode.
            return;
        }

        $operation = $event->getOperation();

        if (! $operation instanceof InstallOperation) {
            return;
        }

        $package = $operation->getPackage();
        $name = $package->getName();

        if ($name !== self::getPackageName()) {
            // we are not installing it
            return;
        }

        $installer = $this->getInstaller($event->getComposer(), $event->getIO());
        $installer->installCommands();
    }

    /**
     * Method by which a Plugin announces its API implementations, through an array
     * with a special structure.
     *
     * The key must be a string, representing a fully qualified class/interface name
     * which Composer Plugin API exposes.
     * The value must be a string as well, representing the fully qualified class name
     * of the implementing class.
     *
     * @tutorial
     *
     * return array(
     *     'Composer\Plugin\Capability\CommandProvider' => 'My\CommandProvider',
     *     'Composer\Plugin\Capability\Validator'       => 'My\Validator',
     * );
     *
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return [
            \Composer\Plugin\Capability\CommandProvider::class => CommandProvider::class,
        ];
    }

    public function deactivate(Composer $composer, IOInterface $io): void {}

    public function uninstall(Composer $composer, IOInterface $io): void {}
}
