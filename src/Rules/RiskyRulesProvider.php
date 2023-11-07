<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class RiskyRulesProvider extends AbstractRuleProvider
{
    public function getRules(): array
    {
        return $this->filterRules([
            '@PER-CS2.0:risky' => true,
            '@PSR12:risky' => true,
            'array_push' => true,
            'combine_nested_dirname' => true,
            'dir_constant' => true,
            'ereg_to_preg' => true,
            'function_to_constant' => true,
            'get_class_to_class_keyword' => true,
            'implode_call' => true,
            'is_null' => true,
            'logical_operators' => true,
            'long_to_shorthand_operator' => true,
            'modernize_strpos' => true,
            'modernize_types_casting' => true,
            'native_constant_invocation' => true,
            'native_function_invocation' => true,
            'no_alias_functions' => true,
            'no_homoglyph_names' => true,
            'no_php4_constructor' => true,
            'no_useless_sprintf' => true,
            'non_printable_character' => true,
            'ordered_traits' => true,
            'php_unit_construct' => true,
            'php_unit_data_provider_static' => true,
            'php_unit_dedicate_assert_internal_type' => true,
            'php_unit_expectation' => true,
            'php_unit_mock_short_will_return' => true,
            'php_unit_dedicate_assert' => true,
            'php_unit_mock' => true,
            'php_unit_namespaced' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'pow_to_exponentiation' => true,
            'psr_autoloading' => true,
            'random_api_migration' => true,
            'self_accessor' => true,
            'set_type_to_cast' => true,
            'ternary_to_elvis_operator' => true,
            'void_return' => true,
        ]);
    }
}
