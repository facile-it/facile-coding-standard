<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

final class DefaultRulesProvider extends AbstractRuleProvider
{
    public function getRules(): array
    {
        $rules = [
            '@PER-CS2.0' => true,
            '@PSR12' => true,
            '@DoctrineAnnotation' => true,
            'align_multiline_comment' => true,
            'array_indentation' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'assign_null_coalescing_to_coalesce_equal' => true,
            'attribute_empty_parentheses' => true,
            'backtick_to_shell_exec' => true,
            'binary_operator_spaces' => [
                'operators' => [
                    '=>' => null,
                    '=' => null,
                ],
            ],
            'blank_line_before_statement' => [
                'statements' => [
                    'return',
                ],
            ],
            'cast_spaces' => true,
            'class_attributes_separation' => true,
            'class_reference_name_casing' => true,
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'compact_nullable_typehint' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'curly_braces_position' => true,
            'declare_parentheses' => true,
            'empty_loop_body' => true,
            'explicit_string_variable' => true,
            'function_typehint_space' => true,
            'heredoc_indentation' => true,
            'heredoc_to_nowdoc' => true,
            'include' => true,
            'increment_style' => true,
            'integer_literal_case' => true,
            'lambda_not_used_import' => true,
            'linebreak_after_opening_tag' => true,
            'list_syntax' => true,
            'magic_constant_casing' => true,
            'magic_method_casing' => true,
            'method_chaining_indentation' => true,
            'multiline_comment_opening_closing' => true,
            'native_function_casing' => true,
            'native_function_type_declaration_casing' => true,
            'native_type_declaration_casing' => true,
            'new_with_braces' => true,
            'no_alternative_syntax' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => [
                'tokens' => [
                    'curly_brace_block',
                    'extra',
                    'parenthesis_brace_block',
                    'square_brace_block',
                    'throw',
                    'use',
                ],
            ],
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
            'no_superfluous_elseif' => true,
            'no_superfluous_phpdoc_tags' => [
                'allow_mixed' => true, // needed to silence Psalm when actual mixed is used
            ],
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_comma_in_singleline' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_trailing_comma_in_singleline_function_call' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unneeded_import_alias' => true,
            'no_unneeded_braces' => true,
            'no_unset_cast' => true,
            'no_unused_imports' => true,
            'no_useless_concat_operator' => true,
            'no_useless_else' => true,
            'no_whitespace_before_comma_in_array' => true,
            'not_operator_with_successor_space' => true,
            'normalize_index_brace' => true,
            'nullable_type_declaration' => true,
            'numeric_literal_separator' => true,
            'object_operator_without_whitespace' => true,
            'octal_notation' => true,
            'operator_linebreak' => true,
            'phpdoc_align' => [
                'align' => 'left',
            ],
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_indent' => true,
            'phpdoc_inline_tag_normalizer' => true,
            'phpdoc_no_alias_tag' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_order' => true,
            'phpdoc_param_order' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_tag_type' => true,
            'phpdoc_to_comment' => false, // to avoid false positives with PHPStan @var helpers
            'phpdoc_var_annotation_correct_order' => true,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_var_without_name' => true,
            'phpdoc_tag_casing' => true,
            'php_unit_fqcn_annotation' => true,
            'return_to_yield_from' => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'semicolon_after_instruction' => true,
            'single_blank_line_before_namespace' => true,
            'single_class_element_per_statement' => true,
            'single_line_comment_spacing' => true,
            'single_line_empty_body' => true,
            'single_quote' => true,
            'single_space_around_construct' => true,
            'space_after_semicolon' => true,
            'standardize_not_equals' => true,
            'switch_continue_to_break' => true,
            'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline' => [
                'elements' => ['arrays'],
            ],
            'trim_array_spaces' => true,
            'type_declaration_spaces' => true,
            'types_spaces' => true,
            'unary_operator_spaces' => true,
            'whitespace_after_comma_in_array' => true,
        ];

        return $this->filterRules($rules);
    }
}
