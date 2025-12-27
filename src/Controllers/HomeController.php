<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Asset;
use Ost\HashGenerator;
use Ost\Response;
use Ost\TestRegistry;
use Ost\View;

/**
 * Home page controller.
 */
class HomeController
{
    /**
     * Display the home page.
     */
    public function index(): string
    {
        // Generate unique hash for this page view
        $debugHash = HashGenerator::generate();

        // Set X-Debug-Hash header for nginx log correlation
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        // Create asset helper with hash
        $asset = new Asset($debugHash);

        // Get all test categories
        $categories = TestRegistry::getCategories();

        // Render home content
        $content = View::render('home', [
            'debugHash' => $debugHash,
            'categories' => $categories,
        ]);

        // Render layout with home content
        return View::render('layout', [
            'title' => 'SEO & AI Bot Testing Platform - openseotest.org',
            'content' => $content,
            'debugHash' => $debugHash,
            'asset' => $asset,
        ]);
    }
}
