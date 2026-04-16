<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = dirname(__DIR__) . '/Views/pages/' . $view . '.php';
        $config = require dirname(__DIR__) . '/Config/config.php';
        $appName = $config['app_name'];
        $baseUrl = $config['base_url'];
        $basePath = $_SERVER['APP_BASE_PATH'] ?? '';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require $viewFile;
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    protected function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
