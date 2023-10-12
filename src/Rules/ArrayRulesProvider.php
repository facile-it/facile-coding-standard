<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class ArrayRulesProvider implements RulesProviderInterface
{
    /**
     * @var array<string, array<string, mixed>|bool>
     */
    private array $rules;

    /**
     * @param array<string, array<string, mixed>|bool> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
