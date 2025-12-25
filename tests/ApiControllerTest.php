<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\ApiController;
use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ApiController class.
 */
class ApiControllerTest extends TestCase
{
    private ApiController $controller;

    protected function setUp(): void
    {
        $this->controller = new ApiController();
        Response::reset();
    }

    protected function tearDown(): void
    {
        Response::reset();
    }

    /**
     * Test valid beacon returns 204.
     */
    public function testValidBeaconReturns204(): void
    {
        $result = $this->controller->beacon('abc12345', 'js-executed');

        $this->assertSame('', $result);
        $this->assertSame(204, Response::current()->getStatusCode());
    }

    /**
     * Test X-Debug-Hash header is present.
     */
    public function testXDebugHashHeaderPresent(): void
    {
        $this->controller->beacon('myhash99', 'content-injected');

        $this->assertSame('myhash99', Response::current()->getHeader('X-Debug-Hash'));
    }

    /**
     * Test invalid event returns 400.
     */
    public function testInvalidEventReturns400(): void
    {
        $result = $this->controller->beacon('abc12345', 'invalid-event');

        $this->assertSame('Invalid event', $result);
        $this->assertSame(400, Response::current()->getStatusCode());
    }

    /**
     * Test all valid static events are accepted.
     */
    public function testAllValidStaticEvents(): void
    {
        $validEvents = [
            'js-executed',
            'content-injected',
            'ajax-complete',
            'error-occurred',
            'timer-complete',
            'first-injected',
            'second-injected',
        ];

        foreach ($validEvents as $event) {
            Response::reset();
            $result = $this->controller->beacon('testhash', $event);
            $this->assertSame('', $result, "Event {$event} should be valid");
            $this->assertSame(204, Response::current()->getStatusCode(), "Event {$event} should return 204");
        }
    }

    /**
     * Test ajax-step-{n} pattern is valid.
     */
    public function testAjaxStepPatternValid(): void
    {
        $validPatterns = [
            'ajax-step-1',
            'ajax-step-2',
            'ajax-step-10',
            'ajax-step-99',
        ];

        foreach ($validPatterns as $event) {
            Response::reset();
            $result = $this->controller->beacon('testhash', $event);
            $this->assertSame('', $result, "Event {$event} should be valid");
            $this->assertSame(204, Response::current()->getStatusCode());
        }
    }

    /**
     * Test invalid ajax-step patterns are rejected.
     */
    public function testInvalidAjaxStepPatternsRejected(): void
    {
        $invalidPatterns = [
            'ajax-step-0',     // Zero not allowed
            'ajax-step-',      // Missing number
            'ajax-step-abc',   // Not a number
            'ajax-step--1',    // Negative
        ];

        foreach ($invalidPatterns as $event) {
            Response::reset();
            $result = $this->controller->beacon('testhash', $event);
            $this->assertSame('Invalid event', $result, "Event {$event} should be invalid");
            $this->assertSame(400, Response::current()->getStatusCode());
        }
    }

    /**
     * Test empty body is returned for valid beacon.
     */
    public function testEmptyBodyReturned(): void
    {
        $result = $this->controller->beacon('abc12345', 'ajax-complete');

        $this->assertSame('', $result);
    }
}
