<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\ArrayRulesProvider;
use Facile\CodingStandardsTest\Framework\TestCase;

class ArrayRulesProviderTest extends TestCase
{
    public function testGetRules(): void
    {
        $rules = ['foo' => true, 'bar' => ['baz' => 'foobar']];

        $provider = new ArrayRulesProvider($rules);

        $this->assertSame($rules, $provider->getRules());
    }
}
