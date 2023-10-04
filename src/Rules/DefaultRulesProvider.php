<?php

declare(strict_types=1);

namespace Facile\CodingStandards\Rules;

use PhpCsFixer\Console\Application;

final class DefaultRulesProvider implements RulesProviderInterface
{
    /**
     * @var array<string, mixed>
     */
    private static array $rules = [
        '@PER-CS2.0' => true,
        '@DoctrineAnnotation' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => null,
                '=' => null,
            ],
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'return',
            ],
        ],
        'blank_lines_before_namespace' => true,
        'cast_spaces' => [
            'space' => 'single',
        ],
        'class_attributes_separation' => true,
        'class_reference_name_casing' => true,
        'compact_nullable_typehint' => true,
        'compact_nullable_type_declaration' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'curly_braces_position' => true,
        'declare_equal_normalize' => true,
        'declare_parentheses' => true,
        'empty_loop_body' => true,
        'function_typehint_space' => true,
        'include' => true,
        'increment_style' => [
            'style' => 'pre',
        ],
        'integer_literal_case' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => true,
        'long_to_shorthand_operator' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'multiline_comment_opening_closing' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,
        'native_type_declaration_casing' => true,
        'new_with_braces' => true,
        'new_with_parentheses' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_class_opening' => true,
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
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => [
            'use' => 'echo',
        ],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_comma_in_singleline_function_call' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_import_alias' => true,
        'no_unneeded_braces' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'not_operator_with_successor_space' => true,
        'normalize_index_brace' => true,
        'nullable_type_declaration' => true,
        'object_operator_without_whitespace' => true,
        'octal_notation' => true,
        'ordered_imports' => true,
        'phpdoc_align' => false,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_to_comment' => false, // to avoid false positives with PHPStan @var helpers
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'phpdoc_tag_casing' => true,
        'return_to_yield_from' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'return_type_declaration' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_line_comment_spacing' => true,
        'single_line_empty_body' => true,
        'single_quote' => true,
        'single_space_around_construct' => true,
        'single_trait_insert_per_statement' => true,
        'space_after_semicolon' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
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

    /**
     * This array maps the deprecations of PHP-CS-Fixer rules, allowing us a flexible approach: both the deprecated and
     * the new rules are defined inside {@see self::$rules}, and this map allows us to remove one of the two depending
     * on the PHP-CS-Fixer version detected at runtime.
     *
     * The map has the following format: [ version => [ oldRuleName => newRuleName ] ]
     */
    private const DEPRECATION_MAP = [
        '3.11.0' => [
            'no_trailing_comma_in_list_call' => 'no_trailing_comma_in_singleline',
            'no_trailing_comma_in_singleline_array' => 'no_trailing_comma_in_singleline',
            'no_trailing_comma_in_singleline_function_call' => 'no_trailing_comma_in_singleline',
        ],
        '3.18.0' => [
            'single_blank_line_before_namespace' => 'blank_lines_before_namespace',
        ],
        '3.21.0' => [
            'function_typehint_space' => 'type_declaration_spaces',
        ],
        '3.32.0' => [
            'compact_nullable_typehint' => 'compact_nullable_type_declaration',
            'curly_braces_position' => 'braces_position',
            'new_with_braces' => 'new_with_parentheses',
        ],
    ];

    /**
     * This array maps the introduction of new rules in PHP-CS-Fixer, when no deprecated counterpart is present
     * for older versions.
     */
    private const INTRODUCTION_MAP = [
        '3.6.0' => [
            'class_reference_name_casing',
            'no_unneeded_import_alias',
        ],
        '3.7.0' => [
            'no_trailing_comma_in_singleline_function_call',
            'single_line_comment_spacing',
        ],
        '3.9.1' => ['curly_braces_position'],
        '3.16.0' => ['single_space_around_construct'],
        '3.17.0' => ['single_line_empty_body'],
        '3.21.0' => ['nullable_type_declaration'],
        '3.23.0' => ['return_to_yield_from'],
        '3.27.0' => ['long_to_shorthand_operator'],
        '3.32.0' => ['no_unneeded_braces'],
        '3.33.0' => ['native_type_declaration_casing'],
    ];

    /**
     * Get default rules, with a dynamic filter depending on the PHP-CS-Fixer version in use.
     *
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        $rules = self::$rules;

        foreach (self::DEPRECATION_MAP as $version => $ruleMap) {
            foreach ($ruleMap as $oldRule => $newRule) {
                if ($this->isAtLeastVersion($version)) {
                    unset($rules[$oldRule]);
                } else {
                    unset($rules[$newRule]);
                }
            }
        }

        foreach (self::INTRODUCTION_MAP as $version => $newRules) {
            if (! $this->isAtLeastVersion($version)) {
                foreach ($newRules as $name) {
                    unset($rules[$name]);
                }
            }
        }

        return $rules;
    }

    /**
     * @psalm-suppress InternalClass
     */
    private function isAtLeastVersion(string $version): bool
    {
        return version_compare(Application::VERSION, $version, '>=');
    }
}
