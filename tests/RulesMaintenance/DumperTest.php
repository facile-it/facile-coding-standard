<?php

namespace Facile\CodingStandardsTest\RulesMaintenance;

use PhpCsFixer\Console\Application;
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
     * An easy fix is to add them to {@see RulesList::getToBeDiscussedRules()}.
     */
    public function testNoRuleIsUnlisted(): void
    {
        if ($this->isPreferLowest()) {
            $this->markTestSkipped('This test is not reliable with older PHP-CS-Fixer version, due to new rules being added, or included in sets');
        }

        $dumper = new Dumper();
        $unlistedFixerNames = array_keys(iterator_to_array($dumper->getUnlistedRulesDescription()));
        sort($unlistedFixerNames);

        $this->assertEmpty($unlistedFixerNames, implode(\PHP_EOL, ['There are some unlisted rules:', ...$unlistedFixerNames]));
    }

    private function isPreferLowest(): bool
    {
        /** @psalm-suppress InternalClass */
        return version_compare(Application::VERSION, '3.30.0') < 0;
    }
}
