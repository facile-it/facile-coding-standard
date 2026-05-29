<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class ArrayRulesProvider implements RulesProviderInterface
{
    /**
     * @param array<string, array<string, mixed>|bool> $rules
     */
    public function __construct(
        private readonly array $rules,
    ) {}

    public function getRules(): array
    {
        return $this->rules;
    }
}
