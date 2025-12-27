<?php

declare(strict_types=1);

namespace Ost;

use RuntimeException;

/**
 * Simple PHP template rendering.
 */
class View
{
    /**
     * Render a template with data.
     *
     * @param string $template Template name (without .php extension)
     * @param array<string, mixed> $data Data to extract into template scope
     * @return string Rendered content
     * @throws RuntimeException If template file not found
     */
    public static function render(string $template, array $data = []): string
    {
        $templatePath = self::getTemplatePath($template);

        if (!file_exists($templatePath)) {
            throw new RuntimeException("Template not found: {$template}");
        }

        // Extract data into local scope
        extract($data, EXTR_SKIP);

        // Capture output
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Get the full path to a template file.
     *
     * @throws RuntimeException If template path escapes the Templates directory
     */
    private static function getTemplatePath(string $template): string
    {
        $basePath = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__);
        $templatesDir = $basePath . '/src/Templates';
        $templatePath = $templatesDir . '/' . $template . '.php';

        // Resolve to real path and validate it's within Templates directory
        $realPath = realpath($templatePath);
        $realTemplatesDir = realpath($templatesDir);

        if ($realPath === false) {
            // File doesn't exist - let caller handle with "Template not found"
            return $templatePath;
        }

        if ($realTemplatesDir === false || !str_starts_with($realPath, $realTemplatesDir . '/')) {
            throw new RuntimeException("Invalid template path: {$template}");
        }

        return $realPath;
    }
}
