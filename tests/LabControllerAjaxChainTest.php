<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\LabController;
use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests for LabController AJAX chain endpoint.
 */
class LabControllerAjaxChainTest extends TestCase
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
     * Test step 1 returns first product.
     */
    public function testStep1ReturnsFirstProduct(): void
    {
        $result = $this->controller->ajaxChainFetch('3', 'abc12345', '1');
        $data = json_decode($result, true);

        $this->assertSame(1, $data['step']);
        $this->assertArrayHasKey('product', $data);
        $this->assertSame('PixelPulse Earbuds', $data['product']['name']);
        $this->assertSame('$79.99', $data['product']['price']);
        $this->assertTrue($data['hasMore']);
    }

    /**
     * Test step 3 returns third product.
     */
    public function testStep3ReturnsThirdProduct(): void
    {
        $result = $this->controller->ajaxChainFetch('5', 'abc12345', '3');
        $data = json_decode($result, true);

        $this->assertSame(3, $data['step']);
        $this->assertSame('PixelPulse Speaker', $data['product']['name']);
        $this->assertSame('$149.99', $data['product']['price']);
        $this->assertTrue($data['hasMore']);
    }

    /**
     * Test hasMore is false on last step (3-step chain).
     */
    public function testHasMoreFalseOnLastStep3(): void
    {
        $result = $this->controller->ajaxChainFetch('3', 'abc12345', '3');
        $data = json_decode($result, true);

        $this->assertSame(3, $data['step']);
        $this->assertFalse($data['hasMore']);
    }

    /**
     * Test hasMore is false on last step (5-step chain).
     */
    public function testHasMoreFalseOnLastStep5(): void
    {
        $result = $this->controller->ajaxChainFetch('5', 'abc12345', '5');
        $data = json_decode($result, true);

        $this->assertSame(5, $data['step']);
        $this->assertFalse($data['hasMore']);
    }

    /**
     * Test hasMore is true on non-last step.
     */
    public function testHasMoreTrueOnNonLastStep(): void
    {
        $result = $this->controller->ajaxChainFetch('5', 'abc12345', '4');
        $data = json_decode($result, true);

        $this->assertTrue($data['hasMore']);
    }

    /**
     * Test invalid step returns 400.
     */
    public function testInvalidStepReturns400(): void
    {
        // Step 0 is invalid
        $result = $this->controller->ajaxChainFetch('3', 'abc12345', '0');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertSame('Invalid step value', $data['error']);

        Response::reset();

        // Step 4 is invalid for 3-step chain
        $result = $this->controller->ajaxChainFetch('3', 'abc12345', '4');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertSame('Invalid step value', $data['error']);

        Response::reset();

        // Step 6 is invalid for 5-step chain
        $result = $this->controller->ajaxChainFetch('5', 'abc12345', '6');
        $data = json_decode($result, true);
        $this->assertSame(400, Response::current()->getStatusCode());
    }

    /**
     * Test invalid steps value returns 400.
     */
    public function testInvalidStepsReturns400(): void
    {
        $result = $this->controller->ajaxChainFetch('4', 'abc12345', '1');
        $data = json_decode($result, true);

        $this->assertSame(400, Response::current()->getStatusCode());
        $this->assertSame('Invalid steps value', $data['error']);
    }

    /**
     * Test X-Debug-Hash header present.
     */
    public function testXDebugHashHeaderPresent(): void
    {
        $this->controller->ajaxChainFetch('3', 'deadbeef', '1');

        $this->assertSame('deadbeef', Response::current()->getHeader('X-Debug-Hash'));
    }

    /**
     * Test hash is included in response.
     */
    public function testHashInResponse(): void
    {
        $result = $this->controller->ajaxChainFetch('3', 'deadbeef', '1');
        $data = json_decode($result, true);

        $this->assertSame('deadbeef', $data['hash']);
    }

    /**
     * Test product includes image URL.
     */
    public function testProductIncludesImageUrl(): void
    {
        $result = $this->controller->ajaxChainFetch('3', 'abc12345', '1');
        $data = json_decode($result, true);

        $this->assertArrayHasKey('image', $data['product']);
        $this->assertStringContainsString('picsum.photos', $data['product']['image']);
    }

    /**
     * Test response has ~500ms delay.
     */
    public function testResponseHas500msDelay(): void
    {
        $startTime = microtime(true);
        $this->controller->ajaxChainFetch('3', 'abc12345', '1');
        $elapsed = (microtime(true) - $startTime) * 1000;

        $this->assertGreaterThanOrEqual(450, $elapsed);
        $this->assertLessThan(1000, $elapsed);
    }
}
