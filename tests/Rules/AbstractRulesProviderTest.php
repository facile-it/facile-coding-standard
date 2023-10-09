<?php

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\RulesProviderInterface;
use Facile\CodingStandardsTest\Framework\TestCase;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;

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
