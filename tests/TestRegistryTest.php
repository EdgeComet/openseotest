<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Config;
use Ost\TestRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the TestRegistry class.
 */
class TestRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear caches before each test
        Config::clearCache();
        TestRegistry::clearCache();
    }

    public function testGetCategoriesReturnsAllCategories(): void
    {
        $categories = TestRegistry::getCategories();

        $this->assertIsArray($categories);
        $this->assertArrayHasKey('js-injection', $categories);
        $this->assertArrayHasKey('ajax', $categories);
        $this->assertArrayHasKey('ajax-chain', $categories);
        $this->assertArrayHasKey('js-errors', $categories);
        $this->assertArrayHasKey('realtime', $categories);
        $this->assertArrayHasKey('http-status', $categories);
        $this->assertCount(6, $categories);
    }

    public function testGetCategoryReturnsCorrectCategory(): void
    {
        $category = TestRegistry::getCategory('ajax');

        $this->assertNotNull($category);
        $this->assertSame('AJAX Delays', $category['name']);
        $this->assertSame('product', $category['template']);
        $this->assertArrayHasKey('tests', $category);
        $this->assertArrayHasKey('delay-500', $category['tests']);
    }

    public function testGetCategoryReturnsNullForInvalidCategory(): void
    {
        $category = TestRegistry::getCategory('nonexistent');

        $this->assertNull($category);
    }

    public function testGetTestReturnsCorrectTestWithConfig(): void
    {
        $test = TestRegistry::getTest('ajax', 'delay-500');

        $this->assertNotNull($test);
        $this->assertSame('500ms Delay', $test['title']);
        $this->assertSame(500, $test['delay']);
        $this->assertSame('ajax', $test['category']);
        $this->assertSame('AJAX Delays', $test['categoryName']);
        $this->assertSame('product', $test['template']);
        $this->assertSame('delay-500', $test['slug']);
    }

    public function testGetTestReturnsNullForInvalidCategory(): void
    {
        $test = TestRegistry::getTest('nonexistent', 'delay-500');

        $this->assertNull($test);
    }

    public function testGetTestReturnsNullForInvalidTest(): void
    {
        $test = TestRegistry::getTest('ajax', 'nonexistent');

        $this->assertNull($test);
    }

    public function testIsValidTestReturnsTrueForValidTest(): void
    {
        $this->assertTrue(TestRegistry::isValidTest('ajax', 'delay-500'));
        $this->assertTrue(TestRegistry::isValidTest('js-injection', 'domcontentinit'));
        $this->assertTrue(TestRegistry::isValidTest('http-status', '404'));
    }

    public function testIsValidTestReturnsFalseForInvalidTest(): void
    {
        $this->assertFalse(TestRegistry::isValidTest('nonexistent', 'delay-500'));
        $this->assertFalse(TestRegistry::isValidTest('ajax', 'nonexistent'));
        $this->assertFalse(TestRegistry::isValidTest('nonexistent', 'nonexistent'));
    }

    public function testGetFirstTestReturnsFirstTest(): void
    {
        $firstTest = TestRegistry::getFirstTest('ajax');

        $this->assertSame('delay-500', $firstTest);
    }

    public function testGetFirstTestReturnsNullForInvalidCategory(): void
    {
        $firstTest = TestRegistry::getFirstTest('nonexistent');

        $this->assertNull($firstTest);
    }

    public function testAllCategoriesHaveRequiredFields(): void
    {
        $categories = TestRegistry::getCategories();

        foreach ($categories as $slug => $category) {
            $this->assertArrayHasKey('name', $category, "Category {$slug} missing 'name'");
            $this->assertArrayHasKey('description', $category, "Category {$slug} missing 'description'");
            $this->assertArrayHasKey('template', $category, "Category {$slug} missing 'template'");
            $this->assertArrayHasKey('tests', $category, "Category {$slug} missing 'tests'");
            $this->assertNotEmpty($category['tests'], "Category {$slug} has no tests");
        }
    }
}
