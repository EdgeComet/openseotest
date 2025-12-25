<?php

declare(strict_types=1);

/**
 * Front controller - all requests are routed through this file.
 */

require_once dirname(__DIR__) . '/src/bootstrap.php';

use Ost\Controllers\ErrorController;
use Ost\Response;
use Ost\Router;

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Load routes and create router
$routes = require APP_ROOT . '/config/routes.php';
$router = new Router($routes);

// Match the request
$match = $router->match($method, $uri);

if ($match === null) {
    // No route matched - show styled 404 page
    $errorController = new ErrorController();
    $response = $errorController->notFound(parse_url($uri, PHP_URL_PATH));
    Response::current()->send($response);
    return;
}

// Extract handler and params
$handler = $match['handler'];
$params = $match['params'];

// Instantiate controller and call method
if (is_array($handler) && count($handler) === 2) {
    [$controllerClass, $method] = $handler;
    $controller = new $controllerClass();
    $response = $controller->$method(...array_values($params));
} elseif (is_callable($handler)) {
    $response = $handler(...array_values($params));
} else {
    throw new RuntimeException('Invalid route handler');
}

// Get response instance and send headers set by controller
$responseObj = Response::current();

// Set default Content-Type if not already set
if (!$responseObj->hasHeader('Content-Type')) {
    $responseObj->setHeader('Content-Type', 'text/html; charset=utf-8');
}

// Send headers and body
$responseObj->send($response);
