<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Asset;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Asset class.
 */
class AssetTest extends TestCase
{
    private const TEST_HASH = 'abc12345';

    /**
     * Test css() returns correct path with hash.
     */
    public function testCssReturnsCorrectPath(): void
    {
        $asset = new Asset(self::TEST_HASH);
        $this->assertSame('/dist/abc12345/css/site.css', $asset->css('site.css'));
    }

    /**
     * Test js() returns correct path with hash.
     */
    public function testJsReturnsCorrectPath(): void
    {
        $asset = new Asset(self::TEST_HASH);
        $this->assertSame('/dist/abc12345/js/site.js', $asset->js('site.js'));
    }

    /**
     * Test url() for arbitrary paths.
     */
    public function testUrlReturnsCorrectPath(): void
    {
        $asset = new Asset(self::TEST_HASH);
        $this->assertSame('/dist/abc12345/images/logo.png', $asset->url('images/logo.png'));
    }

    /**
     * Test that different hashes produce different URLs.
     */
    public function testDifferentHashesDifferentUrls(): void
    {
        $asset1 = new Asset('hash1111');
        $asset2 = new Asset('hash2222');

        $this->assertNotSame($asset1->css('site.css'), $asset2->css('site.css'));
        $this->assertSame('/dist/hash1111/css/site.css', $asset1->css('site.css'));
        $this->assertSame('/dist/hash2222/css/site.css', $asset2->css('site.css'));
    }

    /**
     * Test getHash() returns the hash.
     */
    public function testGetHashReturnsHash(): void
    {
        $asset = new Asset(self::TEST_HASH);
        $this->assertSame(self::TEST_HASH, $asset->getHash());
    }

    /**
     * Test nested paths work correctly.
     */
    public function testNestedPaths(): void
    {
        $asset = new Asset(self::TEST_HASH);
        $this->assertSame(
            '/dist/abc12345/js/tests/ajax.js',
            $asset->url('js/tests/ajax.js')
        );
    }
}
