<?php
/**
 * Standalone 404 error page for nginx.
 * This file is served directly by nginx when a 404 error occurs.
 */

require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

use Ost\Controllers\ErrorController;
use Ost\Response;

$errorController = new ErrorController();
$response = $errorController->notFound($_SERVER['REQUEST_URI'] ?? null);
Response::current()->send($response);
