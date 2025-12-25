<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for catalog, review, and tool templates.
 */
class RemainingTemplatesTest extends TestCase
{
    private static string $appRoot;

    public static function setUpBeforeClass(): void
    {
        self::$appRoot = dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        Response::reset();
        http_response_code(200);
    }

    private function request(string $method, string $uri): array
    {
        Response::reset();
        http_response_code(200);

        $originalServer = $_SERVER;
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;

        ob_start();

        try {
            include self::$appRoot . '/public/index.php';
        } catch (\Throwable $e) {
            ob_end_clean();
            $_SERVER = $originalServer;
            throw $e;
        }

        $body = ob_get_clean();
        $_SERVER = $originalServer;
        $code = http_response_code() ?: 200;

        return ['code' => $code, 'body' => $body];
    }

    // ========== Catalog Template Tests ==========

    public function testAjaxChainRendersCatalogPage(): void
    {
        $response = $this->request('GET', '/lab/ajax-chain/3-steps');

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('catalog-page', $response['body']);
        $this->assertStringContainsString('PixelPulse Product Catalog', $response['body']);
    }

    public function testCatalogPageHasProductSlots(): void
    {
        $response = $this->request('GET', '/lab/ajax-chain/3-steps');

        $this->assertStringContainsString('product-slot', $response['body']);
        $this->assertStringContainsString('id="product-slot-1"', $response['body']);
        $this->assertStringContainsString('id="product-slot-2"', $response['body']);
        $this->assertStringContainsString('id="product-slot-3"', $response['body']);
    }

    public function testCatalogPageHasCorrectStepsData(): void
    {
        $response = $this->request('GET', '/lab/ajax-chain/3-steps');
        $this->assertStringContainsString('data-steps="3"', $response['body']);

        $response = $this->request('GET', '/lab/ajax-chain/5-steps');
        $this->assertStringContainsString('data-steps="5"', $response['body']);
    }

    public function testCatalogPageIncludesTemplateCss(): void
    {
        $response = $this->request('GET', '/lab/ajax-chain/3-steps');
        $this->assertMatchesRegularExpression('/templates\/catalog\.css/', $response['body']);
    }

    public function testCatalogPageHasDebugBadge(): void
    {
        $response = $this->request('GET', '/lab/ajax-chain/3-steps');
        $this->assertStringContainsString('debug-badge', $response['body']);
    }

    // ========== Review Template Tests ==========

    public function testJsErrorsRendersReviewPage(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('review-page', $response['body']);
        $this->assertStringContainsString('Customer Reviews', $response['body']);
    }

    public function testReviewPageHasInjectionPlaceholders(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');

        $this->assertStringContainsString('id="injected-content-1"', $response['body']);
        $this->assertStringContainsString('id="injected-content-2"', $response['body']);
    }

    public function testReviewPageHasStaticReviews(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');

        $this->assertStringContainsString('John Doe', $response['body']);
        $this->assertStringContainsString('Sarah Mitchell', $response['body']);
    }

    public function testReviewPageShowsTestSpecificWarning(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');
        $this->assertStringContainsString('syntax error', $response['body']);

        $response = $this->request('GET', '/lab/js-errors/runtime-before');
        $this->assertStringContainsString('runtime error', $response['body']);

        $response = $this->request('GET', '/lab/js-errors/error-between');
        $this->assertStringContainsString('FIRST review should load', $response['body']);
    }

    public function testReviewPageIncludesTemplateCss(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');
        $this->assertMatchesRegularExpression('/templates\/review\.css/', $response['body']);
    }

    public function testReviewPageHasGlobalHashVariable(): void
    {
        $response = $this->request('GET', '/lab/js-errors/syntax-before');
        $this->assertMatchesRegularExpression('/window\.ostHash\s*=\s*\'[a-f0-9]{8}\'/', $response['body']);
    }

    // ========== Tool Template Tests ==========

    public function testRealtimeRendersToolPage(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('tool-page', $response['body']);
        $this->assertStringContainsString('Performance Monitor', $response['body']);
    }

    public function testToolPageHasTimerDisplay(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');

        $this->assertStringContainsString('timer-display', $response['body']);
        $this->assertStringContainsString('id="timer-display"', $response['body']);
    }

    public function testToolPageHasSystemStatusElements(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');

        $this->assertStringContainsString('id="system-status"', $response['body']);
        $this->assertStringContainsString('id="system-uptime"', $response['body']);
        $this->assertStringContainsString('id="system-latency"', $response['body']);
    }

    public function testToolPageHasProgressBar(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');

        $this->assertStringContainsString('progress-bar', $response['body']);
        $this->assertStringContainsString('id="timer-progress"', $response['body']);
    }

    public function testToolPageHasActivityLog(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');

        $this->assertStringContainsString('activity-log', $response['body']);
        $this->assertStringContainsString('id="activity-log"', $response['body']);
    }

    public function testToolPageHasDurationData(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');
        $this->assertStringContainsString('data-duration="15000"', $response['body']);
    }

    public function testToolPageIncludesTemplateCss(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');
        $this->assertMatchesRegularExpression('/templates\/tool\.css/', $response['body']);
    }

    public function testToolPageHasGlobalHashVariable(): void
    {
        $response = $this->request('GET', '/lab/realtime/timer');
        $this->assertMatchesRegularExpression('/window\.ostHash\s*=\s*\'[a-f0-9]{8}\'/', $response['body']);
    }

    // ========== Common Tests ==========

    public function testAllTemplatesHaveDebugBadge(): void
    {
        $urls = [
            '/lab/ajax-chain/3-steps',
            '/lab/js-errors/syntax-before',
            '/lab/realtime/timer',
        ];

        foreach ($urls as $url) {
            $response = $this->request('GET', $url);
            $this->assertStringContainsString('debug-badge', $response['body'], "Missing debug badge for {$url}");
        }
    }

    public function testAllTemplatesHaveNavigation(): void
    {
        $urls = [
            '/lab/ajax-chain/3-steps',
            '/lab/js-errors/syntax-before',
            '/lab/realtime/timer',
        ];

        foreach ($urls as $url) {
            $response = $this->request('GET', $url);
            $this->assertStringContainsString('Back to Home', $response['body'], "Missing home link for {$url}");
        }
    }
}
