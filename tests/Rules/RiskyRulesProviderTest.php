<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\RiskyRulesProvider;

class RiskyRulesProviderTest extends AbstractRulesProviderTest
{
    public function testRulesAreAlphabeticallySorted(): void
    {
        $this->assertRulesAreAlphabeticallySorted(new RiskyRulesProvider());
    }

    public function testAllRulesAreNotRisky(): void
    {
        $this->assertAllRulesAreRisky(true, new RiskyRulesProvider());
    }
}
