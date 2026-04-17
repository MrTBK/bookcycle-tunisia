<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

class AdminController extends Controller
{
    private $users;
    private $books;
    private $requests;
    private $notifications;

    public function __construct()
    {
        $this->users = new User();
        $this->books = new Book();
        $this->requests = new BookRequest();
        $this->notifications = new Notification();
    }

    public function stats()
    {
        if (!Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Acces administrateur requis.'], 403);
            return;
        }

        $this->json([
            'totalUsers' => $this->users->countAll(),
            'totalBooks' => $this->books->countActive(),
            'totalExchanges' => $this->requests->countAccepted(),
            'inactiveBooks' => $this->books->countInactive(),
            'booksByLevel' => $this->books->countByLevel(),
            'mostRequestedSubjects' => $this->books->mostRequestedSubjects(5),
        ]);
    }

    public function toggleUser()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $targetUser = $this->users->findById($userId);

        if (!$targetUser) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        if ((int) $targetUser['id'] === (int) Auth::id()) {
            $this->setFlashAndRedirect('flash_error', 'Vous ne pouvez pas desactiver votre propre compte.', '/admin');
        }

        $isActive = isset($targetUser['is_active']) ? (int) $targetUser['is_active'] : 1;
        $this->users->setActive($userId, $isActive === 0);

        if ($isActive === 0) {
            $this->setFlashAndRedirect('flash_success', 'Utilisateur reactive avec succes.', '/admin');
        }

        $this->setFlashAndRedirect('flash_success', 'Utilisateur desactive avec succes.', '/admin');
    }

    public function deleteBook()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $book = $this->books->find($bookId);

        if (!$book) {
            $this->setFlashAndRedirect('flash_error', 'Livre introuvable.', '/admin');
        }

        $this->books->deactivate($bookId);
        $this->setFlashAndRedirect('flash_success', 'Livre masque avec succes.', '/admin');
    }

    public function restoreBook()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $book = $this->books->find($bookId);

        if (!$book) {
            $allBooks = $this->books->adminAll();
            $book = null;
            foreach ($allBooks as $bookRow) {
                if ((int) $bookRow['id'] === $bookId) {
                    $book = $bookRow;
                    break;
                }
            }
        }

        if (!$book) {
            $this->setFlashAndRedirect('flash_error', 'Livre introuvable.', '/admin');
        }

        $this->books->reactivate($bookId);
        $this->setFlashAndRedirect('flash_success', 'Livre reactive avec succes.', '/admin');
    }

    public function cancelRequest()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $requestId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->setFlashAndRedirect('flash_error', 'Demande introuvable.', '/admin');
        }

        $this->requests->cancelByAdmin($requestId);

        if (isset($request['status']) && $request['status'] === 'accepted' && isset($request['book_id'])) {
            $this->books->updateStatus((int) $request['book_id'], 'available');
        }

        $this->setFlashAndRedirect('flash_success', 'Demande annulee avec succes.', '/admin');
    }

    public function notify()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';

        if ($message === '') {
            $this->setFlashAndRedirect('flash_error', 'Le message est obligatoire.', '/admin');
        }

        if ($userId === 0) {
            $this->notifications->createForAll($message, 'Administration');
            $this->setFlashAndRedirect('flash_success', 'Notification envoyee a tous les utilisateurs actifs.', '/admin');
        }

        $user = $this->users->findById($userId);
        if (!$user) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        $this->notifications->create($userId, $message, 'Administration');
        $this->setFlashAndRedirect('flash_success', 'Notification envoyee avec succes.', '/admin');
    }

    private function checkAdminAccess()
    {
        if (!Auth::isAdmin()) {
            $this->redirect('/dashboard');
            return false;
        }

        return true;
    }

    private function setFlashAndRedirect($flashKey, $message, $path)
    {
        $_SESSION[$flashKey] = $message;
        $this->redirect($path);
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
