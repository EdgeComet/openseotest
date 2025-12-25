<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Controllers\ErrorController;
use Ost\Response;
use Ost\TestRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ErrorController.
 */
class ErrorControllerTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset response singleton before each test
        Response::reset();
        // Clear test registry cache
        TestRegistry::clearCache();
    }

    public function testNotFoundReturns404StatusCode(): void
    {
        $controller = new ErrorController();
        $controller->notFound('/some/path');

        $this->assertSame(404, Response::current()->getStatusCode());
    }

    public function testNotFoundSetsDebugHashHeader(): void
    {
        $controller = new ErrorController();
        $controller->notFound('/some/path');

        $debugHash = Response::current()->getHeader('X-Debug-Hash');
        $this->assertNotNull($debugHash);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}$/', $debugHash);
    }

    public function testNotFoundContainsRequestedPath(): void
    {
        $controller = new ErrorController();
        $output = $controller->notFound('/some/nonexistent/path');

        $this->assertStringContainsString('/some/nonexistent/path', $output);
    }

    public function testNotFoundContainsHomeLink(): void
    {
        $controller = new ErrorController();
        $output = $controller->notFound();

        $this->assertStringContainsString('href="/"', $output);
        $this->assertStringContainsString('Homepage', $output);
    }

    public function testNotFoundContainsTestCategories(): void
    {
        $controller = new ErrorController();
        $output = $controller->notFound();

        // Should contain links to test categories
        $this->assertStringContainsString('JavaScript Injection', $output);
        $this->assertStringContainsString('AJAX Delays', $output);
    }

    public function testNotFoundContainsDebugHash(): void
    {
        $controller = new ErrorController();
        $output = $controller->notFound();

        // Should contain debug hash display
        $this->assertStringContainsString('Debug Hash:', $output);
    }

    public function testNotFoundReturnsValidHtml(): void
    {
        $controller = new ErrorController();
        $output = $controller->notFound();

        $this->assertStringContainsString('<!DOCTYPE html>', $output);
        $this->assertStringContainsString('<html', $output);
        $this->assertStringContainsString('</html>', $output);
    }

    public function testServerErrorReturns500StatusCode(): void
    {
        $controller = new ErrorController();
        $controller->serverError(500);

        $this->assertSame(500, Response::current()->getStatusCode());
    }

    public function testServerErrorReturns503StatusCode(): void
    {
        $controller = new ErrorController();
        $controller->serverError(503);

        $this->assertSame(503, Response::current()->getStatusCode());
    }

    public function testServerErrorSetsDebugHashHeader(): void
    {
        $controller = new ErrorController();
        $controller->serverError();

        $debugHash = Response::current()->getHeader('X-Debug-Hash');
        $this->assertNotNull($debugHash);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}$/', $debugHash);
    }

    public function testServerErrorContainsErrorCode(): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError(502);

        $this->assertStringContainsString('502', $output);
        $this->assertStringContainsString('Bad Gateway', $output);
    }

    public function testServerErrorContainsCustomMessage(): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError(500, 'Custom error details');

        $this->assertStringContainsString('Custom error details', $output);
    }

    public function testServerErrorContainsHomeLink(): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError();

        $this->assertStringContainsString('href="/"', $output);
    }

    public function testServerErrorContainsRefreshOption(): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError();

        $this->assertStringContainsString('Refresh', $output);
    }

    public function testServerErrorReturnsValidHtml(): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError();

        $this->assertStringContainsString('<!DOCTYPE html>', $output);
        $this->assertStringContainsString('<html', $output);
        $this->assertStringContainsString('</html>', $output);
    }

    /**
     * @dataProvider serverErrorCodeProvider
     */
    public function testServerErrorHandlesAllCodes(int $code, string $expectedTitle): void
    {
        $controller = new ErrorController();
        $output = $controller->serverError($code);

        $this->assertSame($code, Response::current()->getStatusCode());
        $this->assertStringContainsString($expectedTitle, $output);
    }

    /**
     * @return array<string, array{int, string}>
     */
    public static function serverErrorCodeProvider(): array
    {
        return [
            '500 Internal Server Error' => [500, 'Internal Server Error'],
            '502 Bad Gateway' => [502, 'Bad Gateway'],
            '503 Service Unavailable' => [503, 'Service Unavailable'],
            '504 Gateway Timeout' => [504, 'Gateway Timeout'],
        ];
    }
}
