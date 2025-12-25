<?php

declare(strict_types=1);

namespace Ost\Tests;

use Ost\Router;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Router class.
 */
class RouterTest extends TestCase
{
    public function testExactPathMatching(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/', 'handler' => 'home'],
            ['method' => 'GET', 'pattern' => '/about', 'handler' => 'about'],
        ]);

        $result = $router->match('GET', '/');
        $this->assertNotNull($result);
        $this->assertSame('home', $result['handler']);
        $this->assertSame([], $result['params']);

        $result = $router->match('GET', '/about');
        $this->assertNotNull($result);
        $this->assertSame('about', $result['handler']);
        $this->assertSame([], $result['params']);
    }

    public function testSingleParameterExtraction(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/user/{id}', 'handler' => 'user'],
        ]);

        $result = $router->match('GET', '/user/123');
        $this->assertNotNull($result);
        $this->assertSame('user', $result['handler']);
        $this->assertSame(['id' => '123'], $result['params']);
    }

    public function testMultipleParameterExtraction(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/lab/{category}/{test}', 'handler' => 'lab'],
        ]);

        $result = $router->match('GET', '/lab/ajax/delay-500');
        $this->assertNotNull($result);
        $this->assertSame('lab', $result['handler']);
        $this->assertSame(['category' => 'ajax', 'test' => 'delay-500'], $result['params']);
    }

    public function testNonMatchingPathsReturnNull(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/about', 'handler' => 'about'],
        ]);

        $result = $router->match('GET', '/contact');
        $this->assertNull($result);

        $result = $router->match('GET', '/about/extra');
        $this->assertNull($result);
    }

    public function testQueryStringsAreIgnored(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/search', 'handler' => 'search'],
        ]);

        $result = $router->match('GET', '/search?q=test&page=1');
        $this->assertNotNull($result);
        $this->assertSame('search', $result['handler']);
    }

    public function testMethodMatching(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/api/data', 'handler' => 'getData'],
            ['method' => 'POST', 'pattern' => '/api/data', 'handler' => 'postData'],
        ]);

        $result = $router->match('GET', '/api/data');
        $this->assertNotNull($result);
        $this->assertSame('getData', $result['handler']);

        $result = $router->match('POST', '/api/data');
        $this->assertNotNull($result);
        $this->assertSame('postData', $result['handler']);

        $result = $router->match('DELETE', '/api/data');
        $this->assertNull($result);
    }

    public function testLeadingTrailingSlashNormalization(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/about', 'handler' => 'about'],
        ]);

        // Pattern defined as '/about', should match various forms
        $result = $router->match('GET', '/about');
        $this->assertNotNull($result);

        $result = $router->match('GET', '/about/');
        $this->assertNotNull($result);

        $result = $router->match('GET', 'about');
        $this->assertNotNull($result);
    }

    public function testApiBeaconRoute(): void
    {
        $router = new Router([
            ['method' => 'POST', 'pattern' => '/api/beacon/{hash}/{event}', 'handler' => 'beacon'],
        ]);

        $result = $router->match('POST', '/api/beacon/abc12345/js-executed');
        $this->assertNotNull($result);
        $this->assertSame('beacon', $result['handler']);
        $this->assertSame(['hash' => 'abc12345', 'event' => 'js-executed'], $result['params']);
    }

    public function testWildcardPathCapture(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/dist/{hash}/{path:*}', 'handler' => 'asset'],
        ]);

        $result = $router->match('GET', '/dist/abc12345/css/site.css');
        $this->assertNotNull($result);
        $this->assertSame('asset', $result['handler']);
        $this->assertSame(['hash' => 'abc12345', 'path' => 'css/site.css'], $result['params']);

        $result = $router->match('GET', '/dist/xyz99999/js/lib/util.js');
        $this->assertNotNull($result);
        $this->assertSame(['hash' => 'xyz99999', 'path' => 'js/lib/util.js'], $result['params']);
    }

    public function testSpecRoutes(): void
    {
        $router = new Router([
            ['method' => 'GET', 'pattern' => '/', 'handler' => 'home'],
            ['method' => 'GET', 'pattern' => '/lab/{category}/{test}', 'handler' => 'lab'],
            ['method' => 'POST', 'pattern' => '/api/beacon/{hash}/{event}', 'handler' => 'beacon'],
            ['method' => 'GET', 'pattern' => '/dist/{hash}/{path:*}', 'handler' => 'asset'],
        ]);

        // Home
        $result = $router->match('GET', '/');
        $this->assertNotNull($result);
        $this->assertSame('home', $result['handler']);

        // Lab test page
        $result = $router->match('GET', '/lab/ajax/delay-500');
        $this->assertNotNull($result);
        $this->assertSame(['category' => 'ajax', 'test' => 'delay-500'], $result['params']);

        // Beacon API
        $result = $router->match('POST', '/api/beacon/a1b2c3d4/content-injected');
        $this->assertNotNull($result);
        $this->assertSame(['hash' => 'a1b2c3d4', 'event' => 'content-injected'], $result['params']);

        // Asset serving
        $result = $router->match('GET', '/dist/deadbeef/css/site.css');
        $this->assertNotNull($result);
        $this->assertSame(['hash' => 'deadbeef', 'path' => 'css/site.css'], $result['params']);
    }
}
