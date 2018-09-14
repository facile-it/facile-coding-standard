<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest;

use Facile\CodingStandards\AutoloadPathProvider;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class AutoloadPathProviderTest extends TestCase
{
    /**
     * @var string
     */
    private $composerFilePath;

    /**
     * @var string
     */
    private $projectRoot;

    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup();

        $this->projectRoot = $this->vfsRoot->url();
        $this->composerFilePath = $this->vfsRoot->url() . '/composer.json';
        \mkdir($this->vfsRoot->url() . '/src');
        \mkdir($this->vfsRoot->url() . '/tests');
        \file_put_contents($this->composerFilePath, Util::getComposerContent());
    }

    public function testGetPathsWithDevOn(): void
    {
        $provider = new AutoloadPathProvider(
            $this->composerFilePath,
            $this->projectRoot,
            true
        );

        $expected = ['src/', 'tests/'];
        $this->assertSame($expected, $provider->getPaths());
    }

    public function testGetPathsWithDevOff(): void
    {
        $provider = new AutoloadPathProvider(
            $this->composerFilePath,
            $this->projectRoot,
            false
        );

        $expected = ['src/'];
        $this->assertSame($expected, $provider->getPaths());
    }
}
