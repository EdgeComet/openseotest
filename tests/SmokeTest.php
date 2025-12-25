<?php

declare(strict_types=1);

namespace Ost\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Smoke test to verify PHPUnit is working correctly.
 */
class SmokeTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        $this->assertTrue(true);
    }
}
