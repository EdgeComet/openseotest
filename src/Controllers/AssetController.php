<?php

declare(strict_types=1);

namespace Ost\Controllers;

use Ost\Response;

/**
 * Asset controller for development server.
 *
 * In production, nginx serves assets directly.
 * This controller enables asset serving with PHP's built-in server.
 */
class AssetController
{
    /**
     * Allowed asset directories for security.
     */
    private const ALLOWED_PREFIXES = ['css/', 'js/'];

    /**
     * MIME types by extension.
     */
    private const MIME_TYPES = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
    ];

    /**
     * Serve an asset file.
     *
     * @param string $hash Debug hash from URL (logged but not validated)
     * @param string $path Asset path (e.g., 'css/site.css')
     * @return string|null File content or null for 404/403
     */
    public function serve(string $hash, string $path): ?string
    {
        // Log the hash for debugging/tracking
        error_log("Asset request: hash={$hash}, path={$path}");

        $response = Response::current();

        // Security: Block path traversal
        if (str_contains($path, '..')) {
            $response->setStatusCode(403);
            return '403 Forbidden';
        }

        // Security: Only allow specific directories
        if (!$this->isAllowedPath($path)) {
            $response->setStatusCode(403);
            return '403 Forbidden';
        }

        // Build full path to asset
        $assetPath = $this->getAssetPath($path);

        // Check if file exists
        if (!file_exists($assetPath) || !is_file($assetPath)) {
            $response->setStatusCode(404);
            return '404 Not Found';
        }

        // Set content type
        $contentType = $this->getContentType($path);
        $response->setHeader('Content-Type', $contentType);

        // Set X-Debug-Hash header for correlation
        $response->setHeader('X-Debug-Hash', $hash);

        // Return file contents
        return file_get_contents($assetPath);
    }

    /**
     * Check if path is in allowed directories.
     */
    private function isAllowedPath(string $path): bool
    {
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get full filesystem path to asset.
     */
    private function getAssetPath(string $path): string
    {
        $basePath = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__, 2);
        return $basePath . '/assets/' . $path;
    }

    /**
     * Get content type for file based on extension.
     */
    private function getContentType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return self::MIME_TYPES[$ext] ?? 'application/octet-stream';
    }
}
