<?php

namespace Facile\CodingStandardsTest\Installer\Provider\SourcePaths;

use Facile\CodingStandards\Installer\Provider\SourcePaths\ArrayProvider;
use Facile\CodingStandards\Installer\Provider\SourcePaths\ProviderInterface;
use PHPUnit\Framework\TestCase;

class ArrayProviderTest extends TestCase
{
    public function testGetSourcePaths()
    {
        $provider1 = $this->prophesize(ProviderInterface::class);
        $provider2 = $this->prophesize(ProviderInterface::class);

        $provider1->getSourcePaths()->shouldBeCalled()->willReturn(['path1-1', 'path1-2']);
        $provider2->getSourcePaths()->shouldBeCalled()->willReturn(['path2-1', 'path2-2']);

        $provider = new ArrayProvider([$provider1->reveal(), $provider2->reveal()]);

        $paths = $provider->getSourcePaths();

        $this->assertEquals(
            [
                'path1-1', 'path1-2',
                'path2-1', 'path2-2',
            ],
            $paths
        );
    }

    public function testGetSourcePathsWithInvalidProvider()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid source path provider provided');

        $provider = new ArrayProvider(['test']);

        $provider->getSourcePaths();
    }
}
