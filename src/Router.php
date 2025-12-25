<?php

declare(strict_types=1);

namespace Ost;

/**
 * Simple URL router with parameter extraction.
 */
class Router
{
    /**
     * @var array<int, array{method: string, pattern: string, handler: callable|array}>
     */
    private array $routes;

    /**
     * @param array<int, array{method: string, pattern: string, handler: callable|array}> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Match a request against registered routes.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri Request URI
     * @return array{handler: callable|array, params: array<string, string>}|null
     */
    public function match(string $method, string $uri): ?array
    {
        // Normalize URI: strip query string and normalize slashes
        $uri = $this->normalizeUri($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->normalizeUri($route['pattern']);
            $params = $this->matchPattern($pattern, $uri);

            if ($params !== null) {
                return [
                    'handler' => $route['handler'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Normalize a URI by stripping query string and normalizing slashes.
     */
    private function normalizeUri(string $uri): string
    {
        // Strip query string
        $pos = strpos($uri, '?');
        if ($pos !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Normalize slashes: ensure leading slash, remove trailing slash (except for root)
        $uri = '/' . trim($uri, '/');

        return $uri;
    }

    /**
     * Match a pattern against a URI and extract parameters.
     *
     * @param string $pattern Route pattern with {param} placeholders
     * @param string $uri Normalized URI
     * @return array<string, string>|null Parameters if matched, null otherwise
     */
    private function matchPattern(string $pattern, string $uri): ?array
    {
        $params = [];

        // Check for wildcard pattern (e.g., {path:*})
        if (str_contains($pattern, ':*}')) {
            return $this->matchWildcardPattern($pattern, $uri);
        }

        // Split pattern and URI into segments
        $patternParts = explode('/', trim($pattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        // Must have same number of segments
        if (count($patternParts) !== count($uriParts)) {
            return null;
        }

        // Match each segment
        foreach ($patternParts as $i => $patternPart) {
            $uriPart = $uriParts[$i];

            // Pure placeholder: {param}
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $patternPart, $matches)) {
                $params[$matches[1]] = $uriPart;
            }
            // Mixed segment: {param}-suffix or prefix-{param} or prefix-{param}-suffix
            elseif (preg_match('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $patternPart)) {
                // Build regex from pattern: escape literals, replace {param} with capture group
                $regex = preg_quote($patternPart, '/');
                $regex = '/^' . preg_replace('/\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}/', '([^\/]+)', $regex) . '$/';

                if (preg_match($regex, $uriPart, $valueMatches)) {
                    // Extract parameter names and map to captured values
                    preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $patternPart, $paramNames);
                    foreach ($paramNames[1] as $idx => $name) {
                        $params[$name] = $valueMatches[$idx + 1];
                    }
                } else {
                    return null;
                }
            }
            // Literal segment
            elseif ($patternPart !== $uriPart) {
                return null;
            }
        }

        return $params;
    }

    /**
     * Match a pattern with wildcard parameter (captures rest of path).
     *
     * @param string $pattern Pattern with {param:*} wildcard
     * @param string $uri Normalized URI
     * @return array<string, string>|null
     */
    private function matchWildcardPattern(string $pattern, string $uri): ?array
    {
        $params = [];

        // Find the wildcard parameter
        if (!preg_match('/\{([a-zA-Z_][a-zA-Z0-9_]*):\*\}/', $pattern, $wildcardMatch)) {
            return null;
        }

        $wildcardName = $wildcardMatch[1];
        $wildcardPlaceholder = $wildcardMatch[0];

        // Split pattern at the wildcard
        $patternPrefix = substr($pattern, 0, strpos($pattern, $wildcardPlaceholder));
        $patternPrefixParts = explode('/', trim($patternPrefix, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        // URI must have at least as many parts as the prefix
        if (count($uriParts) < count($patternPrefixParts)) {
            return null;
        }

        // Match prefix parts and extract any parameters
        foreach ($patternPrefixParts as $i => $patternPart) {
            if ($patternPart === '') {
                continue;
            }

            $uriPart = $uriParts[$i];

            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $patternPart, $matches)) {
                $params[$matches[1]] = $uriPart;
            } elseif ($patternPart !== $uriPart) {
                return null;
            }
        }

        // Capture the rest of the path as the wildcard parameter
        $wildcardParts = array_slice($uriParts, count($patternPrefixParts));
        $params[$wildcardName] = implode('/', $wildcardParts);

        return $params;
    }
}
