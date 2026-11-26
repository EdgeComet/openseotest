<?php

declare(strict_types=1);

namespace Ost\Tests\Integration;

use Ost\Response;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the DIY SEO product page (Edge SEO Course, lessons 1.4 and 1.5).
 *
 * The lessons depend on the page's starting state: a generic title, no canonical,
 * no structured data, no robots meta tag, and the product values in the server HTML.
 */
class DiyProductTemplateTest extends TestCase
{
    private const URI = '/lab/diy-seo/product';

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

    public function testPageRenders(): void
    {
        $response = $this->request('GET', self::URI);

        $this->assertSame(200, $response['code']);
        $this->assertStringContainsString('class="diy-product"', $response['body']);
    }

    public function testTitleIsGeneric(): void
    {
        $response = $this->request('GET', self::URI);

        $this->assertStringContainsString('<title>Product page - openseotest.org</title>', $response['body']);
    }

    public function testServerHtmlHasNoCanonicalStructuredDataOrRobotsTag(): void
    {
        $body = $this->request('GET', self::URI)['body'];

        $this->assertStringNotContainsString('rel="canonical"', $body);
        $this->assertStringNotContainsString('application/ld+json', $body);
        $this->assertStringNotContainsString('itemscope', $body);
        $this->assertStringNotContainsString('itemprop', $body);
        $this->assertStringNotContainsString('name="robots"', $body);
        $this->assertStringNotContainsString('name="googlebot"', $body);
    }

    public function testProductValuesAreInServerHtml(): void
    {
        $body = $this->request('GET', self::URI)['body'];

        $this->assertStringContainsString('<h1 class="product-title">Arden AX7 Wireless Headphones</h1>', $body);
        $this->assertStringContainsString('<span class="product-brand">Arden Sound</span>', $body);
        $this->assertStringContainsString('data-price="249.00"', $body);
        $this->assertStringContainsString('data-currency="USD"', $body);
        $this->assertStringContainsString('$249.00', $body);
        $this->assertStringContainsString('In stock', $body);
        $this->assertStringContainsString('<span class="product-sku">ARD-AX7-BLK</span>', $body);
        $this->assertMatchesRegularExpression('#<img src="https?://[^"]+/images/arden-ax7-headphones\.jpg"#', $body);
    }

    public function testProductImageFileExists(): void
    {
        $this->assertFileExists(self::$appRoot . '/public/images/arden-ax7-headphones.jpg');
    }

    public function testTagManagerContainerLoadsInHead(): void
    {
        $body = $this->request('GET', self::URI)['body'];

        $this->assertMatchesRegularExpression(
            '#<head>.*https://www\.googletagmanager\.com/gtm\.js\?id=GTM-[A-Z0-9]+.*</head>#s',
            $body
        );
        $this->assertStringContainsString("gtag('js', new Date());", $body);
    }

    public function testNoTemplateScriptRequested(): void
    {
        $body = $this->request('GET', self::URI)['body'];

        $this->assertStringNotContainsString('/js/tests/diy-seo.js', $body);
    }

    public function testTagManagerStaysOffOtherPages(): void
    {
        foreach (['/', '/lab/semantic-html/product-a', '/lab/ajax/delay-500', '/lab/js-injection/domcontentinit'] as $uri) {
            $body = $this->request('GET', $uri)['body'];
            $this->assertStringNotContainsString('googletagmanager.com', $body, "GTM must not load on {$uri}");
        }
    }

    public function testOtherPagesKeepTheirTemplateScript(): void
    {
        $body = $this->request('GET', '/lab/ajax/delay-500')['body'];

        $this->assertStringContainsString('/js/tests/ajax.js', $body);
    }
}
