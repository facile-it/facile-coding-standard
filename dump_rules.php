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

use Facile\CodingStandardsTest\RulesMaintenance\RulesList;
use PhpCsFixer\Console\Command\DescribeCommand;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

require __DIR__ . '/vendor/autoload.php';

$output = __DIR__ . '/dump_rules.md';
@unlink($output);
$alreadyActiveFixers = iterator_to_array(getAlreadyActiveFixers());

$allMappedRules = iterator_to_array(RulesList::getAllMappedRules());
foreach ($allMappedRules as $fixerName => $isIgnored) {
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

    if ($allMappedRules[$fixer->getName()] ?? false) {
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
