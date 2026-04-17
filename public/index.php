<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Router;

$scriptName = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
}
$basePath = '';

if ($scriptName !== '' && str_ends_with($scriptName, '/index.php')) {
    $basePath = rtrim(substr($scriptName, 0, -10), '/');
}

$_SERVER['APP_BASE_PATH'] = $basePath;

$router = new Router();

require dirname(__DIR__) . '/routes/web.php';
require dirname(__DIR__) . '/routes/api.php';

$requestUri = '/';
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
}

$requestPath = parse_url($requestUri, PHP_URL_PATH);
if (!$requestPath) {
    $requestPath = '/';
}
$requestPath = $basePath !== '' && str_starts_with($requestPath, $basePath) ? substr($requestPath, strlen($basePath)) : $requestPath;
$requestPath = $requestPath === '' ? '/' : $requestPath;

$requestMethod = 'GET';
if (isset($_SERVER['REQUEST_METHOD'])) {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
}

$router->dispatch($requestMethod, $requestPath);
