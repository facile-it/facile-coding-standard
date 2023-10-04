<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

/**
 * This is a class that verifies the ruleset against all the existing rules in PHP-CS-Fixer; it is useful to keep tabs on new
 * rules, and keep a todo-list of new rules that we want to adopt.
 *
 * All the private methods at the top of this class
 */
class RulesList
{
    /**
     * This method returns all rules that we're mapping (past, current and future).
     * The list is subdivided in submethods depending on the current status.
     * The rule name is in the key of the generator to cause failure in case of duplicates.
     *
     * @return list<string>
     */
    public static function getAllMappedRules(): array
    {
        return iterator_to_array(self::joinAllMappedRules(), false);
    }

    /**
     * @return \Generator<string>
     */
    private static function joinAllMappedRules(): \Generator
    {
        yield from self::getToBeImplementedRules();
        yield from self::getToBeDiscussedRules();
        yield from self::getUndesirableRules();
        yield from self::getUnapplicableRules();
        yield from self::getBestHandledWithRectorRules();
        yield from self::getTooRiskyRules();
        yield from self::getDeprecatedRules();
    }

    /**
     * These are desirable rules, a todo-list for this library
     *
     * @return string[]
     */
    public static function getToBeImplementedRules(): array
    {
        return [
            'array_push',
            'assign_null_coalescing_to_coalesce_equal',
            'backtick_to_shell_exec',
            'blank_lines_before_namespace',
            'class_reference_name_casing',
            'combine_consecutive_issets',
            'combine_consecutive_unsets',
            'combine_nested_dirname',
            'curly_braces_position',
            'declare_parentheses',
            'empty_loop_body',
            'ereg_to_preg',
            'get_class_to_class_keyword',
            'implode_call',
            'integer_literal_case',
            'lambda_not_used_import',
            'linebreak_after_opening_tag',
            'list_syntax',
            'long_to_shorthand_operator',
            'lowercase_static_reference',
            'magic_constant_casing',
            'magic_method_casing',
            'modernize_strpos',
            'native_function_type_declaration_casing',
            'native_type_declaration_casing',
            'no_alternative_syntax',
            'no_blank_lines_after_class_opening',
            'no_homoglyph_names',
            'no_superfluous_elseif',
            'no_superfluous_phpdoc_tags',
            'no_trailing_comma_in_singleline',
            'no_trailing_comma_in_singleline_function_call',
            'no_unneeded_braces',
            'no_unneeded_curly_braces', // with namespaces => false
            'no_unneeded_import_alias',
            'no_unset_cast',
            'no_useless_else',
            'no_useless_sprintf',
            'nullable_type_declaration',
            'octal_notation',
            'ordered_traits',
            'php_unit_data_provider_static', // with force => false
            'php_unit_dedicate_assert_internal_type',
            'php_unit_expectation',
            'php_unit_fqcn_annotation',
            'php_unit_mock_short_will_return',
            'phpdoc_align', // with left align
            'phpdoc_no_alias_tag',
            'phpdoc_tag_casing',
            'phpdoc_trim_consecutive_blank_line_separation',
            'phpdoc_var_annotation_correct_order',
            'return_to_yield_from',
            'semicolon_after_instruction',
            'set_type_to_cast',
            'single_line_comment_spacing',
            'single_line_empty_body',
            'single_space_around_construct',
            'single_trait_insert_per_statement',
            'switch_continue_to_break',
            'ternary_to_elvis_operator',
            'type_declaration_spaces',
            'types_spaces',
        ];
    }

    /**
     * There are rules on which we're not sure about; newly implemented rules in PHP-CS-Fixer should go here,
     * until approved in a new release (or rejected and moved in another of the following lists)
     *
     * @return string[]
     */
    public static function getToBeDiscussedRules(): array
    {
        return [
            'attribute_empty_parentheses',
            'echo_tag_syntax',
            'escape_implicit_backslashes',
            'explicit_indirect_variable',
            'explicit_string_variable',
            'global_namespace_import',
            'heredoc_indentation',
            'heredoc_to_nowdoc',
            'multiline_whitespace_before_semicolons', // with new_line_for_chained_calls
            'no_useless_concat_operator',
            'operator_linebreak',
            'ordered_types',
            'php_unit_method_casing',
            'php_unit_test_case_static_method_calls',
            'phpdoc_inline_tag_normalizer',
            'phpdoc_param_order',
            'phpdoc_summary',
            'phpdoc_tag_type',
            'self_static_accessor',
            'single_line_comment_style',
        ];
    }

    /**
     * These rules are NOT desirable, either because they go against other rules,
     * or because we do not like them.
     *
     * @return string[]
     */
    public static function getUndesirableRules(): array
    {
        return [
            'blank_line_between_import_groups',
            'braces', // deprecated
            'class_keyword_remove',
            'empty_loop_condition',
            'final_internal_class',
            'final_public_method_for_abstract_class',
            'fully_qualified_strict_types',
            'general_phpdoc_annotation_remove',
            'group_import',
            'no_alias_language_construct_call',
            'no_binary_string',
            'no_null_property_initialization',
            'no_spaces_inside_parenthesis', // deprecated
            'no_unneeded_final_method',
            'no_useless_return',
            'not_operator_with_space',
            'ordered_class_elements',
            'php_unit_data_provider_return_type',
            'php_unit_internal_class',
            'php_unit_size_class',
            'php_unit_test_class_requires_covers',
            'phpdoc_add_missing_param_annotation',
            'phpdoc_line_span',
            'phpdoc_order_by_value',
            'phpdoc_to_comment', // disabled in 0.5.3
            'phpdoc_types_order',
            'simple_to_complex_string_variable',
            'single_line_throw',
            'single_space_after_construct',
            'standardize_increment',
            'yield_from_array_to_yields',
            'yoda_style',
        ];
    }

    /**
     * These rules are not applicable, because they are not useful in private projects or in a general ruleset like
     *
     * @return string[]
     */
    public static function getUnapplicableRules(): array
    {
        return [
            'clean_namespace',
            'final_class',
            'general_phpdoc_tag_rename',
            'header_comment',
            'no_blank_lines_before_namespace',
            'phpdoc_no_access',
            'phpdoc_no_empty_return', // we already want no_superfluous_phpdoc_tags
        ];
    }

    /**
     * These rules are not desirable because a full-fledged SA tool like Rector (which leveradges PHPStan underneath)
     * is more apt to such tasks
     *
     * @return string[]
     */
    public static function getBestHandledWithRectorRules(): array
    {
        return [
            'php_unit_no_expectation_annotation',
            'regular_callable_call',
            'simplified_if_return',
            'simplified_null_return',
            'use_arrow_functions',
        ];
    }

    /**
     * These rules are deemed too risky to be used in such a general ruleset. We already allow some risky rules
     * because we consider them safe enough for good-maintained projects like ours, but these may generate unintentional
     * bugs in too many common situations.
     *
     * @return string[]
     */
    public static function getTooRiskyRules(): array
    {
        return [
            'comment_to_phpdoc',
            'date_time_create_from_format_call',
            'date_time_immutable',
            'declare_strict_types',
            'error_suppression',
            'fopen_flag_order',
            'fopen_flags',
            'mb_str_functions',
            'no_trailing_whitespace_in_string',
            'no_unreachable_default_argument_value',
            'no_unset_on_property',
            'no_useless_nullsafe_operator',
            'nullable_type_declaration_for_default_null_value',
            'ordered_interfaces',
            'php_unit_data_provider_name',
            'php_unit_strict',
            'php_unit_test_annotation',
            'phpdoc_return_self_reference',
            'phpdoc_to_param_type',
            'phpdoc_to_property_type',
            'phpdoc_to_return_type',
            'protected_to_private',
            'return_assignment',
            'static_lambda',
            'strict_comparison',
            'strict_param',
            'string_length_to_empty',
            'string_line_ending',
        ];
    }

    /**
     * Rules that are being deprecated by PHP-CS-Fixer
     * Those are being progressively handled by {@see \Facile\CodingStandards\Rules\DefaultRulesProvider::getRules}
     *
     * @return string[]
     */
    public static function getDeprecatedRules(): array
    {
        return [
            'compact_nullable_typehint',
            'function_typehint_space',
            'new_with_braces',
            'no_trailing_comma_in_list_call',
            'no_trailing_comma_in_singleline_array',
            'single_blank_line_before_namespace',
        ];
    }
}
