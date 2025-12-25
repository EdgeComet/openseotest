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
if ($uri !== '/' && file_exists($publicPath) && is_file($publicPath)) {
    // Serve the static file directly
    return false;
}

// Route everything else through the front controller
require_once __DIR__ . '/index.php';
