<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the front controller.
 */
class FrontControllerTest extends TestCase
{
    private static string $appRoot;

    public static function setUpBeforeClass(): void
    {
        self::$appRoot = dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        // Reset response state between tests
        Response::reset();
        http_response_code(200);
    }

    /**
     * Simulate a request to the front controller.
     *
     * @param string $method HTTP method
     * @param string $uri Request URI
     * @return array{code: int, body: string}
     */
    private function request(string $method, string $uri): array
    {
        // Reset response state before each request
        Response::reset();
        http_response_code(200);

        // Save original values
        $originalServer = $_SERVER;

        // Set up request
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;

        // Capture output
        ob_start();

        try {
            $frontController = self::$appRoot . '/public/index.php';
            include $frontController;
        } catch (\Throwable $e) {
            ob_end_clean();
            $_SERVER = $originalServer;
            throw $e;
        }

        $body = ob_get_clean();

        // Restore original values
        $_SERVER = $originalServer;

        // Get response code
        $code = http_response_code() ?: 200;

        return ['code' => $code, 'body' => $body];
    }

    public function testHomePageReturnsContent(): void
    {
        $response = $this->request('GET', '/');

        $this->assertStringContainsString('openseotest.org', $response['body']);
        $this->assertStringContainsString('SEO', $response['body']);
        $this->assertStringContainsString('debug-badge', $response['body']);
    }

    public function testLabPageReturnsContentWithParams(): void
    {
        $response = $this->request('GET', '/lab/js-injection/timeout-500');

        // Lab pages now render full templates with test info
        $this->assertStringContainsString('500ms', $response['body']);
        $this->assertStringContainsString('JavaScript Injection', $response['body']);
        $this->assertStringContainsString('debug-badge', $response['body']);
    }

    public function testNonexistentPageReturns404(): void
    {
        $response = $this->request('GET', '/nonexistent');

        $this->assertSame(404, $response['code']);
        $this->assertStringContainsString('404', $response['body']);
    }

    public function testLabPageWithDifferentParams(): void
    {
        $response = $this->request('GET', '/lab/js-injection/timeout-1000');

        $this->assertStringContainsString('js-injection', $response['body']);
        $this->assertStringContainsString('timeout-1000', $response['body']);
    }
}
