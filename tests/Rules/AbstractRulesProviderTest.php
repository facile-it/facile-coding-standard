<?php

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\AbstractRuleProvider;
use Facile\CodingStandards\Rules\RulesProviderInterface;
use Facile\CodingStandardsTest\Framework\TestCase;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;

/**
 * @psalm-suppress InternalClass
 * @psalm-suppress InternalMethod
 */
abstract class AbstractRulesProviderTest extends TestCase
{
    abstract protected function shouldBeRisky(): bool;

    public function testRulesAreAlphabeticallySorted(): void
    {
        $this->assertRulesAreAlphabeticallySorted(static::getRulesProvider());
    }

    /**
     * @dataProvider ruleNamesDataProvider
     */
    public function testRuleAreRiskyAsExpected(string $ruleName): void
    {
        $fixer = $this->getFixerByName($ruleName);

        $this->assertSame(
            $this->shouldBeRisky(),
            $fixer->isRisky(),
            sprintf('Fixer %s is %s as expected', $ruleName, $this->shouldBeRisky() ? 'risky' : 'NOT risky')
        );
    }

    /**
     * @dataProvider ruleSetNamesDataProvider
     */
    public function testRuleSetsAreRiskyAsExpected(string $ruleSetName): void
    {
        $ruleSet = new RuleSet([$ruleSetName => true]);
        foreach (array_keys($ruleSet->getRules()) as $ruleName) {
            $fixer = $this->getFixerByName($ruleName);

            $this->assertSame(
                $this->shouldBeRisky(),
                $fixer->isRisky(),
                sprintf('Ruleset %s includes %s rules, such as %s', $ruleSetName, $this->shouldBeRisky() ? 'risky' : 'NOT risky', $ruleName)
            );
        }
    }

    /**
     * @dataProvider ruleNamesDataProvider
     */
    public function testRulesDoNotOverrideRuleSets(string $ruleName): void
    {
        if ($this->ruleIsMappedAsDeprecated($ruleName)) {
            $this->markTestSkipped($ruleName . ' rule is already mapped as deprecated in a future release (we are probably under --prefer-lowest)');
        }

        $allowedOverrides = [
            'binary_operator_spaces',
            'single_class_element_per_statement',
        ];

        if (\in_array($ruleName, $allowedOverrides)) {
            $this->markTestSkipped($ruleName . 'Rule is allowed to override the rule sets configuration');
        }

        $enabledRuleSets = $this->getEnabledRuleSets();

        foreach ($enabledRuleSets as $name => $ruleSet) {
            if (! $ruleSet->hasRule($ruleName)) {
                continue;
            }

            $this->assertConfigurationIsSameAsRuleSet($ruleSet, $ruleName);

            if ($name === '@PER-CS2.0' && $this->isNotInPsr12($ruleName)) {
                $this->markTestSkipped(sprintf('Rule %s is part of PER-CS but NOT of PSR-12, we can drop it only in the future', $ruleName));
            }

            $this->fail(sprintf('Rule %s is being overridden while already included in %s rule set, with the same config', $ruleName, $name));
        }
    }

    protected static function getRulesProvider(): RulesProviderInterface
    {
        throw new \LogicException(sprintf('Override %s to provide the proper concrete instance of %s', __METHOD__, RulesProviderInterface::class));
    }

    /**
     * @return \Generator<string, array{string}>
     */
    public static function ruleNamesDataProvider(): \Generator
    {
        foreach (array_keys(static::getRulesProvider()->getRules()) as $ruleName) {
            if (! str_starts_with($ruleName, '@')) {
                yield $ruleName => [$ruleName];
            }
        }
    }

    /**
     * @return \Generator<string, array{string}>
     */
    public static function ruleSetNamesDataProvider(): \Generator
    {
        foreach (array_keys(static::getRulesProvider()->getRules()) as $ruleSetName) {
            if (str_starts_with($ruleSetName, '@')) {
                yield $ruleSetName => [$ruleSetName];
            }
        }
    }

    protected function assertRulesAreAlphabeticallySorted(RulesProviderInterface $provider): void
    {
        $rules = $provider->getRules();

        $sortedRules = $rules;
        ksort($sortedRules);

        $this->assertEquals($sortedRules, $rules, 'Rules are not alphabetically sorted');
    }

    protected function assertAllRulesAreRisky(bool $expected, RulesProviderInterface $rulesProvider): void
    {
        $rules = array_keys($rulesProvider->getRules());
        $this->assertNotEmpty($rules, 'No rules from the provider!');

        foreach ($rules as $ruleName) {
            if (str_starts_with($ruleName, '@')) {
                continue;
            }

            $fixer = $this->getFixerByName($ruleName);

            $this->assertSame($expected, $fixer->isRisky(), 'Fixer is risky: ' . $ruleName);
        }
    }

    private function assertConfigurationIsSameAsRuleSet(RuleSet $ruleSet, string $ruleName): void
    {
        $fixer = $this->getFixerByName($ruleName);
        if (! $fixer instanceof ConfigurableFixerInterface) {
            return;
        }

        $rulesProvider = static::getRulesProvider();
        $ruleConfiguration = $rulesProvider->getRules()[$ruleName];
        $ruleSetConfiguration = $ruleSet->getRuleConfiguration($ruleName);
        $defaultConfiguration = $fixer->getConfigurationDefinition()->resolve([]);
        $this->assertNotEmpty($defaultConfiguration, 'Empty default configuration?');

        if ($ruleSetConfiguration === null) {
            if ($ruleConfiguration === true) {
                return;
            }

            $this->assertEquals($defaultConfiguration, $ruleConfiguration, sprintf(
                'Ruleset relies on default configuration for rule %s, but it is being overridden',
                $ruleName
            ));
        } elseif ($ruleConfiguration === true) {
            $this->assertEquals($ruleSetConfiguration, $defaultConfiguration, sprintf(
                'Ruleset does not use the default config for rule %s, and it is being overridden with "true" in %s',
                $ruleName,
                \get_class($rulesProvider)
            ));
        } else {
            $this->assertEquals($ruleSetConfiguration, $ruleConfiguration, sprintf(
                'Rule %s has a different configuration from the one from ruleset',
                $ruleName
            ));
        }
    }

    /** @var array<string, RuleSet> */
    private array $enabledRuleSets = [];

    /**
     * @return array<string, RuleSet>
     */
    private function getEnabledRuleSets(): array
    {
        if (empty($this->enabledRuleSets)) {
            foreach (self::ruleSetNamesDataProvider() as $data) {
                $ruleSetName = $data[0];
                $this->enabledRuleSets[$ruleSetName] = new RuleSet([$ruleSetName => true]);
            }

            $this->assertNotEmpty($this->enabledRuleSets, 'No rule sets found');
        }

        return $this->enabledRuleSets;
    }

    private function getFixerByName(string $rule): FixerInterface
    {
        /** @var array<string, FixerInterface>|null $fixers */
        static $fixers;

        if (! isset($fixers)) {
            $fixerFactory = new FixerFactory();
            $fixerFactory->registerBuiltInFixers();

            foreach ($fixerFactory->getFixers() as $fixer) {
                $fixers[$fixer->getName()] = $fixer;
            }
        }

        if (! isset($fixers[$rule])) {
            throw new \InvalidArgumentException('Fixer not found: ' . $rule);
        }

        return $fixers[$rule];
    }

    private function ruleIsMappedAsDeprecated(string $ruleName): bool
    {
        /** @var list<string>|null $deprecatedRules */
        static $deprecatedRules;

        if (! isset($deprecatedRules)) {
            $reflectionClassConstant = new \ReflectionClassConstant(AbstractRuleProvider::class, 'DEPRECATION_MAP');
            /** @var array<string, array<string, string>> $deprecationMap */
            $deprecationMap = $reflectionClassConstant->getValue();

            foreach ($deprecationMap as $map) {
                foreach (array_keys($map) as $deprecatedRule) {
                    $deprecatedRules[] = $deprecatedRule;
                }
            }

            $this->assertNotEmpty($deprecatedRules);
        }

        return \in_array($ruleName, $deprecatedRules);
    }

    private function isNotInPsr12(string $ruleName): bool
    {
        /** @var RuleSet|null $psr12 */
        static $psr12;

        $psr12 ??= new RuleSet([
            '@PSR12' => true,
            '@PSR12:risky' => true,
        ]);

        return ! $psr12->hasRule($ruleName);
    }
}
