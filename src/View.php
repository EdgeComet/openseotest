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
     */
    private static function getTemplatePath(string $template): string
    {
        $basePath = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__);
        return $basePath . '/src/Templates/' . $template . '.php';
    }
}
