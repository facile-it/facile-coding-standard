<?php

namespace Facile\CodingStandards\Rules;

use PhpCsFixer\Console\Application;

abstract class AbstractRuleProvider implements RulesProviderInterface
{
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
        '3.5.0' => [
            'get_class_to_class_keyword',
        ],
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
        '3.27.0' => [
            '@PER-CS2.0',
            '@PER-CS2.0:risky',
            'long_to_shorthand_operator',
        ],
        '3.32.0' => ['no_unneeded_braces'],
        '3.33.0' => ['native_type_declaration_casing'],
    ];

    /**
     * Filter rules, with a dynamic filter depending on the PHP-CS-Fixer version in use.
     *
     * @template T of array<string, mixed>
     *
     * @param T $rules
     *
     * @return T
     */
    protected function filterRules(array $rules): array
    {
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
    protected function isAtLeastVersion(string $version): bool
    {
        return version_compare(Application::VERSION, $version, '>=');
    }
}
