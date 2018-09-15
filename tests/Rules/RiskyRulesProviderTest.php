<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer;

use Facile\CodingStandards\Rules\RiskyRulesProvider;
use PHPUnit\Framework\TestCase;

class RiskyRulesProviderTest extends TestCase
{
    public function testGetRules(): void
    {
        $provider = new RiskyRulesProvider();

        $this->assertInternalType('array', $provider->getRules());
    }
}
