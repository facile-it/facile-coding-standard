<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\DefaultRulesProvider;
use Facile\CodingStandardsTest\Framework\TestCase;

class DefaultRulesProviderTest extends TestCase
{
    public function testRulesAreAlphabeticallySorted(): void
    {
        $provider = new DefaultRulesProvider();

        $rules = $provider->getRules();

        $sortedRules = $rules;
        ksort($sortedRules);
        $this->assertEquals($sortedRules, $rules, 'Rules are not alphabetically sorted');
    }
}
