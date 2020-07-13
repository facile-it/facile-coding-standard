<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

/**
 * Class ArrayRulesProvider
 */
final class ArrayRulesProvider implements RulesProviderInterface
{
    /** @var array */
    private $rules;

    /**
     * ArrayRulesProvider constructor.
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Get rules.
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
