<?php
/**
 * Standalone 50x error page for nginx.
 * This file is served directly by nginx when a 50x error occurs.
 */

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ost\Controllers\ErrorController;
use Ost\Response;

// Determine the error code from the status (nginx sets this)
$errorCode = http_response_code() ?: 500;

// Ensure it's a valid 50x code
if ($errorCode < 500 || $errorCode > 599) {
    $errorCode = 500;
}

$errorController = new ErrorController();
$response = $errorController->serverError($errorCode);
Response::current()->send($response);
