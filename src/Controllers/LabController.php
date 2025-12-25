<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Asset;
use Ost\HashGenerator;
use Ost\Response;
use Ost\TestRegistry;
use Ost\View;

/**
 * Lab test pages controller.
 */
class LabController
{
    /**
     * Display a lab test page.
     */
    public function show(string $category, string $test): string
    {
        // Validate test exists
        if (!TestRegistry::isValidTest($category, $test)) {
            Response::current()->setStatusCode(404);
            return $this->render404($category, $test);
        }

        // Get category and test data
        $categoryData = TestRegistry::getCategory($category);
        $testData = TestRegistry::getTest($category, $test);

        // Handle HTTP status code tests
        if ($category === 'http-status') {
            return $this->handleHttpStatusTest($test, $testData);
        }

        // Generate unique hash for this page view
        $debugHash = HashGenerator::generate();

        // Set X-Debug-Hash header for nginx log correlation
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        // Create asset helper with hash
        $asset = new Asset($debugHash);

        // Determine which template to use
        $template = $categoryData['template'] ?? 'article';

        // Get next test in sequence for navigation
        $nextTest = $this->getNextTest($category, $test);

        // Prepare template data
        $templateData = [
            'debugHash' => $debugHash,
            'category' => $category,
            'categoryName' => $categoryData['name'] ?? $category,
            'test' => $test,
            'testTitle' => $testData['title'] ?? $test,
            'testDescription' => $categoryData['description'] ?? '',
            'delay' => $testData['delay'] ?? 0,
            'nextTest' => $nextTest,
        ];

        // Add any additional test-specific data
        foreach ($testData as $key => $value) {
            if (!isset($templateData[$key])) {
                $templateData[$key] = $value;
            }
        }

        // Render the page template
        $content = View::render('pages/' . $template, $templateData);

        // Determine JS file path - js-errors uses per-test files
        $templateJs = $category;
        if ($category === 'js-errors') {
            $templateJs = 'js-errors/' . $test;
        }

        // Render layout with template content
        return View::render('layout', [
            'title' => $testData['title'] . ' - openseotest.org',
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => $template,
            'templateJs' => $templateJs,
        ]);
    }

    /**
     * Get the next test in sequence for navigation.
     */
    private function getNextTest(string $category, string $currentTest): ?string
    {
        $categoryData = TestRegistry::getCategory($category);
        if (!$categoryData || empty($categoryData['tests'])) {
            return null;
        }

        $tests = array_keys($categoryData['tests']);
        $currentIndex = array_search($currentTest, $tests, true);

        if ($currentIndex === false || $currentIndex >= count($tests) - 1) {
            return null;
        }

        return $tests[$currentIndex + 1];
    }

    /**
     * Allowed delay values for AJAX tests (in milliseconds).
     */
    private const ALLOWED_DELAYS = [500, 1000, 2000, 3000, 4000, 5000];

    /**
     * Product data variations for AJAX tests.
     */
    private const PRODUCT_DATA = [
        500 => [
            'price' => '$299.99',
            'availability' => 'In Stock',
            'shipping' => 'Free 2-day shipping',
        ],
        1000 => [
            'price' => '$349.99',
            'availability' => 'In Stock - Ships Today',
            'shipping' => 'Free next-day shipping',
        ],
        2000 => [
            'price' => '$249.99',
            'availability' => 'In Stock - Limited Quantity',
            'shipping' => 'Free standard shipping (3-5 days)',
        ],
        3000 => [
            'price' => '$279.99',
            'availability' => 'In Stock',
            'shipping' => 'Free 2-day shipping',
        ],
        4000 => [
            'price' => '$319.99',
            'availability' => 'Pre-order - Ships in 2 weeks',
            'shipping' => 'Free shipping on release',
        ],
        5000 => [
            'price' => '$399.99',
            'availability' => 'In Stock - Premium Edition',
            'shipping' => 'Free express shipping',
        ],
    ];

    /**
     * Handle AJAX fetch request for product data with server-side delay.
     */
    public function ajaxFetch(string $delay, string $hash): string
    {
        // Validate delay is numeric and in allowed list
        $delayMs = (int) $delay;
        if (!in_array($delayMs, self::ALLOWED_DELAYS, true)) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid delay value']);
        }

        // Sanitize hash (should be 8 hex characters)
        if (!preg_match('/^[a-f0-9]{8}$/', $hash)) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid hash format']);
        }

        // Sleep for specified milliseconds
        usleep($delayMs * 1000);

        // Set response headers
        Response::current()->setHeader('Content-Type', 'application/json');
        Response::current()->setHeader('X-Debug-Hash', $hash);

        // Get product data for this delay value
        $productData = self::PRODUCT_DATA[$delayMs];

        // Return product data
        return json_encode([
            'price' => $productData['price'],
            'availability' => $productData['availability'],
            'shipping' => $productData['shipping'],
            'hash' => $hash,
        ]);
    }

    /**
     * Product catalog for AJAX chain tests.
     */
    private const CHAIN_PRODUCTS = [
        1 => [
            'name' => 'PixelPulse Earbuds',
            'price' => '$79.99',
            'image' => 'https://picsum.photos/200/200?random=1',
        ],
        2 => [
            'name' => 'PixelPulse Soundbar',
            'price' => '$199.99',
            'image' => 'https://picsum.photos/200/200?random=2',
        ],
        3 => [
            'name' => 'PixelPulse Speaker',
            'price' => '$149.99',
            'image' => 'https://picsum.photos/200/200?random=3',
        ],
        4 => [
            'name' => 'PixelPulse Headphones Pro',
            'price' => '$299.99',
            'image' => 'https://picsum.photos/200/200?random=4',
        ],
        5 => [
            'name' => 'PixelPulse Mic',
            'price' => '$129.99',
            'image' => 'https://picsum.photos/200/200?random=5',
        ],
        6 => [
            'name' => 'PixelPulse Subwoofer',
            'price' => '$249.99',
            'image' => 'https://picsum.photos/200/200?random=6',
        ],
    ];

    /**
     * Handle AJAX chain fetch for sequential product loading.
     */
    public function ajaxChainFetch(string $steps, string $hash, string $step): string
    {
        // Validate steps is 3 or 5
        $stepsInt = (int) $steps;
        if (!in_array($stepsInt, [3, 5], true)) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid steps value']);
        }

        // Validate step is 1 to steps
        $stepInt = (int) $step;
        if ($stepInt < 1 || $stepInt > $stepsInt) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid step value']);
        }

        // Sanitize hash (should be 8 hex characters)
        if (!preg_match('/^[a-f0-9]{8}$/', $hash)) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid hash format']);
        }

        // Sleep for 500ms (standard delay per step)
        usleep(500 * 1000);

        // Set response headers
        Response::current()->setHeader('Content-Type', 'application/json');
        Response::current()->setHeader('X-Debug-Hash', $hash);

        // Get product for this step
        $product = self::CHAIN_PRODUCTS[$stepInt];

        // Return product data
        return json_encode([
            'step' => $stepInt,
            'product' => $product,
            'hasMore' => $stepInt < $stepsInt,
            'hash' => $hash,
        ]);
    }

    /**
     * Handle realtime status request with server-side delay.
     */
    public function realtimeStatus(string $hash): string
    {
        // Sanitize hash (should be 8 hex characters)
        if (!preg_match('/^[a-f0-9]{8}$/', $hash)) {
            Response::current()->setStatusCode(400);
            Response::current()->setHeader('Content-Type', 'application/json');
            return json_encode(['error' => 'Invalid hash format']);
        }

        // Sleep for 2 seconds to simulate latency
        usleep(2000 * 1000);

        // Set response headers
        Response::current()->setHeader('Content-Type', 'application/json');
        Response::current()->setHeader('X-Debug-Hash', $hash);

        // Return status data
        return json_encode([
            'status' => 'Online',
            'uptime' => '99.9%',
            'latency' => '12ms',
            'avgResponse' => '45ms',
            'hash' => $hash,
        ]);
    }

    /**
     * Render a 404 page for invalid tests.
     */
    private function render404(string $category, string $test): string
    {
        $debugHash = HashGenerator::generate();
        Response::current()->setHeader('X-Debug-Hash', $debugHash);
        $asset = new Asset($debugHash);

        $content = '<div class="error-page">';
        $content .= '<h1>Test Not Found</h1>';
        $content .= '<p>The test <strong>' . htmlspecialchars("{$category}/{$test}") . '</strong> does not exist.</p>';
        $content .= '<p><a href="/">Return to homepage</a></p>';
        $content .= '</div>';

        return View::render('layout', [
            'title' => '404 - Test Not Found',
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => null,
            'templateJs' => null,
        ]);
    }

    /**
     * Handle HTTP status code tests (redirects and errors).
     */
    private function handleHttpStatusTest(string $test, array $testData): string
    {
        $code = $testData['code'] ?? 200;
        $debugHash = HashGenerator::generate();
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        // Handle redirects (301, 302)
        if ($code === 301 || $code === 302) {
            Response::current()->setStatusCode($code);
            Response::current()->setHeader('Location', "/lab/http-status/{$test}/target");
            return ''; // Empty body for redirects
        }

        // Handle error codes (404, 500, 503)
        Response::current()->setStatusCode($code);
        $asset = new Asset($debugHash);

        $content = $this->renderHttpStatusContent($code, $debugHash);

        return View::render('layout', [
            'title' => "{$code} - {$testData['title']} - openseotest.org",
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => 'article',
            'templateJs' => null,
        ]);
    }

    /**
     * Render content for HTTP status error pages.
     */
    private function renderHttpStatusContent(int $code, string $debugHash): string
    {
        $messages = [
            404 => [
                'title' => 'Page Not Found',
                'subtitle' => 'But This Is Expected!',
                'description' => 'This page intentionally returns a 404 status code to test how search engine bots handle missing pages.',
                'botBehavior' => 'Bots should recognize this as a "not found" page and remove it from their index, or not index it in the first place.',
            ],
            500 => [
                'title' => 'Internal Server Error',
                'subtitle' => 'Intentional Test',
                'description' => 'This page intentionally returns a 500 status code to test how bots handle server errors.',
                'botBehavior' => 'Bots should retry later or skip this page. They should not permanently remove it from the index on a single 500 error.',
            ],
            503 => [
                'title' => 'Service Unavailable',
                'subtitle' => 'Maintenance Test',
                'description' => 'This page intentionally returns a 503 status code to simulate maintenance or temporary unavailability.',
                'botBehavior' => 'Bots should respect the 503 status and retry later. This is different from a 404 - the page exists but is temporarily unavailable.',
            ],
        ];

        $msg = $messages[$code] ?? [
            'title' => "HTTP {$code}",
            'subtitle' => 'Status Code Test',
            'description' => "This page returns HTTP status code {$code}.",
            'botBehavior' => 'Observe how bots handle this status code.',
        ];

        $content = '<div class="http-status-page">';
        $content .= '<div class="status-code-display">' . $code . '</div>';
        $content .= '<h1>' . htmlspecialchars($msg['title']) . '</h1>';
        $content .= '<p class="subtitle">' . htmlspecialchars($msg['subtitle']) . '</p>';
        $content .= '<div class="description">';
        $content .= '<p>' . htmlspecialchars($msg['description']) . '</p>';
        $content .= '</div>';
        $content .= '<div class="test-info">';
        $content .= '<h4>Expected Bot Behavior</h4>';
        $content .= '<p>' . htmlspecialchars($msg['botBehavior']) . '</p>';
        $content .= '<p><strong>Debug Hash:</strong> <code>' . htmlspecialchars($debugHash) . '</code></p>';
        $content .= '<span class="seo-marker">OSTS-' . htmlspecialchars($debugHash) . '-ERROR-' . $code . '</span>';
        $content .= '</div>';
        $content .= '<nav class="page-nav">';
        $content .= '<a href="/">&larr; Back to Home</a>';
        $content .= '</nav>';
        $content .= '</div>';

        return $content;
    }

    /**
     * Handle redirect target pages.
     */
    public function redirectTarget(string $code): string
    {
        $debugHash = HashGenerator::generate();
        Response::current()->setHeader('X-Debug-Hash', $debugHash);
        $asset = new Asset($debugHash);

        $codeInt = (int) $code;
        $redirectType = $codeInt === 301 ? 'Permanent' : 'Temporary';

        $content = '<div class="redirect-target-page">';
        $content .= '<div class="status-code-display success">' . $codeInt . '</div>';
        $content .= '<h1>Redirect Successful</h1>';
        $content .= '<p class="subtitle">You were redirected here via HTTP ' . $codeInt . ' (' . $redirectType . ' Redirect)</p>';
        $content .= '<div class="description">';
        $content .= '<p>This page is the target of a <strong>' . $codeInt . ' ' . $redirectType . ' Redirect</strong> test.</p>';
        if ($codeInt === 301) {
            $content .= '<p>A 301 redirect tells bots that the original URL has <em>permanently</em> moved to this location. ';
            $content .= 'Bots should update their index to point to this new URL.</p>';
        } else {
            $content .= '<p>A 302 redirect tells bots that the original URL has <em>temporarily</em> moved. ';
            $content .= 'Bots should keep the original URL in their index and check again later.</p>';
        }
        $content .= '</div>';
        $content .= '<div class="test-info">';
        $content .= '<h4>Test Information</h4>';
        $content .= '<p><strong>Redirect Type:</strong> ' . $codeInt . ' ' . $redirectType . '</p>';
        $content .= '<p><strong>Debug Hash:</strong> <code>' . htmlspecialchars($debugHash) . '</code></p>';
        $content .= '<span class="seo-marker">OSTS-' . htmlspecialchars($debugHash) . '-REDIRECT-' . $codeInt . '</span>';
        $content .= '</div>';
        $content .= '<nav class="page-nav">';
        $content .= '<a href="/">&larr; Back to Home</a>';
        $content .= '</nav>';
        $content .= '</div>';

        return View::render('layout', [
            'title' => $codeInt . ' Redirect Target - openseotest.org',
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => 'article',
            'templateJs' => null,
        ]);
    }
}
