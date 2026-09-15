<?php

declare(strict_types=1);

/**
 * Plain-text prompts served at /p/{slug}.
 *
 * Each page shows one prompt and nothing else, so conference attendees can
 * scan a QR code, copy the text on their phone and paste it into an AI chat.
 * The pages are excluded from indexing via a robots meta tag.
 *
 * Ask for a value the model cannot guess, never for a topic it can paraphrase.
 * Never put the correct answer on these pages: they are reachable over HTTP, so
 * anything printed here is one fetch away from the model being tested.
 */
return [
    '1' => [
        'title' => 'Prompt 1',
        'text' => "Read the page https://openseotest.org/lab/js-injection/domcontentinit\n"
            . 'What is the serial number of the prototype that was pulled from the cold chamber batch?',
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
