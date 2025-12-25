<?php

declare(strict_types=1);

return [
    'js-injection' => [
        'name' => 'JavaScript Injection',
        'description' => 'Tests for content injected via JavaScript at various timing intervals.',
        'template' => 'article',
        'tests' => [
            'domcontentinit' => ['title' => 'DOMContentLoaded Injection'],
            'timeout-100' => ['title' => '100ms Timeout', 'delay' => 100],
            'timeout-250' => ['title' => '250ms Timeout', 'delay' => 250],
            'timeout-500' => ['title' => '500ms Timeout', 'delay' => 500],
            'timeout-1000' => ['title' => '1000ms Timeout', 'delay' => 1000],
            'timeout-2000' => ['title' => '2000ms Timeout', 'delay' => 2000],
        ],
    ],
    'ajax' => [
        'name' => 'AJAX Delays',
        'description' => 'Tests for content loaded via AJAX with various server-side delays.',
        'template' => 'product',
        'tests' => [
            'delay-500' => ['title' => '500ms Delay', 'delay' => 500],
            'delay-1000' => ['title' => '1000ms Delay', 'delay' => 1000],
            'delay-2000' => ['title' => '2000ms Delay', 'delay' => 2000],
            'delay-3000' => ['title' => '3000ms Delay', 'delay' => 3000],
            'delay-4000' => ['title' => '4000ms Delay', 'delay' => 4000],
            'delay-5000' => ['title' => '5000ms Delay', 'delay' => 5000],
        ],
    ],
    'ajax-chain' => [
        'name' => 'AJAX Chain',
        'description' => 'Tests for sequentially loaded AJAX content (waterfall pattern).',
        'template' => 'catalog',
        'tests' => [
            '3-steps' => ['title' => '3-Step Chain', 'steps' => 3],
            '5-steps' => ['title' => '5-Step Chain', 'steps' => 5],
        ],
    ],
    'js-errors' => [
        'name' => 'JavaScript Errors',
        'description' => 'Tests for how bots handle pages with JavaScript errors.',
        'template' => 'review',
        'tests' => [
            'syntax-before' => ['title' => 'Syntax Error Before Injection'],
            'runtime-before' => ['title' => 'Runtime Error Before Injection'],
            'error-between' => ['title' => 'Error Between Injections'],
        ],
    ],
    'realtime' => [
        'name' => 'Realtime Updates',
        'description' => 'Tests for continuously updating content via JavaScript.',
        'template' => 'tool',
        'tests' => [
            'timer' => ['title' => 'Timer Display', 'duration' => 15000],
        ],
    ],
    'http-status' => [
        'name' => 'HTTP Status Codes',
        'description' => 'Tests for how bots handle various HTTP status codes.',
        'template' => 'article',
        'tests' => [
            '301' => ['title' => '301 Permanent Redirect', 'code' => 301],
            '302' => ['title' => '302 Temporary Redirect', 'code' => 302],
            '404' => ['title' => '404 Not Found', 'code' => 404],
            '500' => ['title' => '500 Internal Server Error', 'code' => 500],
            '503' => ['title' => '503 Service Unavailable', 'code' => 503],
        ],
    ],
];
