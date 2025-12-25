<?php

declare(strict_types=1);

/**
 * Application bootstrap file.
 *
 * Initializes the application environment.
 */

// Require Composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define APP_ROOT constant pointing to project root
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Load .env file if it exists
$envFile = APP_ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Parse KEY=value
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                $value = $matches[2];
            }
            // Only set if not already set (allow system env to override)
            if (getenv($key) === false) {
                putenv("{$key}={$value}");
            }
        }
    }
}

// Determine environment and debug mode
$appEnv = getenv('APP_ENV') ?: 'development';
$appDebug = getenv('APP_DEBUG') !== 'false';

// Configure error reporting based on environment
if ($appDebug && $appEnv !== 'production') {
    // Development: show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    // Production: hide errors from users, log them instead
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', APP_ROOT . '/logs/php_errors.log');
}

// Set timezone from config or default to UTC
$timezone = getenv('APP_TIMEZONE') ?: 'UTC';
date_default_timezone_set($timezone);
