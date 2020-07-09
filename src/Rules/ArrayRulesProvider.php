<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

/**
 * Class ArrayRulesProvider
 */
final class ArrayRulesProvider implements RulesProviderInterface
{
    /**
     * @var array<string, mixed>
     */
    private $rules;

    /**
     * ArrayRulesProvider constructor.
     *
     * @param array<string, mixed> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Get rules.
     *
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
