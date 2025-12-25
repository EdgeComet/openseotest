<?php

declare(strict_types=1);

namespace Ost;

/**
 * Asset URL generator with debug hash.
 *
 * All asset URLs include the debug hash for request correlation:
 * /dist/{hash}/css/site.css
 * /dist/{hash}/js/site.js
 */
class Asset
{
    private string $hash;

    /**
     * Create an Asset helper with the given debug hash.
     */
    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * Generate URL for a CSS file.
     *
     * @param string $file CSS filename (e.g., 'site.css')
     * @return string Full URL path with hash
     */
    public function css(string $file): string
    {
        return "/dist/{$this->hash}/css/{$file}";
    }

    /**
     * Generate URL for a JS file.
     *
     * @param string $file JS filename (e.g., 'site.js')
     * @return string Full URL path with hash
     */
    public function js(string $file): string
    {
        return "/dist/{$this->hash}/js/{$file}";
    }

    /**
     * Generate URL for an arbitrary asset path.
     *
     * @param string $path Asset path (e.g., 'images/logo.png')
     * @return string Full URL path with hash
     */
    public function url(string $path): string
    {
        return "/dist/{$this->hash}/{$path}";
    }

    /**
     * Get the debug hash.
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
