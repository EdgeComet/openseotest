<?php

declare(strict_types=1);

return [
    'js-injection' => [
        'name' => 'JavaScript Injection',
        'description' => 'Evaluates whether bots can execute JavaScript and how long they are willing to wait for content to appear after the initial page load. Crucial for client-side rendered apps.',
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
        'description' => 'Measures the bot\'s ability to fetch external data asynchronously. Tests different server response times to determine the timeout thresholds of various crawlers.',
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
        'description' => 'Simulates complex loading dependencies where one request must finish before the next begins (Waterfall). Essential for testing Single Page Applications (SPAs) with nested data requirements.',
        'template' => 'catalog',
        'tests' => [
            '3-steps' => ['title' => '3-Step Chain', 'steps' => 3],
            '5-steps' => ['title' => '5-Step Chain', 'steps' => 5],
        ],
    ],
    'js-errors' => [
        'name' => 'JavaScript Errors',
        'description' => 'Checks the resilience of crawlers when encountering script errors. Does a syntax or runtime error in one script block prevent the indexing of subsequent content?',
        'template' => 'review',
        'tests' => [
            'syntax-before' => ['title' => 'Syntax Error Before Injection'],
            'runtime-before' => ['title' => 'Runtime Error Before Injection'],
            'error-between' => ['title' => 'Error Between Injections'],
        ],
    ],
    'realtime' => [
        'name' => 'Realtime Updates',
        'description' => 'Observes how bots interact with content that changes in real-time. Do they take a static snapshot, or can they perceive dynamic updates over a short period?',
        'template' => 'tool',
        'tests' => [
            'timer' => ['title' => 'Timer Display', 'duration' => 15000],
        ],
    ],
    'http-status' => [
        'name' => 'HTTP Status Codes',
        'description' => 'Verifies standard protocol compliance. Ensures that bots correctly interpret redirects (301/302) and error states (404/500) rather than indexing error pages.',
        'template' => 'article',
        'tests' => [
            '301' => ['title' => '301 Permanent Redirect', 'code' => 301],
            '302' => ['title' => '302 Temporary Redirect', 'code' => 302],
            '404' => ['title' => '404 Not Found', 'code' => 404],
            '500' => ['title' => '500 Internal Server Error', 'code' => 500],
            '503' => ['title' => '503 Service Unavailable', 'code' => 503],
        ],
    ],
    'semantic-html' => [
        'name' => 'Semantic HTML',
        'description' => 'Tests AI comprehension of semantic HTML vs utility CSS classes',
        'template' => 'semantic-product',
        'tests' => [
            'product-a' => ['title' => 'Product Page (Semantic)', 'template' => 'semantic-product', 'variant' => 'semantic'],
            'product-b' => ['title' => 'Product Page (Tailwind)', 'template' => 'semantic-product', 'variant' => 'tailwind'],
            'product-jsonld' => ['title' => 'Product Page (JSON-LD)', 'template' => 'semantic-product-jsonld', 'templateCss' => 'semantic-product'],
            'product-jsonld-head' => ['title' => 'Product Page (JSON-LD in Head)', 'template' => 'semantic-product-jsonld-head', 'templateCss' => 'semantic-product', 'headJsonLd' => true],
            'job-a' => ['title' => 'Job Listing (Semantic)', 'template' => 'semantic-job', 'variant' => 'semantic'],
            'job-b' => ['title' => 'Job Listing (Tailwind)', 'template' => 'semantic-job', 'variant' => 'tailwind'],
        ],
    ],
    'loading-time' => [
        'name' => 'Loading Time',
        'description' => 'Tests how long bots wait for server response. Server-side delays simulate slow page generation.',
        'template' => 'loading-time',
        'templateCss' => 'article',
        'tests' => [
            '1-second' => ['title' => '1 Second Delay', 'delay' => 1000],
            '2-seconds' => ['title' => '2 Second Delay', 'delay' => 2000],
            '3-seconds' => ['title' => '3 Second Delay', 'delay' => 3000],
            '4-seconds' => ['title' => '4 Second Delay', 'delay' => 4000],
            '5-seconds' => ['title' => '5 Second Delay', 'delay' => 5000],
            '6-seconds' => ['title' => '6 Second Delay', 'delay' => 6000],
            '7-seconds' => ['title' => '7 Second Delay', 'delay' => 7000],
            '8-seconds' => ['title' => '8 Second Delay', 'delay' => 8000],
            '9-seconds' => ['title' => '9 Second Delay', 'delay' => 9000],
            '10-seconds' => ['title' => '10 Second Delay', 'delay' => 10000],
        ],
    ],
];
