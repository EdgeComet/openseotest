<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the product template.
 */
class ProductTemplateTest extends TestCase
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

    public function testAjaxDelayRendersProductPage(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('PixelPulse', $response['body']);
        $this->assertStringContainsString('Pro Wireless Headphones', $response['body']);
        $this->assertStringContainsString('product-page', $response['body']);
    }

    public function testProductPageIncludesPricePlaceholder(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertStringContainsString('id="product-price"', $response['body']);
        $this->assertStringContainsString('Loading price', $response['body']);
    }

    public function testProductPageIncludesAvailabilityPlaceholder(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertStringContainsString('id="product-availability"', $response['body']);
        $this->assertStringContainsString('Checking availability', $response['body']);
    }

    public function testProductPageIncludesDataDelayAttribute(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertStringContainsString('data-delay="500"', $response['body']);
    }

    public function testProductPageIncludesDataHashAttribute(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertMatchesRegularExpression('/data-hash="[a-f0-9]{8}"/', $response['body']);
    }

    public function testProductPageIncludesDebugBadge(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertStringContainsString('debug-badge', $response['body']);
    }

    public function testProductPageIncludesTemplateCss(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertMatchesRegularExpression('/templates\/product\.css/', $response['body']);
    }

    public function testProductPageIncludesTestInfo(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        $this->assertStringContainsString('Test Information', $response['body']);
        $this->assertStringContainsString('AJAX', $response['body']);
        $this->assertStringContainsString('500ms', $response['body']);
    }

    public function testDifferentDelayValues(): void
    {
        $delays = [500, 1000, 2000, 3000, 4000, 5000];

        foreach ($delays as $delay) {
            $response = $this->request('GET', "/lab/ajax/delay-{$delay}");

            $this->assertSame(200, $response['code'], "Failed for delay-{$delay}");
            $this->assertStringContainsString("data-delay=\"{$delay}\"", $response['body']);
            $this->assertStringContainsString("{$delay}ms", $response['body']);
        }
    }

    public function testProductPageHasNextTestLink(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-500');

        // delay-500 is the first test, so next should be delay-1000
        $this->assertStringContainsString('Next Test', $response['body']);
        $this->assertStringContainsString('/lab/ajax/delay-1000', $response['body']);
    }

    public function testLastDelayTestHasNoNextLink(): void
    {
        $response = $this->request('GET', '/lab/ajax/delay-5000');

        // delay-5000 is the last test
        $this->assertStringNotContainsString('Next Test', $response['body']);
    }
}
