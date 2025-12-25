<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\SitemapController;
use Ost\Response;
use Ost\TestRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the SitemapController.
 */
class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset response singleton before each test
        Response::reset();
        // Clear test registry cache
        TestRegistry::clearCache();
    }

    public function testSitemapReturnsValidXml(): void
    {
        $controller = new SitemapController();
        $output = $controller->index();

        // Check XML declaration
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $output);

        // Verify it's valid XML by parsing it
        $xml = simplexml_load_string($output);
        $this->assertNotFalse($xml, 'Sitemap should be valid XML');
    }

    public function testSitemapIncludesHomepage(): void
    {
        $controller = new SitemapController();
        $output = $controller->index();

        // Check that homepage is included
        $this->assertStringContainsString('<loc>https://openseotest.org/</loc>', $output);
        $this->assertStringContainsString('<priority>1.0</priority>', $output);
    }

    public function testSitemapIncludesAllTestUrls(): void
    {
        $controller = new SitemapController();
        $output = $controller->index();

        // Get all categories and their tests
        $categories = TestRegistry::getCategories();

        foreach ($categories as $categorySlug => $category) {
            if (!isset($category['tests']) || !is_array($category['tests'])) {
                continue;
            }

            foreach ($category['tests'] as $testSlug => $test) {
                $expectedUrl = 'https://openseotest.org/lab/' . rawurlencode($categorySlug) . '/' . rawurlencode((string)$testSlug);
                $this->assertStringContainsString(
                    '<loc>' . htmlspecialchars($expectedUrl, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc>',
                    $output,
                    "Sitemap should include URL for {$categorySlug}/{$testSlug}"
                );
            }
        }
    }

    public function testSitemapHasProperXmlStructure(): void
    {
        $controller = new SitemapController();
        $output = $controller->index();

        // Parse the XML
        $xml = simplexml_load_string($output);
        $this->assertNotFalse($xml);

        // Check urlset element with correct namespace
        $namespaces = $xml->getNamespaces();
        $this->assertArrayHasKey('', $namespaces);
        $this->assertSame('http://www.sitemaps.org/schemas/sitemap/0.9', $namespaces['']);

        // Check that there are url elements
        $urls = $xml->url;
        $this->assertGreaterThan(0, count($urls), 'Sitemap should contain at least one URL');

        // Each URL should have loc, changefreq, and priority
        foreach ($urls as $url) {
            $this->assertNotEmpty((string)$url->loc, 'Each URL should have a loc element');
            $this->assertNotEmpty((string)$url->changefreq, 'Each URL should have a changefreq element');
            $this->assertNotEmpty((string)$url->priority, 'Each URL should have a priority element');
        }
    }

    public function testSitemapSetsCorrectContentType(): void
    {
        $controller = new SitemapController();
        $controller->index();

        $contentType = Response::current()->getHeader('Content-Type');
        $this->assertSame('application/xml; charset=utf-8', $contentType);
    }

    public function testSitemapTestUrlsHaveCorrectPriority(): void
    {
        $controller = new SitemapController();
        $output = $controller->index();

        $xml = simplexml_load_string($output);
        $this->assertNotFalse($xml);

        $foundTestPage = false;
        foreach ($xml->url as $url) {
            $loc = (string)$url->loc;
            if (strpos($loc, '/lab/') !== false) {
                $this->assertSame('0.8', (string)$url->priority, 'Test pages should have priority 0.8');
                $foundTestPage = true;
            }
        }

        $this->assertTrue($foundTestPage, 'Should have at least one test page in sitemap');
    }
}
