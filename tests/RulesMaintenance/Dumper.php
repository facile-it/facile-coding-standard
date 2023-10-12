<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

use Facile\CodingStandards\Rules\CompositeRulesProvider;
use Facile\CodingStandards\Rules\DefaultRulesProvider;
use Facile\CodingStandards\Rules\RiskyRulesProvider;
use PhpCsFixer\Config;
use PhpCsFixer\Console\Command\DescribeCommand;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @psalm-suppress InternalClass
 * @psalm-suppress InternalMethod
 */
class Dumper
{
    /**
     * @return \Generator<string, string>
     */
    public function getUnlistedRulesDescription(bool $listWarnings = false): \Generator
    {
        $alreadyActiveFixers = iterator_to_array($this->getAlreadyActiveFixers());

        $allMappedRules = RulesList::getAllMappedRules();

        if ($listWarnings) {
            foreach ($allMappedRules as $fixerName) {
                foreach ($alreadyActiveFixers as $fixer) {
                    if ($fixer->getName() === $fixerName) {
                        yield 'warning for ' . $fixerName => 'WARNING: ignoring already active fixer: ' . $fixerName . \PHP_EOL;

                        break;
                    }
                }
            }
        }

        foreach ($this->getAllFixers() as $fixer) {
            if (isset($alreadyActiveFixers[\get_class($fixer)])) {
                continue;
            }

            if (\in_array($fixer->getName(), $allMappedRules, true)) {
                continue;
            }

            if ($fixer instanceof DeprecatedFixerInterface) {
                continue;
            }

            yield $fixer->getName() => $this->describe($fixer);
        }
    }

    /**
     * @return \Generator<class-string<FixerInterface>, FixerInterface>
     */
    private function getAllFixers(): \Generator
    {
        $fixerFactory = new FixerFactory();
        $fixerFactory->registerBuiltInFixers();

        yield from $this->generateWithClassNameAsKey($fixerFactory->getFixers());
    }

    /**
     * @return \Generator<class-string<FixerInterface>, FixerInterface>
     */
    private function getAlreadyActiveFixers(): \Generator
    {
        $providers = [
            new DefaultRulesProvider(),
            new RiskyRulesProvider(),
        ];

        $rulesProvider = new CompositeRulesProvider($providers);

        $config = new Config('facile-it/facile-coding-standard');
        $config->setRules($rulesProvider->getRules());
        $config->setRiskyAllowed(true);

        $resolver = new ConfigurationResolver($config, [], '/dev/null', new ToolInfo());

        yield from $this->generateWithClassNameAsKey($resolver->getFixers());
    }

    /**
     * @param FixerInterface[] $list
     *
     * @return \Generator<class-string<FixerInterface>, FixerInterface>
     */
    private function generateWithClassNameAsKey(array $list): \Generator
    {
        foreach ($list as $fixer) {
            yield \get_class($fixer) => $fixer;
        }
    }

    private function describe(FixerInterface $fixer): string
    {
        /** @var Application|null $application */
        static $application;
        if (null === $application) {
            $command = new DescribeCommand();
            $application = new Application();
            $application->add($command);
            $application->setAutoExit(false);
        }

        $bufferedOutput = new BufferedOutput();

        try {
            $exitCode = $application->run(new ArrayInput(['describe', 'name' => $fixer->getName()]), $bufferedOutput);
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Error while running describe for rule: ' . $exception->getMessage(), 0, $exception);
        }

        if ($exitCode !== 0) {
            throw new \RuntimeException('Error while describing rule ' . $fixer->getName());
        }

        return $this->postProcessOutput($bufferedOutput->fetch());
    }

    private function postProcessOutput(string $output): string
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
}
