<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Config;
use Ost\HashGenerator;
use Ost\Response;
use Ost\View;

/**
 * Standalone prompt pages used as QR code targets at conferences.
 *
 * Each page renders one piece of text and a copy button, nothing else.
 * The pages carry a noindex robots meta tag and stay out of the sitemap.
 */
class PromptController
{
    /**
     * Display a single prompt page.
     */
    public function show(string $slug): string
    {
        $prompt = $this->getPrompt($slug);

        if ($prompt === null) {
            return (new ErrorController())->notFound('/p/' . $slug);
        }

        // Generate unique hash for this page view (nginx log correlation)
        $debugHash = HashGenerator::generate();
        Response::current()->setHeader('X-Debug-Hash', $debugHash);

        return View::render('prompt', [
            'title' => $prompt['title'],
            'text' => $prompt['text'],
        ]);
    }

    /**
     * Look up a prompt definition by slug.
     *
     * @return array{title: string, text: string}|null
     */
    private function getPrompt(string $slug): ?array
    {
        $prompt = Config::get('prompts', $slug);

        if (!is_array($prompt) || !isset($prompt['text'])) {
            return null;
        }

        return [
            'title' => (string)($prompt['title'] ?? $slug),
            'text' => (string)$prompt['text'],
        ];
    }
}
