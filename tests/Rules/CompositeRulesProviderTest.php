<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\CompositeRulesProvider;
use Facile\CodingStandards\Rules\RulesProviderInterface;
use Facile\CodingStandardsTest\Framework\TestCase;

class CompositeRulesProviderTest extends TestCase
{
    public function testGetRules(): void
    {
        $provider1 = $this->prophesize(RulesProviderInterface::class);
        $provider2 = $this->prophesize(RulesProviderInterface::class);

        $provider1->getRules()->willReturn([
            'foo' => true,
            'bar' => ['opt' => true],
            'another' => true,
        ]);

        $provider2->getRules()->willReturn([
            'foo' => true,
            'bar' => false,
            'dummy' => ['opt2' => false],
        ]);

        $provider = new CompositeRulesProvider([
            $provider1->reveal(),
            $provider2->reveal(),
        ]);

        $expected = [
            'foo' => true,
            'bar' => false,
            'another' => true,
            'dummy' => ['opt2' => false],
        ];

        $this->assertSame($expected, $provider->getRules());
    }
}
