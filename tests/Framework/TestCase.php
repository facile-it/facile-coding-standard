<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Framework;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TestCase extends PHPUnitTestCase
{
    use ProphecyTrait;
}
