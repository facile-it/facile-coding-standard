<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class CompositeRulesProvider implements RulesProviderInterface
{
    /**
     * @var RulesProviderInterface[]
     */
    private $providers = [];

    /**
     * CompositeRulesProvider constructor.
     * @param iterable|RulesProviderInterface[] $providers
     */
    public function __construct(iterable $providers)
    {
        foreach ($providers as $provider) {
            $this->addRulesProvider($provider);
        }
    }

    private function addRulesProvider(RulesProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];

        foreach ($this->providers as $provider) {
            $rules = \array_merge($rules, $provider->getRules());
        }

        return $rules;
    }
}
