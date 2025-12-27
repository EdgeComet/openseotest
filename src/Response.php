<?php

declare(strict_types=1);

namespace Ost;

/**
 * HTTP Response helper.
 *
 * Manages headers and response body for the current request.
 * Provides a singleton pattern for easy access from controllers.
 */
class Response
{
    private static ?Response $instance = null;

    /** @var array<string, string> */
    private array $headers = [];

    private int $statusCode = 200;

    /**
     * Get the current response instance (singleton).
     */
    public static function current(): Response
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Reset the singleton instance (useful for testing).
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Set a response header.
     *
     * @param string $name Header name
     * @param string $value Header value
     */
    public function setHeader(string $name, string $value): void
    {
        // Strip CRLF to prevent header injection
        $name = str_replace(["\r", "\n"], '', $name);
        $value = str_replace(["\r", "\n"], '', $value);

        $this->headers[$name] = $value;
    }

    /**
     * Get a header value.
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get all headers.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the HTTP status code.
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    /**
     * Get the status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Send headers and output body.
     *
     * @param string $body Response body
     */
    public function send(string $body): void
    {
        // Set status code
        http_response_code($this->statusCode);

        // Send all headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Output body
        echo $body;
    }

    /**
     * Check if a specific header has been set.
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }
}
