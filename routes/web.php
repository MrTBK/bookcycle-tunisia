<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\PageController;
use App\Controllers\RequestController;

$router->get('/', [PageController::class, 'home']);
$router->get('/catalog', [PageController::class, 'catalog']);
$router->get('/login', [PageController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [PageController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/dashboard', [PageController::class, 'dashboard']);
$router->get('/add-book', [PageController::class, 'addBook']);
$router->post('/add-book', [BookController::class, 'store']);
$router->post('/request-book', [RequestController::class, 'store']);
$router->post('/accept-request', [RequestController::class, 'accept']);
$router->get('/admin', [PageController::class, 'admin']);
$router->post('/logout', [AuthController::class, 'logout']);
