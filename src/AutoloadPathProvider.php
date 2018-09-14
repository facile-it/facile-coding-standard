<?php

declare(strict_types=1);

namespace Facile\CodingStandards;

class AutoloadPathProvider
{
    /**
     * @var string
     */
    private $composerPath;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var bool
     */
    private $dev;

    /**
     * AutoloadPathProvider constructor.
     * @param null|string $composerFile
     * @param null|string $projectRoot
     * @param bool $dev
     */
    public function __construct(?string $composerFile = null, ?string $projectRoot = null, bool $dev = true)
    {
        $this->composerPath = $composerFile ?: trim(getenv('COMPOSER')) ?: './composer.json';
        $this->projectRoot = $projectRoot ?: realpath(\dirname($this->composerPath));
        $this->projectRoot = rtrim($this->projectRoot, '/\\');
        $this->dev = $dev;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        if (! \file_exists($this->composerPath)) {
            throw new \RuntimeException('Unable to find composer.json');
        }

        $composer = \json_decode(\file_get_contents($this->composerPath), true);

        if (! \is_array($composer)) {
            throw new \RuntimeException('Invalid composer.json file');
        }

        $paths = $this->getAutoloadPaths($composer['autoload'] ?? []);

        if ($this->dev) {
            $paths = \array_merge($paths, $this->getAutoloadPaths($composer['autoload-dev'] ?? []));
        }

        return $paths;
    }

    private function getAutoloadPaths(array $autoload): array
    {
        $keys = ['psr-0', 'psr-4', 'classmap'];
        $autoload = \array_intersect_key($autoload, \array_flip($keys));

        $autoloadPaths = [];
        foreach ($autoload as $paths) {
            if (\is_array($paths)) {
                $autoloadPaths = \array_merge($autoloadPaths, \array_values($paths));
            } elseif (\is_string($paths)) {
                $autoloadPaths[] = $paths;
            }
        }

        $autoloadPaths = \array_filter($autoloadPaths, function (string $path) {
            return \is_dir($this->projectRoot . \DIRECTORY_SEPARATOR . $path);
        });

        return $autoloadPaths;
    }
}
