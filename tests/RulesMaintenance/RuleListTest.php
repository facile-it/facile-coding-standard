<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

use PHPUnit\Framework\TestCase;

/**
 * This test is used to keep {@see RulesList} tidy
 */
class RuleListTest extends TestCase
{
    /**
     * @dataProvider listMethodsDataProvider
     */
    public function testAllListAreAlphabetical(array $rulesList): void
    {
        $sortedList = $rulesList;
        sort($sortedList);

        $this->assertEquals($sortedList, $rulesList, 'List is not alphabetically sorted');
    }

    /**
     * @return \Generator<string, array{string[]}>
     */
    public function listMethodsDataProvider(): \Generator
    {
        $reflectionClass = new \ReflectionClass(RulesList::class);
        foreach ($reflectionClass->getMethods() as $method) {
            if (! $method->isStatic()) {
                throw new \LogicException('All methods should be static on ' . RulesList::class);
            }

            if ($method->getName() === 'getAllMappedRules') {
                continue;
            }

            yield $method->getName() => [$method->invoke(null)];
        }
    }
}
