<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Installer\Writer;

use Facile\CodingStandards\Installer\Writer\PhpCsConfigWriter;
use Facile\CodingStandardsTest\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

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
        $filename = $this->vfsRoot->url() . '/.php-cs-fixer.dist.php';
        $writer = new PhpCsConfigWriter();

        $writer->writeConfigFile($filename);

        $content = file_get_contents($filename);

        $expected = <<<'TEXT'
            <?php

            /*
             * Additional rules or rules to override.
             * These rules will be added to default rules or will override them if the same key already exists.
             */

            $additionalRules = [];
            $rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
                new Facile\CodingStandards\Rules\DefaultRulesProvider(),
                new Facile\CodingStandards\Rules\RiskyRulesProvider(),
                new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
            ]);

            $config = new PhpCsFixer\Config();
            $config->setRules($rulesProvider->getRules());

            $finder = new PhpCsFixer\Finder();

            /*
             * You can set manually these paths:
             */
            $autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
            $finder->in($autoloadPathProvider->getPaths());

            $config->setFinder($finder);

            return $config;

            TEXT;

        $this->assertSame($expected, $content);
    }

    public function testWriteConfigFileWithNoDev(): void
    {
        $filename = $this->vfsRoot->url() . '/.php-cs-fixer.dist.php';
        $writer = new PhpCsConfigWriter();

        $writer->writeConfigFile($filename, true);

        $content = file_get_contents($filename);

        $expected = <<<'TEXT'
            <?php

            /*
             * Additional rules or rules to override.
             * These rules will be added to default rules or will override them if the same key already exists.
             */

            $additionalRules = [];
            $rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
                new Facile\CodingStandards\Rules\DefaultRulesProvider(),
                new Facile\CodingStandards\Rules\RiskyRulesProvider(),
                new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
            ]);

            $config = new PhpCsFixer\Config();
            $config->setRules($rulesProvider->getRules());

            $finder = new PhpCsFixer\Finder();

            /*
             * You can set manually these paths:
             */
            $autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider(null, null, false);
            $finder->in($autoloadPathProvider->getPaths());

            $config->setFinder($finder);

            return $config;

            TEXT;

        $this->assertSame($expected, $content);
    }

    public function testWriteConfigFileWithNoRisky(): void
    {
        $filename = $this->vfsRoot->url() . '/.php-cs-fixer.dist.php';
        $writer = new PhpCsConfigWriter();

        $writer->writeConfigFile($filename, false, true);

        $content = file_get_contents($filename);

        $expected = <<<'TEXT'
            <?php

            /*
             * Additional rules or rules to override.
             * These rules will be added to default rules or will override them if the same key already exists.
             */

            $additionalRules = [];
            $rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
                new Facile\CodingStandards\Rules\DefaultRulesProvider(),
                new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
            ]);

            $config = new PhpCsFixer\Config();
            $config->setRules($rulesProvider->getRules());

            $finder = new PhpCsFixer\Finder();

            /*
             * You can set manually these paths:
             */
            $autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
            $finder->in($autoloadPathProvider->getPaths());

            $config->setFinder($finder);

            return $config;

            TEXT;

        $this->assertSame($expected, $content);
    }

    public function testWriteConfigFileWithNoDevAndNoRisky(): void
    {
        $filename = $this->vfsRoot->url() . '/.php-cs-fixer.dist.php';
        $writer = new PhpCsConfigWriter();

        $writer->writeConfigFile($filename, true, true);

        $content = file_get_contents($filename);

        $expected = <<<'TEXT'
            <?php

            /*
             * Additional rules or rules to override.
             * These rules will be added to default rules or will override them if the same key already exists.
             */

            $additionalRules = [];
            $rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
                new Facile\CodingStandards\Rules\DefaultRulesProvider(),
                new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
            ]);

            $config = new PhpCsFixer\Config();
            $config->setRules($rulesProvider->getRules());

            $finder = new PhpCsFixer\Finder();

            /*
             * You can set manually these paths:
             */
            $autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider(null, null, false);
            $finder->in($autoloadPathProvider->getPaths());

            $config->setFinder($finder);

            return $config;

            TEXT;

        $this->assertSame($expected, $content);
    }
}
