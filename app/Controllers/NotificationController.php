<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Notification;

// el contoller hedha ykoun fiha method wa7da li tbadal status mta3 notification not read yet to read
class NotificationController extends Controller
{
    private $notifications;

    public function __construct()
    {
        // yasna3 object wa7ed ykoun fiha method markAsRead li tbadal status mta3 notification
        $this->notifications = new Notification();
    }

    public function read()
    {
        //ken el connected user tjih notif
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        //a9ra id mta3 notification min URL 
        $notificationId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        //double check li notification hedhi mta3 el user li connected taw
        $notification = $this->notifications->findForUser($notificationId, (int) Auth::id());

        //ken el notification mawjoud w mta3 el user li connected taw, badal status mta3ha l read
        if ($notification) {
            $this->notifications->markAsRead($notificationId, (int) Auth::id());
        }

        //ba3d ma ihel notification, raja3ou lil dashboard section notifications
        $redirectPath = '/dashboard#section-notifications';

        //ken fama redirect path fi query string w ybda b /, raja3ou lih fi3oudh el  dashboard
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
