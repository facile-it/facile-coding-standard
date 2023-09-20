<?php

/**
 * This PHP script is useful to dump rules descriptions into Markdown,
 * so that it's easier to open PRs with descriptive content aimed at
 * adding new rules to this repository ruleset.
 *
 * Launching this script with not options will dump all rules which are
 * not alread enabled.
 *
 * TODO: Launching it with arguments will dump the listed rules.
 */

use PhpCsFixer\Console\Command\DescribeCommand;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

require __DIR__ . '/vendor/autoload.php';

const UNDESIRED_RULES = [
    // TODO - these SHOULD be implemented
    'no_alternative_syntax' => true,
    'no_useless_sprintf' => true,
    'no_unneeded_curly_braces' => true, // with namespaces => false
    'no_useless_else' => true,
    'no_superfluous_elseif' => true,
    'empty_loop_body' => true,
    'no_multiple_statements_per_line' => true,
    'implode_call' => true,
    'modernize_strpos' => true,
    'single_trait_insert_per_statement' => true,
    'combine_nested_dirname' => true,
    'lambda_not_used_import' => true,
    'combine_consecutive_unsets' => true,
    'phpdoc_tag_casing' => true,
    'phpdoc_no_alias_tag' => true,
    'no_superfluous_phpdoc_tags' => true,
    'no_trailing_comma_in_singleline_function_call' => true,
    'combine_consecutive_issets' => true,
    'semicolon_after_instruction' => true,
    'backtick_to_shell_exec' => true,
    'control_structure_braces' => true,
    'list_syntax' => true,
    'single_line_comment_spacing' => true,
    'ternary_to_elvis_operator' => true,
    'get_class_to_class_keyword' => true,
    'no_unneeded_import_alias' => true,
    'control_structure_continuation_position' => true,
    'switch_continue_to_break' => true,
    'octal_notation' => true,
    'statement_indentation' => true,
    'types_spaces' => true,
    'no_unset_cast' => true,
    'no_blank_lines_after_class_opening' => true,
    'ordered_traits' => true,
    'php_unit_mock_short_will_return' => true,
    'php_unit_expectation' => true,
    'linebreak_after_opening_tag' => true,
    'no_homoglyph_names' => true,
    'array_push' => true,
    'set_type_to_cast' => true,
    'ereg_to_preg' => true,
    'doctrine_annotation_indentation' => true,
    'doctrine_annotation_braces' => true, // with braces?
    'doctrine_annotation_spaces' => true,
    'native_function_type_declaration_casing' => true,
    'magic_method_casing' => true,
    'class_reference_name_casing' => true,
    'integer_literal_case' => true,
    'magic_constant_casing' => true,
    'lowercase_static_reference' => true,
    'declare_parentheses' => true,
    'phpdoc_var_annotation_correct_order' => true,
    'assign_null_coalescing_to_coalesce_equal' => true,
    'php_unit_fqcn_annotation' => true,
    'php_unit_dedicate_assert_internal_type' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'phpdoc_align' => true, // with left align
    // not sure
    'escape_implicit_backslashes' => true,
    'doctrine_annotation_array_assignment' => true,
    'curly_braces_position' => true,
    'multiline_whitespace_before_semicolons' => true, // with new_line_for_chained_calls
    'php_unit_method_casing' => true,
    'php_unit_test_case_static_method_calls' => true,
    'operator_linebreak' => true,
    'echo_tag_syntax' => true,
    'explicit_string_variable' => true,
    'heredoc_to_nowdoc' => true,
    'explicit_indirect_variable' => true,
    'global_namespace_import' => true,
    'phpdoc_summary' => true,
    'phpdoc_inline_tag_normalizer' => true,
    'phpdoc_tag_type' => true,
    'self_static_accessor' => true,
    'heredoc_indentation' => true,
    'single_line_comment_style' => true,
    // undesirable
    'class_keyword_remove' => true,
    'group_import' => true,
    'php_unit_internal_class' => true,
    'final_internal_class' => true,
    'ordered_class_elements' => true,
    'no_binary_string' => true,
    'single_line_throw' => true,
    'single_space_after_construct' => true,
    'standardize_increment' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'general_phpdoc_annotation_remove' => true,
    'fully_qualified_strict_types' => true,
    'phpdoc_line_span' => true,
    'empty_loop_condition' => true,
    'yoda_style' => true,
    'no_null_property_initialization' => true,
    'final_public_method_for_abstract_class' => true,
    'no_unneeded_final_method' => true,
    'php_unit_test_class_requires_covers' => true,
    'php_unit_size_class' => true,
    'no_alias_language_construct_call' => true,
    'phpdoc_types_order' => true,
    'not_operator_with_space' => true,
    'simple_to_complex_string_variable' => true,
    'phpdoc_order_by_value' => true,
    'no_useless_return' => true,
    'blank_line_between_import_groups' => true,
    'phpdoc_to_comment' => true, // disabled in 0.5.3
    // not applicable
    'final_class' => true,
    'header_comment' => true,
    'no_blank_lines_before_namespace' => true,
    'general_phpdoc_tag_rename' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_empty_return' => true, // we already want no_superfluous_phpdoc_tags
    'clean_namespace' => true,
    // should be handled with Rector
    'regular_callable_call' => true,
    'simplified_if_return' => true,
    'simplified_null_return' => true,
    'php_unit_no_expectation_annotation' => true,
    'use_arrow_functions' => true,
    // too risky
    'date_time_immutable' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'phpdoc_to_param_type' => true,
    'phpdoc_to_property_type' => true,
    'phpdoc_to_return_type' => true,
    'protected_to_private' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'comment_to_phpdoc' => true,
    'no_unset_on_property' => true,
    'php_unit_test_annotation' => true,
    'phpdoc_return_self_reference' => true,
    'declare_strict_types' => true,
    'string_length_to_empty' => true,
    'ordered_interfaces' => true,
    'php_unit_strict' => true,
    'no_useless_nullsafe_operator' => true,
    'fopen_flag_order' => true,
    'fopen_flags' => true,
    'date_time_create_from_format_call' => true,
    'static_lambda' => true,
    'no_unreachable_default_argument_value' => true,
    'string_line_ending' => true,
    'no_trailing_whitespace_in_string' => true,
    'mb_str_functions' => true,
    'error_suppression' => true,
    'return_assignment' => true,
];

$output = __DIR__ . '/dump_rules.md';
@unlink($output);
$alreadyActiveFixers = iterator_to_array(getAlreadyActiveFixers());

foreach (UNDESIRED_RULES as $fixerName => $isIgnored) {
    if (! $isIgnored) {
        continue;
    }

    foreach ($alreadyActiveFixers as $fixer) {
        if ($fixer->getName() === $fixerName) {
            echo 'WARNING: ignoring already active fixer: ' . $fixerName . \PHP_EOL;

            break;
        }
    }
}

foreach (getAllFixers() as $fixer) {
    if (isset($alreadyActiveFixers[\get_class($fixer)])) {
        continue;
    }

    if (UNDESIRED_RULES[$fixer->getName()] ?? false) {
        continue;
    }

    describe($output, $fixer);
}

/**
 * @return \Generator<class-string<FixerInterface>, FixerInterface>
 */
function getAllFixers(): \Generator
{
    $fixerFactory = new FixerFactory();
    $fixerFactory->registerBuiltInFixers();

    yield from generateWithClassNameAsKey($fixerFactory->getFixers());
}

/**
 * @return \Generator<class-string<FixerInterface>, FixerInterface>
 */
function getAlreadyActiveFixers(): \Generator
{
    $providers = [
        new Facile\CodingStandards\Rules\DefaultRulesProvider(),
        new Facile\CodingStandards\Rules\RiskyRulesProvider(),
    ];

    $rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider($providers);

    $config = new PhpCsFixer\Config('facile-it/facile-coding-standard');
    $config->setRules($rulesProvider->getRules());

    $resolver = new ConfigurationResolver($config, [], getcwd(), new ToolInfo());

    yield from generateWithClassNameAsKey($resolver->getFixers());
}

/**
 * @param FixerInterface[] $list
 *
 * @return \Generator<class-string<FixerInterface>, FixerInterface>
 */
function generateWithClassNameAsKey(array $list): \Generator
{
    foreach ($list as $fixer) {
        yield \get_class($fixer) => $fixer;
    }
}

function describe(string $outputFile, FixerInterface $fixer): void
{
    static $application;
    if (null === $application) {
        $command = new DescribeCommand();
        $application = new Application();
        $application->add($command);
        $application->setAutoExit(false);
    }

    $bufferedOutput = new BufferedOutput();

    echo 'dumping rule ' . $fixer->getName() . '...' . \PHP_EOL;

    try {
        if ($application->run(new ArrayInput(['describe', 'name' => $fixer->getName()]), $bufferedOutput) !== 0) {
            echo 'Error while describing rule ' . $fixer->getName() . \PHP_EOL;
            echo $bufferedOutput->fetch();
            exit(1);
        }
    } catch (\Throwable $exception) {
        echo 'Error while running describe for rule: ' . $exception->getMessage() . \PHP_EOL;
        exit(1);
    }

    file_put_contents($outputFile, postProcessOutput($bufferedOutput->fetch()), \FILE_APPEND);
}

function postProcessOutput(string $output): string
{
    // replace first line with heading
    $output = preg_replace('/Description of ([\w_]+) rule\./', '## `$1`', $output);
    // replace diffs opening with fenced typed code snippet
    $output = preg_replace('/ +-+ begin diff -+/', '```diff', $output);
    // replace diffs closing with fenced code snippet
    $output = preg_replace('/ *\n +-+ end diff -+/', '```', $output);
    // remove additional diff labels
    $output = preg_replace('/ +(--- Original|\+\+\+ New|@@ .+ @@)\n/', '', $output);
    // try to de-indent diff snippets
    $output = preg_replace('/\n {3}/', "\n", $output);

    // avoid linking issues by mistake
    return str_replace('Example #', 'Example ', $output);
}
