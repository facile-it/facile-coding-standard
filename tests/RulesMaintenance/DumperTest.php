<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

use PHPUnit\Framework\TestCase;

class DumperTest extends TestCase
{
    public function testGetUnlistedRulesDescription(): void
    {
        $dumper = new Dumper();
        $unlistedFixerNames = array_keys(iterator_to_array($dumper->getUnlistedRulesDescription(), false));

        foreach (RulesList::getAllMappedRules() as $ruleName) {
            $this->assertArrayNotHasKey($ruleName, $unlistedFixerNames, 'Rule should not appear unlisted: ' . $ruleName);
        }
    }

    /**
     * This test may break a lot when new rules are introducted in PHP-CS-Fixer
     * An easy fix is to add them to {@see RulesList::getToBeDiscussedRules()}
     */
    public function testNoRuleIsUnlisted(): void
    {
        $dumper = new Dumper();
        $unlistedFixerNames = array_keys(iterator_to_array($dumper->getUnlistedRulesDescription()));
        sort($unlistedFixerNames);

        $this->assertEmpty($unlistedFixerNames, implode(\PHP_EOL, ['There are some unlisted rules:', ...$unlistedFixerNames]));
    }
}
