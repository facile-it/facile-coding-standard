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
     *
     * @param null|string $composerFile
     * @param null|string $projectRoot
     * @param bool $dev
     */
    public function __construct(?string $composerFile = null, ?string $projectRoot = null, bool $dev = true)
    {
        $this->composerPath = $composerFile ?: \trim(\getenv('COMPOSER') ?: '') ?: './composer.json';

        $this->projectRoot = $projectRoot ?: \realpath(\dirname($this->composerPath));

        if (false === $this->projectRoot) {
            throw new \RuntimeException('Unable to get project root.');
        }

        $this->projectRoot = \rtrim($this->projectRoot, '/\\');
        $this->dev = $dev;
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        if (! \file_exists($this->composerPath)) {
            throw new \RuntimeException('Unable to find composer.json');
        }

        $composerContent = \file_get_contents($this->composerPath);
        if (false === $composerContent) {
            throw new \RuntimeException('Unable to read composer.json');
        }

        $composer = \json_decode($composerContent, true);
        if (! \is_array($composer)) {
            throw new \RuntimeException('Invalid composer.json file');
        }

        $paths = $this->getAutoloadPaths($composer['autoload'] ?? []);

        if ($this->dev) {
            $paths = \array_merge($paths, $this->getAutoloadPaths($composer['autoload-dev'] ?? []));
        }

        return $paths;
    }

    /**
     * @param array<mixed> $autoload
     *
     * @return string[]
     */
    private function getAutoloadPaths(array $autoload): array
    {
        $keys = ['psr-0', 'psr-4', 'classmap'];
        $autoloads = \array_intersect_key($autoload, \array_flip($keys));

        $autoloadPaths = $this->reduceAutoload($autoloads);

        $autoloadPaths = \array_filter($autoloadPaths, function (string $path) {
            return \is_dir($this->projectRoot . \DIRECTORY_SEPARATOR . $path);
        });

        return $autoloadPaths;
    }

    /**
     * @param array<mixed> $autoload
     *
     * @return string[]
     */
    private function reduceAutoload(array $autoload): array
    {
        return \array_reduce(
            $autoload,
            \Closure::fromCallable([$this, 'autoloadReducer']),
            []
        );
    }

    /**
     * @param array<mixed> $carry
     * @param array<mixed>|string $item
     *
     * @return string[]
     */
    private function autoloadReducer(array $carry, $item): array
    {
        if (\is_array($item)) {
            return \array_merge($carry, $this->reduceAutoload($item));
        }

        return \array_merge($carry, [$item]);
    }
}
