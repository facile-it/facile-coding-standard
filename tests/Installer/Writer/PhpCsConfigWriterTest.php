<?php

namespace Facile\CodingStandardsTest\Installer\Writer;

use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriter;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpCsFixer;
use PHPUnit\Framework\TestCase;

class PhpCsConfigWriterTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfsRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup();
    }

    public function testWriteConfigFile(): void
    {
        \mkdir($this->vfsRoot->url() . '/src');
        \mkdir($this->vfsRoot->url() . '/tests');

        $filename = $this->vfsRoot->url() . '/.php_cs.dist';
        $writer = new PhpCsConfigWriter();

        $writer->writeConfigFile($filename);

        $csConfig = include $filename;

        $this->assertInstanceOf(PhpCsFixer\Config::class, $csConfig);
    }
}
