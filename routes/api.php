<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\RequestController;

$router->get('/api/me', [AuthController::class, 'me']);
$router->post('/api/register', [AuthController::class, 'register']);
$router->post('/api/login', [AuthController::class, 'login']);
$router->post('/api/logout', [AuthController::class, 'logout']);

$router->get('/api/books', [BookController::class, 'index']);
$router->post('/api/books', [BookController::class, 'store']);
$router->get('/api/latest-books', [BookController::class, 'latest']);
$router->get('/api/my-books', [BookController::class, 'mine']);
$router->get('/api/stats', [BookController::class, 'stats']);

$router->post('/api/requests', [RequestController::class, 'store']);
$router->get('/api/my-requests', [RequestController::class, 'mine']);
$router->get('/api/received-requests', [RequestController::class, 'received']);
$router->post('/api/accept-request', [RequestController::class, 'accept']);

$router->get('/api/admin-stats', [AdminController::class, 'stats']);

