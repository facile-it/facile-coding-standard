<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer;

use Facile\CodingStandards\Rules\ArrayRulesProvider;
use PHPUnit\Framework\TestCase;

class ArrayRulesProviderTest extends TestCase
{
    public function testGetRules(): void
    {
        $rules = ['foo', 'bar'];

        $provider = new ArrayRulesProvider($rules);

        $this->assertSame($rules, $provider->getRules());
    }
}
