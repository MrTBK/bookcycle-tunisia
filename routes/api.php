<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\RequestController;

// These routes are the JSON/API side of the app.
// They are useful for JavaScript calls, tests, or other clients.

// Authentication endpoints for API clients.
$router->get('/api/me', [AuthController::class, 'me']);
$router->post('/api/register', [AuthController::class, 'register']);
$router->post('/api/login', [AuthController::class, 'login']);
$router->post('/api/logout', [AuthController::class, 'logout']);

// Book catalogue and statistics endpoints.
// These routes return book data instead of full HTML pages.
$router->get('/api/books', [BookController::class, 'index']);
$router->post('/api/books', [BookController::class, 'store']);
$router->get('/api/latest-books', [BookController::class, 'latest']);
$router->get('/api/my-books', [BookController::class, 'mine']);
$router->get('/api/stats', [BookController::class, 'stats']);

// Request workflow endpoints for creating and managing book requests.
// These routes let an API client create, read, accept, and reject requests.
$router->post('/api/requests', [RequestController::class, 'store']);
$router->get('/api/my-requests', [RequestController::class, 'mine']);
$router->get('/api/received-requests', [RequestController::class, 'received']);
$router->post('/api/accept-request', [RequestController::class, 'accept']);
$router->post('/api/reject-request', [RequestController::class, 'reject']);

// Lightweight admin metrics endpoint.
// This route gives the admin dashboard numbers in JSON form.
$router->get('/api/admin-stats', [AdminController::class, 'stats']);
