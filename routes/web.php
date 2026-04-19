<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\NotificationController;
use App\Controllers\PageController;
use App\Controllers\RequestController;

$router->get('/', [PageController::class, 'home']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/catalog', [PageController::class, 'catalog']);
$router->get('/contact', [PageController::class, 'contact']);
$router->get('/login', [PageController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/privacy-policy', [PageController::class, 'privacyPolicy']);
$router->get('/register', [PageController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/dashboard', [PageController::class, 'dashboard']);
$router->get('/notifications/read', [NotificationController::class, 'read']);
$router->get('/add-book', [PageController::class, 'addBook']);
$router->post('/add-book', [BookController::class, 'store']);
$router->post('/request-book', [RequestController::class, 'store']);
$router->post('/accept-request', [RequestController::class, 'accept']);
$router->post('/reject-request', [RequestController::class, 'reject']);
$router->get('/admin', [PageController::class, 'admin']);
$router->post('/admin/toggle-user', [AdminController::class, 'toggleUser']);
$router->post('/admin/delete-book', [AdminController::class, 'deleteBook']);
$router->post('/admin/restore-book', [AdminController::class, 'restoreBook']);
$router->post('/admin/cancel-request', [AdminController::class, 'cancelRequest']);
$router->post('/admin/notify', [AdminController::class, 'notify']);
$router->post('/logout', [AuthController::class, 'logout']);
