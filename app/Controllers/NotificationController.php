<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    private $notifications;

    public function __construct()
    {
        $this->notifications = new Notification();
    }

    public function read()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $notificationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $notification = $this->notifications->findForUser($notificationId, (int) Auth::id());

        if ($notification) {
            $this->notifications->markAsRead($notificationId, (int) Auth::id());
        }

        $redirectPath = '/dashboard#section-notifications';
        if (isset($_GET['redirect']) && is_string($_GET['redirect']) && str_starts_with($_GET['redirect'], '/')) {
            $redirectPath = $_GET['redirect'];
        }

        $this->redirect($redirectPath);
    }

    private function redirect($path)
    {
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }

        header('Location: ' . $basePath . $path);
        exit;
    }
}
