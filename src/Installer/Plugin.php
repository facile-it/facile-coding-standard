<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Plugin\PluginInterface;

/**
 * Facile coding standards installer.
 */
class Plugin implements EventSubscriberInterface, PluginInterface
{
    /**
     * @var Installer
     */
    private $installer;

    /**
     * Constructor.
     *
     * Optionally accept the project root into which to install.
     *
     * @param Installer $installer
     */
    public function __construct(Installer $installer = null)
    {
        $this->installer = $installer;
    }

    /**
     * Return this package name.
     *
     * @return string
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function getPackageName(): string
    {
        $composerJson = new JsonFile(\dirname(__DIR__, 2) . '/composer.json');
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
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
            PackageEvents::POST_PACKAGE_UPDATE => 'onPostPackageInstall',
        ];
    }

    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return Installer
     *
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
     * @param PackageEvent $event
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function onPostPackageInstall(PackageEvent $event)
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
}
