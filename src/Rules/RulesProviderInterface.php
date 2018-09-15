<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

interface RulesProviderInterface
{
    /**
     * Get rules.
     *
     * @return array
     */
    public function getRules(): array;
}
