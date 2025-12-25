<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Response class.
 */
class ResponseTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset singleton before each test
        Response::reset();
    }

    protected function tearDown(): void
    {
        // Clean up singleton after each test
        Response::reset();
    }

    /**
     * Test setHeader stores header.
     */
    public function testSetHeaderStoresHeader(): void
    {
        $response = Response::current();
        $response->setHeader('X-Custom', 'test-value');

        $this->assertSame('test-value', $response->getHeader('X-Custom'));
    }

    /**
     * Test setStatusCode stores code.
     */
    public function testSetStatusCodeStoresCode(): void
    {
        $response = Response::current();
        $response->setStatusCode(404);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * Test default status code is 200.
     */
    public function testDefaultStatusCode(): void
    {
        $response = Response::current();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Test singleton returns same instance.
     */
    public function testSingletonReturnsSameInstance(): void
    {
        $response1 = Response::current();
        $response2 = Response::current();

        $this->assertSame($response1, $response2);
    }

    /**
     * Test reset creates new instance.
     */
    public function testResetCreatesNewInstance(): void
    {
        $response1 = Response::current();
        $response1->setHeader('X-Test', 'value');

        Response::reset();

        $response2 = Response::current();
        $this->assertNull($response2->getHeader('X-Test'));
    }

    /**
     * Test getHeaders returns all headers.
     */
    public function testGetHeadersReturnsAllHeaders(): void
    {
        $response = Response::current();
        $response->setHeader('X-First', 'one');
        $response->setHeader('X-Second', 'two');

        $headers = $response->getHeaders();

        $this->assertSame([
            'X-First' => 'one',
            'X-Second' => 'two',
        ], $headers);
    }

    /**
     * Test hasHeader checks for header existence.
     */
    public function testHasHeader(): void
    {
        $response = Response::current();
        $response->setHeader('X-Exists', 'yes');

        $this->assertTrue($response->hasHeader('X-Exists'));
        $this->assertFalse($response->hasHeader('X-Missing'));
    }

    /**
     * Test getHeader returns null for missing header.
     */
    public function testGetHeaderReturnsNullForMissing(): void
    {
        $response = Response::current();
        $this->assertNull($response->getHeader('X-Missing'));
    }
}
