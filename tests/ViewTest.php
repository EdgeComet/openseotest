<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Asset;
use Ost\View;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Tests for the View class.
 */
class ViewTest extends TestCase
{
    public function testRenderReturnsString(): void
    {
        $result = View::render('home', [
            'debugHash' => 'test123',
            'categories' => [],
        ]);

        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testDataIsAvailableInTemplate(): void
    {
        $result = View::render('layout', [
            'title' => 'Test Title',
            'content' => '<p>Test content</p>',
            'debugHash' => 'abc12345',
            'asset' => new Asset('abc12345'),
        ]);

        $this->assertStringContainsString('Test Title', $result);
        $this->assertStringContainsString('Test content', $result);
        $this->assertStringContainsString('abc12345', $result);
    }

    public function testMissingTemplateThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Template not found: nonexistent');

        View::render('nonexistent');
    }

    public function testLayoutContainsHtml5Structure(): void
    {
        $result = View::render('layout', [
            'title' => 'Test',
            'content' => 'Content',
            'debugHash' => 'hash123',
            'asset' => new Asset('hash123'),
        ]);

        $this->assertStringContainsString('<!DOCTYPE html>', $result);
        $this->assertStringContainsString('<html lang="en">', $result);
        $this->assertStringContainsString('</html>', $result);
    }

    public function testDebugBadgeIsRendered(): void
    {
        $result = View::render('layout', [
            'title' => 'Test',
            'content' => 'Content',
            'debugHash' => 'myhash99',
            'asset' => new Asset('myhash99'),
        ]);

        $this->assertStringContainsString('debug-badge', $result);
        $this->assertStringContainsString('Debug: myhash99', $result);
    }

    public function testAssetUrlsIncludeHash(): void
    {
        $result = View::render('layout', [
            'title' => 'Test',
            'content' => 'Content',
            'debugHash' => 'abc12345',
            'asset' => new Asset('abc12345'),
        ]);

        $this->assertStringContainsString('/dist/abc12345/css/site.css', $result);
        $this->assertStringContainsString('/dist/abc12345/js/site.js', $result);
    }
}
