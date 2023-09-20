<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

/**
 * This is a class that verifies the ruleset against all the existing rules in PHP-CS-Fixer; it is useful to keep tabs on new
 * rules, and keep a todo-list of new reles that we want to adopt.
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
     * @return \Generator<string>
     */
    public static function getAllMappedRules(): \Generator
    {
        yield from self::getToBeImplementedRules();
        yield from self::getToBeDiscussedRules();
        yield from self::getUndesirableRules();
        yield from self::getUnapplicableRules();
        yield from self::getBestHandledWithRectorRules();
        yield from self::getTooRiskyRules();
    }

    /**
     * These are desirable rules, a todo-list for this library
     *
     * @return string[]
     */
    public static function getToBeImplementedRules(): array
    {
        return [
            // TODO - these SHOULD be implemented
            'no_alternative_syntax',
            'no_useless_sprintf',
            'no_unneeded_curly_braces', // with namespaces => false
            'no_useless_else',
            'no_superfluous_elseif',
            'empty_loop_body',
            'no_multiple_statements_per_line',
            'implode_call',
            'modernize_strpos',
            'single_trait_insert_per_statement',
            'combine_nested_dirname',
            'lambda_not_used_import',
            'combine_consecutive_unsets',
            'phpdoc_tag_casing',
            'phpdoc_no_alias_tag',
            'no_superfluous_phpdoc_tags',
            'no_trailing_comma_in_singleline_function_call',
            'combine_consecutive_issets',
            'semicolon_after_instruction',
            'backtick_to_shell_exec',
            'control_structure_braces',
            'list_syntax',
            'single_line_comment_spacing',
            'ternary_to_elvis_operator',
            'get_class_to_class_keyword',
            'no_unneeded_import_alias',
            'control_structure_continuation_position',
            'switch_continue_to_break',
            'octal_notation',
            'statement_indentation',
            'types_spaces',
            'no_unset_cast',
            'no_blank_lines_after_class_opening',
            'ordered_traits',
            'php_unit_mock_short_will_return',
            'php_unit_expectation',
            'linebreak_after_opening_tag',
            'no_homoglyph_names',
            'array_push',
            'set_type_to_cast',
            'ereg_to_preg',
            'doctrine_annotation_indentation',
            'doctrine_annotation_braces', // with braces?
            'doctrine_annotation_spaces',
            'native_function_type_declaration_casing',
            'magic_method_casing',
            'class_reference_name_casing',
            'integer_literal_case',
            'magic_constant_casing',
            'lowercase_static_reference',
            'declare_parentheses',
            'phpdoc_var_annotation_correct_order',
            'assign_null_coalescing_to_coalesce_equal',
            'php_unit_fqcn_annotation',
            'php_unit_dedicate_assert_internal_type',
            'phpdoc_trim_consecutive_blank_line_separation',
            'phpdoc_align', // with left align
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
            'escape_implicit_backslashes',
            'doctrine_annotation_array_assignment',
            'curly_braces_position',
            'multiline_whitespace_before_semicolons', // with new_line_for_chained_calls
            'php_unit_method_casing',
            'php_unit_test_case_static_method_calls',
            'operator_linebreak',
            'echo_tag_syntax',
            'explicit_string_variable',
            'heredoc_to_nowdoc',
            'explicit_indirect_variable',
            'global_namespace_import',
            'phpdoc_summary',
            'phpdoc_inline_tag_normalizer',
            'phpdoc_tag_type',
            'self_static_accessor',
            'heredoc_indentation',
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
            'class_keyword_remove',
            'group_import',
            'php_unit_internal_class',
            'final_internal_class',
            'ordered_class_elements',
            'no_binary_string',
            'single_line_throw',
            'single_space_after_construct',
            'standardize_increment',
            'phpdoc_add_missing_param_annotation',
            'general_phpdoc_annotation_remove',
            'fully_qualified_strict_types',
            'phpdoc_line_span',
            'empty_loop_condition',
            'yoda_style',
            'no_null_property_initialization',
            'final_public_method_for_abstract_class',
            'no_unneeded_final_method',
            'php_unit_test_class_requires_covers',
            'php_unit_size_class',
            'no_alias_language_construct_call',
            'phpdoc_types_order',
            'not_operator_with_space',
            'simple_to_complex_string_variable',
            'phpdoc_order_by_value',
            'no_useless_return',
            'blank_line_between_import_groups',
            'phpdoc_to_comment', // disabled in 0.5.3
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
            'final_class',
            'header_comment',
            'no_blank_lines_before_namespace',
            'general_phpdoc_tag_rename',
            'phpdoc_no_access',
            'phpdoc_no_empty_return', // we already want no_superfluous_phpdoc_tags
            'clean_namespace',
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
            'regular_callable_call',
            'simplified_if_return',
            'simplified_null_return',
            'php_unit_no_expectation_annotation',
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
            'date_time_immutable',
            'nullable_type_declaration_for_default_null_value',
            'phpdoc_to_param_type',
            'phpdoc_to_property_type',
            'phpdoc_to_return_type',
            'protected_to_private',
            'strict_comparison',
            'strict_param',
            'comment_to_phpdoc',
            'no_unset_on_property',
            'php_unit_test_annotation',
            'phpdoc_return_self_reference',
            'declare_strict_types',
            'string_length_to_empty',
            'ordered_interfaces',
            'php_unit_strict',
            'no_useless_nullsafe_operator',
            'fopen_flag_order',
            'fopen_flags',
            'date_time_create_from_format_call',
            'static_lambda',
            'no_unreachable_default_argument_value',
            'string_line_ending',
            'no_trailing_whitespace_in_string',
            'mb_str_functions',
            'error_suppression',
            'return_assignment',
        ];
    }
}
