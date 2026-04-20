<?php

// Load the bootstrap file first so config, helpers, sessions, and autoloading are ready.
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Router;

// SCRIPT_NAME usually looks like /bookcycle/public/index.php.
// We clean it up so we can detect whether the app is installed inside a subfolder.
$scriptName = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
}

// Most local setups serve the app from the web root, so the base path starts empty.
$basePath = '';

// Detect the application base path so links still work if the app is served from a subfolder.
if ($scriptName !== '' && str_ends_with($scriptName, '/index.php')) {
    $basePath = rtrim(substr($scriptName, 0, -10), '/');
}

// Save the base path in the request so views and scripts can build correct URLs later.
$_SERVER['APP_BASE_PATH'] = $basePath;

// Create one router instance and then feed it all page routes and API routes.
$router = new Router();

require dirname(__DIR__) . '/routes/web.php';
require dirname(__DIR__) . '/routes/api.php';

// REQUEST_URI may contain both the path and a query string, like /catalog?id=5.
// We keep only the path because routing should not depend on filter/query values.
$requestUri = '/';
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
}

$requestPath = parse_url($requestUri, PHP_URL_PATH);
if (!$requestPath) {
    $requestPath = '/';
}

// If the app lives in a subfolder, remove that prefix before matching routes.
$requestPath = $basePath !== '' && str_starts_with($requestPath, $basePath) ? substr($requestPath, strlen($basePath)) : $requestPath;
$requestPath = $requestPath === '' ? '/' : $requestPath;

// Default to GET so the app still behaves safely if the web server omits the method.
$requestMethod = 'GET';
if (isset($_SERVER['REQUEST_METHOD'])) {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
}

// Hand the cleaned method + path to the router so it can find the right controller action.
$router->dispatch($requestMethod, $requestPath);
