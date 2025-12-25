<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Controllers\LabController;
use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for all AJAX delay variations.
 */
class AjaxDelayTest extends TestCase
{
    private LabController $controller;

    protected function setUp(): void
    {
        $this->controller = new LabController();
        Response::reset();
    }

    protected function tearDown(): void
    {
        Response::reset();
    }

    /**
     * Test each delay endpoint returns correct timing.
     * Note: Uses a tolerance to account for PHP overhead.
     *
     * @dataProvider delayProvider
     */
    public function testDelayEndpointTiming(int $delay): void
    {
        $startTime = microtime(true);
        $this->controller->ajaxFetch((string) $delay, 'abc12345');
        $elapsed = (microtime(true) - $startTime) * 1000;

        // Allow 100ms tolerance for overhead
        $this->assertGreaterThanOrEqual($delay - 100, $elapsed, "Response should take at least ~{$delay}ms");
        $this->assertLessThan($delay + 500, $elapsed, "Response should not take more than {$delay}ms + 500ms overhead");
    }

    /**
     * Test each delay returns different product data.
     *
     * @dataProvider delayProvider
     */
    public function testDelayReturnsProductData(int $delay): void
    {
        $result = $this->controller->ajaxFetch((string) $delay, 'abc12345');
        $data = json_decode($result, true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('availability', $data);
        $this->assertArrayHasKey('shipping', $data);
        $this->assertArrayHasKey('hash', $data);

        // Verify price format
        $this->assertMatchesRegularExpression('/^\$\d+\.\d{2}$/', $data['price']);

        // Verify availability is non-empty
        $this->assertNotEmpty($data['availability']);

        // Verify shipping is non-empty
        $this->assertNotEmpty($data['shipping']);
    }

    /**
     * Test that different delays return different prices.
     */
    public function testDifferentDelaysReturnDifferentPrices(): void
    {
        $prices = [];

        foreach ([500, 1000, 2000, 3000, 4000, 5000] as $delay) {
            Response::reset();
            $result = $this->controller->ajaxFetch((string) $delay, 'abc12345');
            $data = json_decode($result, true);
            $prices[$delay] = $data['price'];
        }

        // At least some prices should be different (we have variety)
        $uniquePrices = array_unique($prices);
        $this->assertGreaterThan(3, count($uniquePrices), 'Should have at least 4 different prices');
    }

    /**
     * Test beacons would fire correctly (hash is present).
     *
     * @dataProvider delayProvider
     */
    public function testHashPresentInResponse(int $delay): void
    {
        $testHash = 'deadbeef';
        $result = $this->controller->ajaxFetch((string) $delay, $testHash);
        $data = json_decode($result, true);

        $this->assertSame($testHash, $data['hash']);
        $this->assertSame($testHash, Response::current()->getHeader('X-Debug-Hash'));
    }

    /**
     * Data provider for delay values.
     */
    public static function delayProvider(): array
    {
        return [
            'delay-500' => [500],
            'delay-1000' => [1000],
            'delay-2000' => [2000],
            'delay-3000' => [3000],
            'delay-4000' => [4000],
            'delay-5000' => [5000],
        ];
    }
}
