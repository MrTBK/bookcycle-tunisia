<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

// This controller is the "control room" for the admin.
// It lets the admin see big numbers, manage users, manage books,
// cancel requests, and send messages to everybody.
class AdminController extends Controller
{
    // These helpers let the admin controller talk to all the important tables.
    private $users;
    private $books;
    private $requests;
    private $notifications;

    public function __construct()
    {
        // Build all helper objects once so each admin action can reuse them.
        $this->users = new User();
        $this->books = new Book();
        $this->requests = new BookRequest();
        $this->notifications = new Notification();
    }

    public function stats()
    {
        // Only admins are allowed to see admin numbers.
        if (!Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Acces administrateur requis.'], 403);
            return;
        }

        // Return the dashboard metrics used by the admin area and API consumers.
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
        // First make sure the current visitor is really an admin.
        if (!$this->checkAdminAccess()) {
            return;
        }

        // Read the target user id from the URL.
        $userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $targetUser = $this->users->findById($userId);

        // If the user does not exist, stop here.
        if (!$targetUser) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        // We do not allow an admin to turn off their own account by mistake.
        if ((int) $targetUser['id'] === (int) Auth::id()) {
            $this->setFlashAndRedirect('flash_error', 'Vous ne pouvez pas desactiver votre propre compte.', '/admin');
        }

        // Flip the current status instead of asking the UI to send the target state explicitly.
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

        // Read the book id and make sure the book exists.
        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $book = $this->books->find($bookId);

        if (!$book) {
            $this->setFlashAndRedirect('flash_error', 'Livre introuvable.', '/admin');
        }

        // "Delete" here really means "hide from the platform", not erase from the database forever.
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

        // Fallback to the full admin dataset because hidden books may not appear in the public finder.
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

        // If we found the hidden book, make it visible again.
        $this->books->reactivate($bookId);
        $this->setFlashAndRedirect('flash_success', 'Livre reactive avec succes.', '/admin');
    }

    public function cancelRequest()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        // Find the request the admin wants to stop.
        $requestId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->setFlashAndRedirect('flash_error', 'Demande introuvable.', '/admin');
        }

        // Turn the request into rejected/cancelled from the admin side.
        $this->requests->cancelByAdmin($requestId);

        // If an already accepted request is cancelled, the book becomes available again.
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

        // Read the chosen receiver and the text message from the form.
        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';

        if ($message === '') {
            $this->setFlashAndRedirect('flash_error', 'Le message est obligatoire.', '/admin');
        }

        // A user_id of 0 is used as the "broadcast to all active users" option in the form.
        if ($userId === 0) {
            $this->notifications->createForAll($message, 'Administration');
            $this->setFlashAndRedirect('flash_success', 'Notification envoyee a tous les utilisateurs actifs.', '/admin');
        }

        // If the admin chose one person, make sure that user exists first.
        $user = $this->users->findById($userId);
        if (!$user) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        $this->notifications->create($userId, $message, 'Administration');
        $this->setFlashAndRedirect('flash_success', 'Notification envoyee avec succes.', '/admin');
    }

    private function checkAdminAccess()
    {
        // If the current user is not admin, send them away from the admin area.
        if (!Auth::isAdmin()) {
            $this->redirect('/dashboard');
            return false;
        }

        return true;
    }

    private function setFlashAndRedirect($flashKey, $message, $path)
    {
        // Save a temporary message in session, then move the browser to another page.
        $_SESSION[$flashKey] = $message;
        $this->redirect($path);
    }

    private function redirect($path)
    {
        // Add the app base path before redirecting.
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }

        header('Location: ' . $basePath . $path);
        exit;
    }
}
