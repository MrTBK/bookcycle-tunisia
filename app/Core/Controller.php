<?php

namespace App\Core;

abstract class Controller
{
    protected function render($view, $data = [])
    {
        extract($data, EXTR_SKIP);
        $viewFile = dirname(__DIR__) . '/Views/pages/' . $view . '.php';
        $config = require dirname(__DIR__) . '/Config/config.php';
        $appName = $config['app_name'];
        $baseUrl = $config['base_url'];
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }
        $unreadNotificationsCount = 0;

        // Populate navbar notification data automatically for logged-in users.
        if (isset($data['unreadNotificationsCount'])) {
            $unreadNotificationsCount = (int) $data['unreadNotificationsCount'];
        } elseif (isset($currentUser) && is_array($currentUser) && isset($currentUser['id'])) {
            $notificationModel = new \App\Models\Notification();
            $unreadNotificationsCount = $notificationModel->unreadCountForUser((int) $currentUser['id']);
            $navNotifications = $notificationModel->unreadLatestForUser((int) $currentUser['id'], 5);
        }

        if (!isset($navNotifications)) {
            $navNotifications = [];
        }

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require $viewFile;
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    protected function json($payload, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
