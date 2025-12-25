<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the article template.
 */
class ArticleTemplateTest extends TestCase
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

    public function testJsInjectionDomcontentinitRendersArticle(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('The Evolution of Wireless Technology', $response['body']);
        $this->assertStringContainsString('PixelPulse', $response['body']);
        $this->assertStringContainsString('article', $response['body']);
    }

    public function testArticlePageIncludesInjectedContentDiv(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertStringContainsString('id="injected-content"', $response['body']);
    }

    public function testArticlePageIncludesDebugBadge(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertStringContainsString('debug-badge', $response['body']);
        // Debug hash should be 8 hex characters
        $this->assertMatchesRegularExpression('/Debug:\s*[a-f0-9]{8}/i', $response['body']);
    }

    public function testArticlePageIncludesDataAttributes(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertStringContainsString('data-test="domcontentinit"', $response['body']);
        $this->assertStringContainsString('data-category="js-injection"', $response['body']);
        $this->assertMatchesRegularExpression('/data-hash="[a-f0-9]{8}"/', $response['body']);
    }

    public function testArticlePageIncludesTestInfo(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertStringContainsString('Test Information', $response['body']);
        $this->assertStringContainsString('JavaScript Injection', $response['body']);
        $this->assertStringContainsString('DOMContentLoaded Injection', $response['body']);
    }

    public function testArticlePageIncludesNavigation(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        $this->assertStringContainsString('Back to Home', $response['body']);
        $this->assertStringContainsString('href="/"', $response['body']);
    }

    public function testArticlePageIncludesNextTestLink(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        // domcontentinit is the first test, so next should be timeout-100
        $this->assertStringContainsString('Next Test', $response['body']);
        $this->assertStringContainsString('/lab/js-injection/timeout-100', $response['body']);
    }

    public function testTimeoutTestIncludesDelayData(): void
    {
        $response = $this->request('GET', '/lab/js-injection/timeout-500');

        $this->assertStringContainsString('data-delay="500"', $response['body']);
        $this->assertStringContainsString('500ms', $response['body']);
    }

    public function testAllTimeoutVariationsWork(): void
    {
        $timeouts = [100, 250, 500, 1000, 2000];

        foreach ($timeouts as $timeout) {
            $response = $this->request('GET', "/lab/js-injection/timeout-{$timeout}");

            $this->assertSame(200, $response['code'], "Failed for timeout-{$timeout}");
            $this->assertStringContainsString("data-delay=\"{$timeout}\"", $response['body']);
            $this->assertStringContainsString("{$timeout}ms", $response['body']);
            $this->assertStringContainsString('js-injection.js', $response['body']);
        }
    }

    public function testInvalidTestReturns404(): void
    {
        $response = $this->request('GET', '/lab/js-injection/nonexistent-test');

        $this->assertSame(404, $response['code']);
        $this->assertStringContainsString('Test Not Found', $response['body']);
    }

    public function testInvalidCategoryReturns404(): void
    {
        $response = $this->request('GET', '/lab/nonexistent-category/some-test');

        $this->assertSame(404, $response['code']);
        $this->assertStringContainsString('Test Not Found', $response['body']);
    }

    public function testArticlePageIncludesTemplateCss(): void
    {
        $response = $this->request('GET', '/lab/js-injection/domcontentinit');

        // Should include template-specific CSS
        $this->assertMatchesRegularExpression('/templates\/article\.css/', $response['body']);
    }

    public function testLastTestDoesNotShowNextLink(): void
    {
        $response = $this->request('GET', '/lab/js-injection/timeout-2000');

        // timeout-2000 is the last test in js-injection category
        $this->assertStringNotContainsString('Next Test', $response['body']);
    }
}
