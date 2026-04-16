<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    public function dispatch(string $method, string $path): void
    {
        $normalizedPath = $this->normalizePath($path);
        $handler = $this->routes[$method][$normalizedPath] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Page not found';
            return;
        }

        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass();
        $controller->$action();
    }

    private function map(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$this->normalizePath($path)] = $handler;
    }

    private function normalizePath(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        $path = '/' . trim($path, '/');

        return $path === '/index.php' ? '/' : $path;
    }
}

