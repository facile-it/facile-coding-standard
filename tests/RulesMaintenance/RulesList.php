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
    }

    /**
     * These are desirable rules, a todo-list for this library.
     *
     * @return string[]
     */
    public static function getToBeImplementedRules(): array
    {
        return [
            'get_class_to_class_keyword', // already active, shut off here due to PHP 7.4 support
        ];
    }

    /**
     * There are rules on which we're not sure about; newly implemented rules in PHP-CS-Fixer should go here,
     * until approved in a new release (or rejected and moved in another of the following lists).
     *
     * @return string[]
     */
    public static function getToBeDiscussedRules(): array
    {
        return [
            'echo_tag_syntax',
            'escape_implicit_backslashes',
            'explicit_indirect_variable',
            'global_namespace_import',
            'multiline_whitespace_before_semicolons', // with new_line_for_chained_calls
            'ordered_types',
            'php_unit_method_casing',
            'php_unit_test_case_static_method_calls',
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
            'single_blank_line_before_namespace', // deprecated, replaced with blank_lines_before_namespace
            'single_line_throw',
            'single_space_after_construct',
            'standardize_increment',
            'yield_from_array_to_yields',
            'yoda_style',
        ];
    }

    /**
     * These rules are not applicable, because they are not useful in private projects or in a general ruleset like.
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
     * is more apt to such tasks.
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
            'no_unset_on_property',
            'no_useless_nullsafe_operator',
            'nullable_type_declaration_for_default_null_value',
            'ordered_interfaces',
            'php_unit_data_provider_name',
            'php_unit_strict',
            'php_unit_test_annotation',
            'phpdoc_readonly_class_comment_to_keyword',
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
}
