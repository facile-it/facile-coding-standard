<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Rules;

use Facile\CodingStandards\Rules\SingleLineVarPhpdocFixer;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

class SingleLineVarPhpdocFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, string $input): void
    {
        $this->doTest($expected, $input);
    }

    protected function createFixer()
    {
        return new SingleLineVarPhpdocFixer();
    }

    public function provideFixCases(): \Generator
    {
        $input = <<<'PHP'
        <?php
        
        namespace Project\TheNamespace;
        
        class TheClass {
        
            /** 
             * @var string  
             */
            private $var;

            /** 
             * My super cool property
             * @var string  
             */
            private $superCoolProperty;

            public function getVar(): string 
            {
                return $this->var;
            }
        }
PHP;

        $expected = <<<'PHP'
        <?php
        
        namespace Project\TheNamespace;
        
        class TheClass {
        
            /** @var string */
            private $var;

            /** 
             * My super cool property
             * @var string  
             */
            private $superCoolProperty;

            public function getVar(): string 
            {
                return $this->var;
            }
        }
PHP;

        yield [$expected, $input];
    }
}
