<?php

declare(strict_types=1);

namespace Ost;

/**
 * Registry for test definitions.
 */
class TestRegistry
{
    /**
     * Cached test definitions.
     *
     * @var array<string, array>|null
     */
    private static ?array $tests = null;

    /**
     * Get all test categories.
     *
     * @return array<string, array>
     */
    public static function getCategories(): array
    {
        return self::loadTests();
    }

    /**
     * Get a single category by slug.
     *
     * @param string $slug Category slug
     * @return array|null Category data or null if not found
     */
    public static function getCategory(string $slug): ?array
    {
        $tests = self::loadTests();
        return $tests[$slug] ?? null;
    }

    /**
     * Get a single test by category and test slug.
     *
     * @param string $category Category slug
     * @param string $test Test slug
     * @return array|null Test data or null if not found
     */
    public static function getTest(string $category, string $test): ?array
    {
        $categoryData = self::getCategory($category);

        if ($categoryData === null) {
            return null;
        }

        if (!isset($categoryData['tests'][$test])) {
            return null;
        }

        // Return test data with category info included
        // Test-specific values (like template) take precedence over category defaults
        return array_merge([
            'category' => $category,
            'categoryName' => $categoryData['name'],
            'template' => $categoryData['template'],
            'slug' => $test,
        ], $categoryData['tests'][$test]);
    }

    /**
     * Check if a test exists.
     *
     * @param string $category Category slug
     * @param string $test Test slug
     * @return bool
     */
    public static function isValidTest(string $category, string $test): bool
    {
        return self::getTest($category, $test) !== null;
    }

    /**
     * Get the first test in a category.
     *
     * @param string $category Category slug
     * @return string|null First test slug or null if category not found
     */
    public static function getFirstTest(string $category): ?string
    {
        $categoryData = self::getCategory($category);

        if ($categoryData === null || empty($categoryData['tests'])) {
            return null;
        }

        return array_key_first($categoryData['tests']);
    }

    /**
     * Load test definitions from config file.
     *
     * @return array<string, array>
     */
    private static function loadTests(): array
    {
        if (self::$tests === null) {
            self::$tests = Config::load('tests');
        }

        return self::$tests;
    }

    /**
     * Clear the cache (useful for testing).
     */
    public static function clearCache(): void
    {
        self::$tests = null;
    }
}
