<?php

/**
 * Development router for PHP built-in server.
 *
 * Usage: php -S localhost:8000 -t public public/router.php
 *
 * This routes all requests through index.php unless they're
 * for actual static files that exist in public/.
 */

// Get the requested URI path
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Check if the request is for an actual file in public/
$publicPath = __DIR__ . $uri;
$realPath = realpath($publicPath);

// Validate path is within public directory (prevents path traversal)
if ($realPath &&
    str_starts_with($realPath, __DIR__) &&
    $uri !== '/' &&
    is_file($realPath)) {
    // Serve the static file directly
    return false;
}

// Route everything else through the front controller
require_once __DIR__ . '/index.php';
