<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\LabController;
use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests for LabController AJAX endpoint.
 */
class LabControllerAjaxTest extends TestCase
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
     * Test endpoint returns JSON.
     */
    public function testEndpointReturnsJson(): void
    {
        // Use minimum delay to speed up test
        $result = $this->controller->ajaxFetch('500', 'abc12345');

        $this->assertJson($result);
        $this->assertSame('application/json', Response::current()->getHeader('Content-Type'));
    }

    /**
     * Test response includes price and availability.
     */
    public function testResponseIncludesPriceAndAvailability(): void
    {
        $result = $this->controller->ajaxFetch('500', 'abc12345');
        $data = json_decode($result, true);

        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('availability', $data);
        $this->assertArrayHasKey('shipping', $data);
        $this->assertArrayHasKey('hash', $data);

        $this->assertSame('$299.99', $data['price']);
        $this->assertSame('In Stock', $data['availability']);
        $this->assertSame('Free 2-day shipping', $data['shipping']);
        $this->assertSame('abc12345', $data['hash']);
    }

    /**
     * Test X-Debug-Hash header is present.
     */
    public function testXDebugHashHeaderPresent(): void
    {
        $this->controller->ajaxFetch('500', 'deadbeef');

        $this->assertSame('deadbeef', Response::current()->getHeader('X-Debug-Hash'));
    }

    /**
     * Test invalid delay returns 400.
     */
    public function testInvalidDelayReturns400(): void
    {
        $result = $this->controller->ajaxFetch('999', 'abc12345');
        $data = json_decode($result, true);

        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertArrayHasKey('error', $data);
        $this->assertSame('Invalid delay value', $data['error']);
    }

    /**
     * Test non-numeric delay returns 400.
     */
    public function testNonNumericDelayReturns400(): void
    {
        $result = $this->controller->ajaxFetch('abc', 'abc12345');
        $data = json_decode($result, true);

        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * Test all valid delays are accepted.
     */
    public function testAllValidDelaysAccepted(): void
    {
        $validDelays = ['500', '1000', '2000', '3000', '4000', '5000'];

        foreach ($validDelays as $delay) {
            Response::reset();
            $result = $this->controller->ajaxFetch($delay, 'abc12345');
            $data = json_decode($result, true);

            $this->assertArrayNotHasKey('error', $data, "Delay {$delay} should be valid");
            $this->assertArrayHasKey('price', $data);
        }
    }

    /**
     * Test invalid hash format returns 400.
     */
    public function testInvalidHashFormatReturns400(): void
    {
        // Too short
        $result = $this->controller->ajaxFetch('500', 'abc');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertSame('Invalid hash format', $data['error']);

        Response::reset();

        // Invalid characters
        $result = $this->controller->ajaxFetch('500', 'ABCD1234');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertSame('Invalid hash format', $data['error']);

        Response::reset();

        // Too long
        $result = $this->controller->ajaxFetch('500', 'abc123456789');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
    }

    /**
     * Test response time is approximately correct.
     * Note: This test uses a tolerance to account for PHP overhead.
     */
    public function testResponseTimeApproximatelyCorrect(): void
    {
        $startTime = microtime(true);
        $this->controller->ajaxFetch('500', 'abc12345');
        $elapsed = (microtime(true) - $startTime) * 1000;

        // Allow 50ms tolerance for overhead
        $this->assertGreaterThanOrEqual(450, $elapsed, 'Response should take at least ~500ms');
        $this->assertLessThan(1000, $elapsed, 'Response should not take more than 1s');
    }
}
