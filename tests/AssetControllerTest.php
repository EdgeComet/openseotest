<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\AssetController;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the AssetController class.
 */
class AssetControllerTest extends TestCase
{
    private AssetController $controller;

    protected function setUp(): void
    {
        $this->controller = new AssetController();
        // Define APP_ROOT for tests
        if (!defined('APP_ROOT')) {
            define('APP_ROOT', dirname(__DIR__));
        }
    }

    /**
     * Test serving existing CSS file.
     */
    public function testServeCssFile(): void
    {
        $result = $this->controller->serve('testhash', 'css/site.css');

        $this->assertNotNull($result);
        $this->assertStringContainsString('openseotest.org', $result);
        $this->assertStringContainsString('background-color', $result);
    }

    /**
     * Test serving existing JS file.
     */
    public function testServeJsFile(): void
    {
        $result = $this->controller->serve('testhash', 'js/site.js');

        $this->assertNotNull($result);
        $this->assertStringContainsString('OST', $result);
    }

    /**
     * Test 404 for missing files.
     */
    public function testServe404ForMissingFile(): void
    {
        $result = $this->controller->serve('testhash', 'css/nonexistent.css');

        $this->assertSame('404 Not Found', $result);
    }

    /**
     * Test path traversal is blocked.
     */
    public function testPathTraversalBlocked(): void
    {
        $result = $this->controller->serve('testhash', '../../../etc/passwd');

        $this->assertSame('403 Forbidden', $result);
    }

    /**
     * Test path traversal with encoded dots is blocked.
     */
    public function testPathTraversalVariationsBlocked(): void
    {
        $result = $this->controller->serve('testhash', 'css/../../../etc/passwd');

        $this->assertSame('403 Forbidden', $result);
    }

    /**
     * Test invalid directory prefix is blocked.
     */
    public function testInvalidDirectoryBlocked(): void
    {
        $result = $this->controller->serve('testhash', 'images/logo.png');

        $this->assertSame('403 Forbidden', $result);
    }

    /**
     * Test root path is blocked.
     */
    public function testRootPathBlocked(): void
    {
        $result = $this->controller->serve('testhash', 'site.css');

        $this->assertSame('403 Forbidden', $result);
    }
}
