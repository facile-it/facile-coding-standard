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

use Facile\CodingStandards\Rules\DefaultRulesProvider;
use Facile\CodingStandards\Rules\RiskyRulesProvider;
use PhpCsFixer\Console\Command\DescribeCommand;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSets;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

require __DIR__ . '/vendor/autoload.php';

$fixers = getAllFixers();
$setDefinitions = RuleSets::getSetDefinitions();

$output = __DIR__ . '/dump_rules.md';
@unlink($output);

foreach ($fixers as $fixer) {
    if (isAlreadyActiveInCurrentRuleset($fixer)) {
        continue;
    }

    describe($output, $fixer);
}

/**
 * @return FixerInterface[]
 */
function getAllFixers(): array
{
    $fixerFactory = new FixerFactory();
    $fixerFactory->registerBuiltInFixers();

    return $fixerFactory->getFixers();
}

function isAlreadyActiveInCurrentRuleset(FixerInterface $fixer): bool
{
    static $allActiveRules;
    if (null === $allActiveRules) {
        $allActiveRules = array_merge(
            (new DefaultRulesProvider())->getRules(),
            (new RiskyRulesProvider())->getRules()
        );

        foreach (RuleSets::getSetDefinitions() as $ruleSetDefinition) {
            $allActiveRules = array_merge($allActiveRules, $ruleSetDefinition->getRules());
        }
    }

    return array_key_exists($fixer->getName(), $allActiveRules);
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

    echo 'dumping rule ' . $fixer->getName() . '...' . PHP_EOL;

    try {
        if ($application->run(new ArrayInput(['describe', 'name' => $fixer->getName()]), $bufferedOutput) !== 0) {
            echo 'Error while describing rule ' . $fixer->getName() . PHP_EOL;
            echo $bufferedOutput->fetch();
            exit(1);
        }
    } catch (\Throwable $exception) {
        echo 'Error while running describe for rule: ' . $exception->getMessage() . PHP_EOL;
        exit(1);
    }

    file_put_contents($outputFile, postProcessOutput($bufferedOutput->fetch()), FILE_APPEND);
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

