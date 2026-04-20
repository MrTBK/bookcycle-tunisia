<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\NotificationController;
use App\Controllers\PageController;
use App\Controllers\RequestController;

// These routes are the "normal website pages" a browser can open with GET or POST.
// You can read them like this:
// "When the browser opens this path, call this controller method."

// Public pages available without authentication.
$router->get('/', [PageController::class, 'home']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/catalog', [PageController::class, 'catalog']);
$router->get('/contact', [PageController::class, 'contact']);
$router->get('/login', [PageController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/privacy-policy', [PageController::class, 'privacyPolicy']);
$router->get('/register', [PageController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);

// Authenticated user pages and actions.
// These routes still exist publicly in the router,
// but the controller methods themselves check if the user is connected.
$router->get('/dashboard', [PageController::class, 'dashboard']);
$router->get('/notifications/read', [NotificationController::class, 'read']);
$router->get('/add-book', [PageController::class, 'addBook']);
$router->post('/add-book', [BookController::class, 'store']);
$router->post('/request-book', [RequestController::class, 'store']);
$router->post('/accept-request', [RequestController::class, 'accept']);
$router->post('/reject-request', [RequestController::class, 'reject']);

// Administrator-only area used for moderation and global notifications.
// Just like above, the controller protects these actions with an admin check.
$router->get('/admin', [PageController::class, 'admin']);
$router->post('/admin/toggle-user', [AdminController::class, 'toggleUser']);
$router->post('/admin/delete-book', [AdminController::class, 'deleteBook']);
$router->post('/admin/restore-book', [AdminController::class, 'restoreBook']);
$router->post('/admin/cancel-request', [AdminController::class, 'cancelRequest']);
$router->post('/admin/notify', [AdminController::class, 'notify']);

// Session cleanup route.
// This removes the connected user from the session.
$router->post('/logout', [AuthController::class, 'logout']);
