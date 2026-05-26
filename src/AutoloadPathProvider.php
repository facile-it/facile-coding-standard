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

    public function __construct(?string $composerFile = null, ?string $projectRoot = null, bool $dev = true)
    {
        $this->composerPath = $composerFile ?? $this->getComposerFilePath();

        $projectRootPath = $projectRoot ?? realpath(\dirname($this->composerPath));

        if (false === $projectRootPath) {
            throw new \RuntimeException('Unable to get project root.');
        }

        $this->projectRoot = rtrim($projectRootPath, '/\\');
        $this->dev = $dev;
    }

    private function getComposerFilePath(): string
    {
        $path = getenv('COMPOSER');

        if (\is_string($path) && $path !== '') {
            return trim($path);
        }

        return './composer.json';
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        if (! file_exists($this->composerPath)) {
            throw new \RuntimeException('Unable to find composer.json');
        }

        $composerContent = file_get_contents($this->composerPath);
        if (false === $composerContent) {
            throw new \RuntimeException('Unable to read composer.json');
        }

        $composer = json_decode($composerContent, true);
        if (! \is_array($composer)) {
            throw new \RuntimeException('Invalid composer.json file');
        }

        /** @var array<mixed> $autoload */
        $autoload = $composer['autoload'] ?? [];
        $paths = $this->getAutoloadPaths($autoload);

        if ($this->dev) {
            /** @var array<mixed> $autoloadDev */
            $autoloadDev = $composer['autoload-dev'] ?? [];
            $paths = array_merge($paths, $this->getAutoloadPaths($autoloadDev));
        }

        return $paths;
    }

    /**
     * @param array<mixed> $autoload
     *
     * @return array<string>
     */
    private function getAutoloadPaths(array $autoload): array
    {
        $keys = ['psr-0', 'psr-4', 'classmap'];
        $autoloads = array_intersect_key($autoload, array_flip($keys));

        $autoloadPaths = $this->reduceAutoload($autoloads);

        $autoloadPaths = array_filter($autoloadPaths, function (string $path): bool {
            return is_dir($this->projectRoot . \DIRECTORY_SEPARATOR . $path);
        });

        return $autoloadPaths;
    }

    /**
     * @param array<mixed> $autoload
     *
     * @return array<string>
     */
    private function reduceAutoload(array $autoload): array
    {
        return array_reduce(
            $autoload,
            \Closure::fromCallable([$this, 'autoloadReducer']),
            [],
        );
    }

    /**
     * @param array<string> $carry
     * @param array<mixed>|string $item
     *
     * @return array<string>
     */
    private function autoloadReducer(array $carry, $item): array
    {
        if (\is_array($item)) {
            return array_merge($carry, $this->reduceAutoload($item));
        }

        return array_merge($carry, [$item]);
    }
}
