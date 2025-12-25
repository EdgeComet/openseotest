<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Asset;
use Ost\HashGenerator;
use Ost\Response;
use Ost\TestRegistry;
use Ost\View;

/**
 * Error page controller.
 */
class ErrorController
{
    /**
     * Render a styled 404 error page.
     *
     * @param string|null $requestedPath The path that was not found
     */
    public function notFound(?string $requestedPath = null): string
    {
        $debugHash = HashGenerator::generate();
        Response::current()->setStatusCode(404);
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        $asset = new Asset($debugHash);

        // Get test categories for suggestions
        $categories = TestRegistry::getCategories();

        $content = View::render('errors/404', [
            'debugHash' => $debugHash,
            'requestedPath' => $requestedPath,
            'categories' => $categories,
        ]);

        return View::render('layout', [
            'title' => '404 - Page Not Found',
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => null,
            'templateJs' => null,
        ]);
    }

    /**
     * Render a styled 50x error page.
     *
     * @param int $code The specific error code (500, 502, 503, 504)
     * @param string|null $message Optional error message
     */
    public function serverError(int $code = 500, ?string $message = null): string
    {
        $debugHash = HashGenerator::generate();
        Response::current()->setStatusCode($code);
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        $asset = new Asset($debugHash);

        $content = View::render('errors/50x', [
            'debugHash' => $debugHash,
            'errorCode' => $code,
            'errorMessage' => $message,
        ]);

        return View::render('layout', [
            'title' => "{$code} - Server Error",
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
            'templateCss' => null,
            'templateJs' => null,
        ]);
    }
}
