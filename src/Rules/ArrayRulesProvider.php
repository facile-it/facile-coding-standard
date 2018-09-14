<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class ArrayRulesProvider implements RulesProviderInterface
{
    /**
     * @var array
     */
    private $rules;

    /**
     * ArrayRulesProvider constructor.
     *
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Get rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
