<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Installer\Provider\SourcePaths;

/**
 * Class ArrayProvider
 */
final class ArrayProvider implements ProviderInterface
{
    /**
     * @var array|ProviderInterface[]
     */
    private $providers = [];

    /**
     * ArrayProvider constructor.
     * @param array|ProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            $this->validateProvider($provider);
        }

        $this->providers = $providers;
    }

    /**
     * Get PHP CS source paths.
     *
     * @return array
     */
    public function getSourcePaths(): array
    {
        $paths = [];

        foreach ($this->providers as $provider) {
            $paths = \array_merge($paths, $provider->getSourcePaths());
        }

        return $paths;
    }

    /**
     * @param mixed $provider
     * @return ProviderInterface
     * @throws \InvalidArgumentException
     */
    private function validateProvider($provider): ProviderInterface
    {
        if (! $provider instanceof ProviderInterface) {
            throw new \InvalidArgumentException('Invalid source path provider provided');
        }

        return $provider;
    }
}
