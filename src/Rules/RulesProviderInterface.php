<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

interface RulesProviderInterface
{
    /**
     * Get rules.
     */
    public function getRules(): array;
}
