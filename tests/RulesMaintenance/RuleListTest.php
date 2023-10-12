<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

use PHPUnit\Framework\TestCase;

/**
 * This test is used to keep {@see RulesList} tidy.
 */
class RuleListTest extends TestCase
{
    /**
     * @dataProvider listMethodsDataProvider
     */
    public function testGetAllMappedRules(array $rulesList): void
    {
        $this->assertNotEmpty(array_intersect($rulesList, RulesList::getAllMappedRules()), 'Method is getting lost in getAllMappedRules');
    }

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
    public static function listMethodsDataProvider(): \Generator
    {
        $reflectionClass = new \ReflectionClass(RulesList::class);
        foreach ($reflectionClass->getMethods() as $method) {
            if (! $method->isStatic()) {
                throw new \LogicException('All methods should be static on ' . RulesList::class);
            }

            if (! $method->isPublic()) {
                continue;
            }

            if ($method->getName() === 'getAllMappedRules') {
                continue;
            }

            /** @var string[] $result */
            $result = $method->invoke(null);

            yield $method->getName() => [$result];
        }
    }
}
