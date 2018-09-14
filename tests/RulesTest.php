<?php

namespace Facile\CodingStandardsTest;

use Facile\CodingStandards\DefaultRules;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    public function testGetRules()
    {
        $rules = new DefaultRules();
        $this->assertInternalType('array', $rules->getRules());
    }

    public function testOverrideRules()
    {
        $rules = new DefaultRules();
        $this->assertInternalType('array', $rules->getRules());

        // get first rule
        $rulesDefinition = $rules->getRules();
        $rulesDefinitionKeys = \array_keys($rulesDefinition);
        $ruleKey = \array_shift($rulesDefinitionKeys);
        $this->assertArrayHasKey($ruleKey, $rulesDefinition);

        $overridedRules = new DefaultRules([$ruleKey => 'foobar']);
        $this->assertArrayHasKey($ruleKey, $overridedRules->getRules());
        $this->assertEquals('foobar', $overridedRules->getRules()[$ruleKey]);
    }
}
