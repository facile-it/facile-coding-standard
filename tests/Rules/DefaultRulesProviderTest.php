<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\DefaultRulesProvider;

use const false;

class DefaultRulesProviderTest extends AbstractRulesProviderTest
{
    public function testRulesAreAlphabeticallySorted(): void
    {
        $this->assertRulesAreAlphabeticallySorted(new DefaultRulesProvider());
    }

    public function testAllRulesAreNotRisky(): void
    {
        $this->assertAllRulesAreRisky(false, new DefaultRulesProvider());
    }
}
