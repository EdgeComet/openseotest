<?php

declare(strict_types=1);

namespace Ost;

use InvalidArgumentException;

/**
 * Configuration loader and accessor.
 */
class Config
{
    /**
     * Cache of loaded configuration files.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $cache = [];

    /**
     * Load a configuration file.
     *
     * @param string $file The config file name (without .php extension)
     * @return array<string, mixed> The configuration array
     * @throws InvalidArgumentException If the config file doesn't exist
     */
    public static function load(string $file): array
    {
        if (isset(self::$cache[$file])) {
            return self::$cache[$file];
        }

        $path = self::getConfigPath($file);

        if (!file_exists($path)) {
            throw new InvalidArgumentException("Configuration file not found: {$file}");
        }

        $config = require $path;

        self::$cache[$file] = $config;

        return $config;
    }

    /**
     * Get a specific configuration value.
     *
     * @param string $file The config file name
     * @param string $key The configuration key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed The configuration value or default
     */
    public static function get(string $file, string $key, mixed $default = null): mixed
    {
        $config = self::load($file);

        return $config[$key] ?? $default;
    }

    /**
     * Get the full path to a configuration file.
     *
     * @param string $file The config file name
     * @return string The full path
     */
    private static function getConfigPath(string $file): string
    {
        $basePath = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__);
        return $basePath . '/config/' . $file . '.php';
    }

    /**
     * Clear the configuration cache (useful for testing).
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
