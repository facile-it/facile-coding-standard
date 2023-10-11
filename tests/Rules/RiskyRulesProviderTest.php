<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\RiskyRulesProvider;

class RiskyRulesProviderTest extends AbstractRulesProviderTest
{
    protected static function getRulesProvider(): RiskyRulesProvider
    {
        return new RiskyRulesProvider();
    }

    protected function shouldBeRisky(): bool
    {
        return true;
    }
}
