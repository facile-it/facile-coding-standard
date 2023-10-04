<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class RiskyRulesProvider extends AbstractRuleProvider
{
    public function getRules(): array
    {
        return $this->filterRules([
            '@PER-CS2.0:risky' => true,
            'dir_constant' => true,
            'function_to_constant' => true,
            'is_null' => true,
            'logical_operators' => true,
            'modernize_types_casting' => true,
            'native_constant_invocation' => true,
            'native_function_invocation' => true,
            'no_alias_functions' => true,
            'no_homoglyph_names' => true,
            'no_php4_constructor' => true,
            'non_printable_character' => true,
            'php_unit_construct' => true,
            'php_unit_dedicate_assert' => true,
            'php_unit_mock' => true,
            'php_unit_namespaced' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'pow_to_exponentiation' => true,
            'psr_autoloading' => true,
            'random_api_migration' => true,
            'self_accessor' => true,
            'set_type_to_cast' => true,
            'void_return' => true,
        ]);
    }
}
