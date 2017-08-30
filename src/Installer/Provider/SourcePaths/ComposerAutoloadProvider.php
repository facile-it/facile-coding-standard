<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Provider\SourcePaths;

/**
 * Class ComposerAutoloadSourcePathsProvider.
 */
final class ComposerAutoloadProvider implements ProviderInterface
{
    /**
     * @var array
     */
    private $autoload;

    /**
     * ComposerAutoloadProvider constructor.
     *
     * @param array $autoload
     */
    public function __construct(array $autoload)
    {
        $this->autoload = $autoload;
    }

    /**
     * Get PHP CS source paths.
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    public function getSourcePaths(): array
    {
        $paths = [];
        $allowedAutoloads = ['psr-0', 'psr-4'];
        foreach ($this->autoload as $autoloadType => $autoload) {
            if (! in_array($autoloadType, $allowedAutoloads, true)) {
                continue;
            }

            if (! is_array($autoload)) {
                throw new \RuntimeException('Your composer autoloader section does not contain an array!');
            }

            foreach ($autoload as $autoloadPaths) {
                if (! is_array($autoloadPaths)) {
                    $autoloadPaths = [$autoloadPaths];
                }
                foreach ($autoloadPaths as $path) {
                    if (in_array($path, $paths, true)) {
                        continue;
                    }

                    $paths[] = $path;
                }
            }
        }

        return $paths;
    }
}
