<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

/**
 * Class RiskyRulesProvider.
 */
final class RiskyRulesProvider implements RulesProviderInterface
{
    /**
     * @var array
     */
    private static $rules = [
        'dir_constant' => true,
        'ereg_to_preg' => true,
        'function_to_constant' => true,
        'is_null' => true,
        'modernize_types_casting' => true,
        'native_function_invocation' => true,
        'no_php4_constructor' => true,
        'non_printable_character' => true,
        'php_unit_construct' => true,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'void_return' => true,
    ];

    /**
     * Get default rules.
     *
     * @return array
     */
    public function getRules(): array
    {
        return static::$rules;
    }
}
