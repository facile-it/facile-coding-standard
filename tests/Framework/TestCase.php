<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest\Framework;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

if (! \trait_exists(ProphecyTrait::class)) {
    \class_alias(ProphecyTrait::class, EmptyProphecyTrait::class);
}

class TestCase extends PHPUnitTestCase
{
    use ProphecyTrait;
}
