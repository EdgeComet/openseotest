<?php

declare(strict_types=1);

namespace Ost;

/**
 * Generates cryptographically random debug hashes.
 *
 * Each page load gets a unique hash that appears in:
 * - Debug badge on page
 * - Asset URLs (/dist/{hash}/css/...)
 * - Beacon URLs (/api/beacon/{hash}/...)
 * - X-Debug-Hash response header
 *
 * This allows correlating all requests for a single page view.
 */
class HashGenerator
{
    /**
     * Generate an 8-character lowercase hex hash.
     *
     * Uses random_bytes(4) for cryptographic randomness,
     * converted to hex for URL-safe characters.
     *
     * @return string 8-character lowercase hex string [a-f0-9]
     */
    public static function generate(): string
    {
        return substr(bin2hex(random_bytes(4)), 0, 8);
    }
}
