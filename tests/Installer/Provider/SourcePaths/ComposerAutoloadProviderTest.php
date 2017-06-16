<?php

namespace Facile\CodingStandardsTest\Installer\Provider\SourcePaths;

use Composer\Composer;
use Facile\CodingStandards\Installer\Provider\SourcePaths\ComposerAutoloadProvider;
use PHPUnit\Framework\TestCase;

class ComposerAutoloadProviderTest extends TestCase
{
    public function testGetSourcePaths()
    {
        $autoload = [
            'psr-0' => [
                'Foo/' => 'foo/',
                'Bar/' => [
                    'src/foo',
                    'tests/foo',
                ],
            ],
            'psr-4' => [
                'FooBar/' => 'foobar/',
                'BarFoo/' => [
                    'foo/',
                    'src/foobar',
                    'tests/barfoo',
                ],
            ],
            'class_map' => [],
        ];
        $provider = new ComposerAutoloadProvider($autoload);

        $paths = $provider->getSourcePaths();

        $this->assertCount(6, $paths);
        $this->assertContains('foo/', $paths);
        $this->assertContains('src/foo', $paths);
        $this->assertContains('tests/foo', $paths);
        $this->assertContains('foobar/', $paths);
        $this->assertContains('src/foobar', $paths);
        $this->assertContains('tests/barfoo', $paths);
    }

    public function testGetSourcePathWithInvalidAutoloadFormat()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageRegExp('/composer autoloader section does not contain an array/');
        $autoload = [
            'psr-0' => 'foo',
        ];
        $provider = new ComposerAutoloadProvider($autoload);

        $provider->getSourcePaths();
    }
}
