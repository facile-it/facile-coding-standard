<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\DefaultRulesProvider;
use PHPUnit\Framework\TestCase;

class DefaultRulesProviderTest extends TestCase
{
    public function testGetRules(): void
    {
        $provider = new DefaultRulesProvider();

        $this->assertInternalType('array', $provider->getRules());
    }
}
