<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Notification;

// This controller is in charge of opening a notification
// and changing it from "not read yet" to "already read".
class NotificationController extends Controller
{
    private $notifications;

    public function __construct()
    {
        // Create the helper that knows how to manage notification rows in the database.
        $this->notifications = new Notification();
    }

    public function read()
    {
        // Only connected users may read private notifications.
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        // Read the notification id from the URL.
        $notificationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Double-check that this notification really belongs to the current user.
        $notification = $this->notifications->findForUser($notificationId, (int) Auth::id());

        // If the notification exists, mark it as read.
        if ($notification) {
            $this->notifications->markAsRead($notificationId, (int) Auth::id());
        }

        // After opening the notification, go back to the notifications part of the dashboard.
        $redirectPath = '/dashboard#section-notifications';

        // If a safe internal redirect is provided, use it instead.
        if (isset($_GET['redirect']) && is_string($_GET['redirect']) && str_starts_with($_GET['redirect'], '/')) {
            $redirectPath = $_GET['redirect'];
        }

        $this->redirect($redirectPath);
    }

    private function redirect($path)
    {
        // Add the app base path before sending the browser away.
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }

        header('Location: ' . $basePath . $path);
        exit;
    }
}
