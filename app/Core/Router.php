<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->map('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->map('POST', $path, $handler);
    }

    public function dispatch($method, $path)
    {
        // Normalize the incoming URL so route matching stays consistent.
        $normalizedPath = $this->normalizePath($path);
        $handler = null;

        if (isset($this->routes[$method]) && isset($this->routes[$method][$normalizedPath])) {
            $handler = $this->routes[$method][$normalizedPath];
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
    }

    private function map($method, $path, $handler)
    {
        $this->routes[$method][$this->normalizePath($path)] = $handler;
    }

    private function normalizePath($path)
    {
        if ($path === '') {
            return '/';
        }

        $path = '/' . trim($path, '/');

        return $path === '/index.php' ? '/' : $path;
    }
}
