<?php

declare(strict_types=1);

namespace Ost\Tests;

use InvalidArgumentException;
use Ost\Config;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Config class.
 */
class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear the config cache before each test
        Config::clearCache();
    }

    public function testLoadReturnsArrayFromConfigFile(): void
    {
        $config = Config::load('app');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('name', $config);
        $this->assertSame('openseotest.org', $config['name']);
    }

    public function testGetReturnsSpecificKey(): void
    {
        $name = Config::get('app', 'name');

        $this->assertSame('openseotest.org', $name);
    }

    public function testGetReturnsDefaultWhenKeyMissing(): void
    {
        $value = Config::get('app', 'nonexistent_key', 'default_value');

        $this->assertSame('default_value', $value);
    }

    public function testGetReturnsNullWhenKeyMissingAndNoDefault(): void
    {
        $value = Config::get('app', 'nonexistent_key');

        $this->assertNull($value);
    }

    public function testLoadThrowsExceptionForMissingFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration file not found: nonexistent');

        Config::load('nonexistent');
    }

    public function testConfigValuesAreCached(): void
    {
        $config1 = Config::load('app');
        $config2 = Config::load('app');

        // Both calls should return the same array instance
        $this->assertSame($config1, $config2);
    }

    public function testClearCacheRemovesCachedValues(): void
    {
        $config1 = Config::load('app');
        Config::clearCache();
        $config2 = Config::load('app');

        // After clearing cache, arrays should be equal but not same instance
        $this->assertEquals($config1, $config2);
    }
}
