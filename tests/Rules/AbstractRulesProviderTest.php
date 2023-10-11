<?php

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\RulesProviderInterface;
use Facile\CodingStandardsTest\Framework\TestCase;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;

abstract class AbstractRulesProviderTest extends TestCase
{
    /** @psalm-suppress PrivateClass */
    protected static FixerFactory $fixerFactory;

    public static function setUpBeforeClass(): void
    {
        TestCase::setUpBeforeClass();

        $fixerFactory = new FixerFactory();
        $fixerFactory->registerBuiltInFixers();

        self::$fixerFactory = $fixerFactory;
    }

    abstract protected function shouldBeRisky(): bool;

    public function testRulesAreAlphabeticallySorted(): void
    {
        $this->assertRulesAreAlphabeticallySorted(static::getRulesProvider());
    }

    /**
     * @dataProvider ruleNamesDataProvider
     */
    public function testRulesAreNotRisky(string $ruleName): void
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
        foreach ($ruleSet->getRules() as $ruleName => $config) {
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
        $enabledRuleSets = [];
        foreach (self::ruleSetNamesDataProvider() as $data) {
            $ruleSetName = $data[0];
            $enabledRuleSets[$ruleSetName] = new RuleSet([$ruleSetName => true]);
        }

        $this->assertNotEmpty($enabledRuleSets, 'No rule sets found');

        foreach ($enabledRuleSets as $name => $ruleSet) {
            $this->assertFalse(
                $ruleSet->hasRule($ruleName),
                sprintf('Rule %s is being overridden while already included in %s rule set', $ruleName, $name)
                . \PHP_EOL . 'Our config: ' . print_r(static::getRulesProvider()->getRules()[$ruleName], true)
                . \PHP_EOL . 'Rule set config: ' . print_r($ruleSet->getRuleConfiguration($ruleName), true)
                . \PHP_EOL . 'Default config: ' . print_r($this->getFixerByName($ruleName), true)
            );
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
        foreach (static::getRulesProvider()->getRules() as $ruleName => $config) {
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
        foreach (static::getRulesProvider()->getRules() as $ruleSetName => $config) {
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
        $rules = $rulesProvider->getRules();
        $this->assertNotEmpty($rules, 'No rules from the provider!');

        foreach ($rules as $ruleName => $config) {
            if (str_starts_with($ruleName, '@')) {
                continue;
            }

            $fixer = $this->getFixerByName($ruleName);

            $this->assertSame($expected, $fixer->isRisky(), 'Fixer is risky: ' . $ruleName);
        }
    }

    private function getFixerByName(string $rule): FixerInterface
    {
        foreach (self::$fixerFactory->getFixers() as $fixer) {
            if ($fixer->getName() === $rule) {
                return $fixer;
            }
        }

        throw new \InvalidArgumentException('Fixer not found: ' . $rule);
    }
}
