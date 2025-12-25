<?php

declare(strict_types=1);

return [
    // Application name
    'name' => 'openseotest.org',

    // Environment: development, production, testing
    'env' => getenv('APP_ENV') ?: 'development',

    // Debug mode - shows detailed errors when true
    'debug' => getenv('APP_DEBUG') !== 'false',

    // Application URL - used for generating absolute URLs
    'url' => getenv('APP_URL') ?: 'https://openseotest.org',

    // Timezone
    'timezone' => getenv('APP_TIMEZONE') ?: 'UTC',
];
