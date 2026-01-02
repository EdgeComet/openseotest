<?php

declare(strict_types=1);

use Ost\Controllers\ApiController;
use Ost\Controllers\AssetController;
use Ost\Controllers\HomeController;
use Ost\Controllers\LabController;
use Ost\Controllers\SitemapController;

return [
    ['method' => 'GET', 'pattern' => '/', 'handler' => [HomeController::class, 'index']],
    ['method' => 'GET', 'pattern' => '/sitemap.xml', 'handler' => [SitemapController::class, 'index']],
    // Specific /lab routes must come before generic /lab/{category}/{test}
    ['method' => 'GET', 'pattern' => '/lab/ajax/delay-{delay}/{hash}/fetch', 'handler' => [LabController::class, 'ajaxFetch']],
    ['method' => 'GET', 'pattern' => '/lab/ajax-chain/{steps}-steps/{hash}/fetch/{step}', 'handler' => [LabController::class, 'ajaxChainFetch']],
    ['method' => 'GET', 'pattern' => '/lab/realtime/timer/{hash}/status', 'handler' => [LabController::class, 'realtimeStatus']],
    ['method' => 'GET', 'pattern' => '/lab/http-status/{code}/target', 'handler' => [LabController::class, 'redirectTarget']],
    ['method' => 'GET', 'pattern' => '/lab/semantic-html', 'handler' => [LabController::class, 'semanticIndex']],
    ['method' => 'GET', 'pattern' => '/lab/{category}/{test}', 'handler' => [LabController::class, 'show']],
    ['method' => 'GET', 'pattern' => '/dist/{hash}/{path:*}', 'handler' => [AssetController::class, 'serve']],
    ['method' => 'POST', 'pattern' => '/api/beacon/{hash}/{event}', 'handler' => [ApiController::class, 'beacon']],
];
