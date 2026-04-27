<?php

// Load the bootstrap file first so config, helpers, sessions, and autoloading are ready.
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\NotificationController;
use App\Controllers\PageController;
use App\Controllers\RequestController;

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

// Directly dispatch web pages and form actions without a separate routes layer.
$handler = null;

if ($requestMethod === 'GET') {
    switch ($requestPath) {
        case '/':
            $handler = [PageController::class, 'home'];
            break;
        case '/about':
            $handler = [PageController::class, 'about'];
            break;
        case '/catalog':
            $handler = [PageController::class, 'catalog'];
            break;
        case '/contact':
            $handler = [PageController::class, 'contact'];
            break;
        case '/login':
            $handler = [PageController::class, 'login'];
            break;
        case '/privacy-policy':
            $handler = [PageController::class, 'privacyPolicy'];
            break;
        case '/register':
            $handler = [PageController::class, 'register'];
            break;
        case '/dashboard':
            $handler = [PageController::class, 'dashboard'];
            break;
        case '/notifications/read':
            $handler = [NotificationController::class, 'read'];
            break;
        case '/add-book':
            $handler = [PageController::class, 'addBook'];
            break;
        case '/edit-book':
            // Affiche le formulaire pre-rempli pour modifier un livre existant.
            $handler = [PageController::class, 'editBook'];
            break;
        case '/admin':
            $handler = [PageController::class, 'admin'];
            break;
    }
} elseif ($requestMethod === 'POST') {
    switch ($requestPath) {
        case '/login':
            $handler = [AuthController::class, 'login'];
            break;
        case '/register':
            $handler = [AuthController::class, 'register'];
            break;
        case '/add-book':
            $handler = [BookController::class, 'store'];
            break;
        case '/edit-book':
            // Traite le formulaire de modification d'un livre (UPDATE en base).
            $handler = [BookController::class, 'update'];
            break;
        case '/request-book':
            $handler = [RequestController::class, 'store'];
            break;
        case '/accept-request':
            $handler = [RequestController::class, 'accept'];
            break;
        case '/reject-request':
            $handler = [RequestController::class, 'reject'];
            break;
        case '/admin/toggle-user':
            $handler = [AdminController::class, 'toggleUser'];
            break;
        case '/admin/delete-book':
            $handler = [AdminController::class, 'deleteBook'];
            break;
        case '/admin/restore-book':
            $handler = [AdminController::class, 'restoreBook'];
            break;
        case '/admin/cancel-request':
            $handler = [AdminController::class, 'cancelRequest'];
            break;
        case '/admin/notify':
            $handler = [AdminController::class, 'notify'];
            break;
        case '/admin/delete-user':
            // Suppression physique d'un utilisateur (DELETE reel en base de donnees).
            $handler = [AdminController::class, 'permanentDeleteUser'];
            break;
        case '/logout':
            $handler = [AuthController::class, 'logout'];
            break;
    }
}

if ($handler === null) {
    http_response_code(404);
    echo '404 - Page not found';
    return;
}

$controllerClass = $handler[0];
$action = $handler[1];
$controller = new $controllerClass();
$controller->$action();
