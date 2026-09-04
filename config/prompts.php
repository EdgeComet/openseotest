<?php

declare(strict_types=1);

/**
 * Plain-text prompts served at /p/{slug}.
 *
 * Each page shows one prompt and nothing else, so conference attendees can
 * scan a QR code, copy the text on their phone and paste it into an AI chat.
 * The pages are excluded from indexing via a robots meta tag.
 */
return [
    '1' => [
        'title' => 'Prompt 1',
        'text' => "read the page https://openseotest.org/lab/js-injection/domcontentinit\n"
            . 'What does the PixelPulse team say about innovation on this page?',
    ],
    '2' => [
        'title' => 'Prompt 2',
        'text' => "What is the condition of the product?\n"
            . 'https://openseotest.org/lab/semantic-html/product-a',
    ],
    '3' => [
        'title' => 'Prompt 3',
        'text' => 'What is mpn of the product https://openseotest.org/lab/semantic-html/product-jsonld-head',
    ],
];
