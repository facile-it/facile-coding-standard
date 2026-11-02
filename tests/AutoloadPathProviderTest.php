<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest;

use Facile\CodingStandards\AutoloadPathProvider;
use Facile\CodingStandardsTest\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class AutoloadPathProviderTest extends TestCase
{
    private string $composerFilePath;

    private string $projectRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $vfsRoot = vfsStream::setup();

        $this->projectRoot = $vfsRoot->url();
        $this->composerFilePath = $vfsRoot->url() . '/composer.json';
        mkdir($vfsRoot->url() . '/src');
        mkdir($vfsRoot->url() . '/tests');
        file_put_contents($this->composerFilePath, Util::getComposerContent());
    }

    public function testGetPathsWithDevOn(): void
    {
        $provider = new AutoloadPathProvider(
            $this->composerFilePath,
            $this->projectRoot,
            true,
        );

        $expected = ['src/', 'tests/'];
        $this->assertSame($expected, $provider->getPaths());
    }

    public function testGetPathsWithDevOff(): void
    {
        $provider = new AutoloadPathProvider(
            $this->composerFilePath,
            $this->projectRoot,
            false,
        );

        $expected = ['src/'];
        $this->assertSame($expected, $provider->getPaths());
    }

    public function testGetPathsWithDefault(): void
    {
        $provider = new AutoloadPathProvider();

        $expected = ['src/', 'tests/'];
        $this->assertSame($expected, $provider->getPaths());
    }

    public function testGetPathsWithWrongComposerJsonPath(): void
    {
        $provider = new AutoloadPathProvider(__DIR__ . '/composer.json');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to find composer.json');

        $provider->getPaths();
    }

    public function testGetPathsWithInvalidComposerJson(): void
    {
        $provider = new AutoloadPathProvider(
            $this->composerFilePath,
            $this->projectRoot,
            false,
        );

        file_put_contents($this->composerFilePath, '');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid composer.json file');

        $provider->getPaths();
    }

    public function testWrongComposerPathLeadsToBrokenProjectPath(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to get project root');

        new AutoloadPathProvider('wrong/composer/path/');
    }
}
