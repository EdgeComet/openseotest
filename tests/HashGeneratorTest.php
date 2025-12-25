<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\HashGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the HashGenerator class.
 */
class HashGeneratorTest extends TestCase
{
    /**
     * Test that generate() returns an 8-character string.
     */
    public function testGenerateReturns8Characters(): void
    {
        $hash = HashGenerator::generate();
        $this->assertSame(8, strlen($hash));
    }

    /**
     * Test that generate() returns only lowercase hex characters [a-f0-9].
     */
    public function testGenerateReturnsOnlyLowercaseHex(): void
    {
        $hash = HashGenerator::generate();
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}$/', $hash);
    }

    /**
     * Test that generate() returns different values on each call.
     * With 4 bytes of entropy, collision in 10 attempts is astronomically unlikely.
     */
    public function testGenerateReturnsDifferentValues(): void
    {
        $hashes = [];
        for ($i = 0; $i < 10; $i++) {
            $hashes[] = HashGenerator::generate();
        }

        // All 10 hashes should be unique
        $uniqueHashes = array_unique($hashes);
        $this->assertCount(10, $uniqueHashes, 'All generated hashes should be unique');
    }

    /**
     * Test that the format matches expected hex pattern consistently.
     */
    public function testFormatConsistency(): void
    {
        // Generate multiple hashes and verify format
        for ($i = 0; $i < 5; $i++) {
            $hash = HashGenerator::generate();
            $this->assertSame(8, strlen($hash), 'Hash length should always be 8');
            $this->assertMatchesRegularExpression(
                '/^[a-f0-9]{8}$/',
                $hash,
                'Hash should match hex pattern'
            );
        }
    }
}
