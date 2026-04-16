<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Router;

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$basePath = '';

if ($scriptName !== '' && str_ends_with($scriptName, '/index.php')) {
    $basePath = rtrim(substr($scriptName, 0, -10), '/');
}

$_SERVER['APP_BASE_PATH'] = $basePath;

$router = new Router();

require dirname(__DIR__) . '/routes/web.php';
require dirname(__DIR__) . '/routes/api.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requestPath = $basePath !== '' && str_starts_with($requestPath, $basePath) ? substr($requestPath, strlen($basePath)) : $requestPath;
$requestPath = $requestPath === '' ? '/' : $requestPath;

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestPath);
