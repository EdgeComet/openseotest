<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Config;
use Ost\Response;
use Ost\TestRegistry;

/**
 * Sitemap controller for generating XML sitemap.
 */
class SitemapController
{
    /**
     * Generate and return the XML sitemap.
     */
    public function index(): string
    {
        // Set content type to XML
        Response::current()->setHeader('Content-Type', 'application/xml; charset=utf-8');

        $urls = $this->buildUrlList();

        return $this->generateXml($urls);
    }

    /**
     * Get the base URL from configuration.
     */
    private function getBaseUrl(): string
    {
        return rtrim(Config::get('app', 'url', 'https://openseotest.org'), '/');
    }

    /**
     * Build the list of URLs for the sitemap.
     *
     * @return array<int, array{loc: string, priority: string, changefreq: string}>
     */
    private function buildUrlList(): array
    {
        $baseUrl = $this->getBaseUrl();
        $urls = [];

        // Homepage - highest priority
        $urls[] = [
            'loc' => $baseUrl . '/',
            'priority' => '1.0',
            'changefreq' => 'weekly',
        ];

        // Get all test categories and their tests
        $categories = TestRegistry::getCategories();

        foreach ($categories as $categorySlug => $category) {
            if (!isset($category['tests']) || !is_array($category['tests'])) {
                continue;
            }

            foreach ($category['tests'] as $testSlug => $test) {
                $urls[] = [
                    'loc' => $baseUrl . '/lab/' . $this->escape($categorySlug) . '/' . $this->escape((string)$testSlug),
                    'priority' => '0.8',
                    'changefreq' => 'monthly',
                ];
            }
        }

        return $urls;
    }

    /**
     * Generate the XML sitemap from URL list.
     *
     * @param array<int, array{loc: string, priority: string, changefreq: string}> $urls
     */
    private function generateXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $this->escapeXml($url['loc']) . '</loc>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>' . "\n";

        return $xml;
    }

    /**
     * URL-encode a path segment.
     */
    private function escape(string $value): string
    {
        return rawurlencode($value);
    }

    /**
     * Escape a value for XML output.
     */
    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
