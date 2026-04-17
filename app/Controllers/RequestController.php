<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

class RequestController extends Controller
{
    private $requests;
    private $books;
    private $notifications;
    private $users;

    public function __construct()
    {
        $this->requests = new BookRequest();
        $this->books = new Book();
        $this->notifications = new Notification();
        $this->users = new User();
    }

    public function store()
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }
        $bookId = (int) ($payload['bookId'] ?? 0);
        $userId = (int) Auth::id();
        $book = $this->books->find($bookId);

        if (!$book) {
            $this->respondError('Livre introuvable.', '/catalog', 404);
            return;
        }

        if ((int) $book['owner_id'] === $userId) {
            $this->respondError('Vous ne pouvez pas demander votre propre livre.', '/catalog?id=' . $bookId, 422);
            return;
        }

        if ($this->requests->existsPending($bookId, $userId)) {
            $this->respondError('Une demande en attente existe deja.', '/catalog?id=' . $bookId, 422);
            return;
        }

        $this->requests->create($bookId, $userId);
        $this->notifications->create(
            (int) $book['owner_id'],
            'Nouvelle demande pour le livre "' . $book['title'] . '".',
            (string) (Auth::user()['name'] ?? 'Utilisateur')
        );

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande envoyee avec succes.';
        $this->redirect('/dashboard');
    }

    public function mine()
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->mine((int) Auth::id()));
    }

    public function received()
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->received((int) Auth::id()));
    }

    public function accept()
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $requestId = (int) ($_GET['id'] ?? 0);
        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }
        $meetingNote = trim((string) ($payload['meetingNote'] ?? ''));
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->respondError('Demande introuvable.', '/dashboard', 404);
            return;
        }

        $book = $this->books->find((int) $request['book_id']);
        if (!$book || (int) $book['owner_id'] !== (int) Auth::id()) {
            $this->respondError('Action interdite.', '/dashboard', 403);
            return;
        }

        if ($meetingNote === '') {
            $this->respondError('La note de rendez-vous est obligatoire.', '/dashboard', 422);
            return;
        }

        $this->requests->accept($requestId, (int) $request['book_id'], $meetingNote);
        $this->books->updateStatus((int) $request['book_id'], 'reserved');
        $owner = $this->users->findById((int) $book['owner_id']);
        $requester = $this->users->findById((int) $request['requester_id']);

        if ($requester && $owner) {
            $this->notifications->create(
                (int) $request['requester_id'],
                'Votre demande pour "' . $book['title'] . '" a ete acceptee. Contact du proprietaire: '
                . $owner['name'] . ' | Email: ' . $owner['email'] . ' | Telephone: ' . $owner['phone'],
                (string) $owner['name']
            );
            $this->notifications->create(
                (int) $book['owner_id'],
                'Demande acceptee pour "' . $book['title'] . '". Contact du demandeur: '
                . $requester['name'] . ' | Email: ' . $requester['email'] . ' | Telephone: ' . $requester['phone'],
                (string) $requester['name']
            );
        }

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $contactSummary = '';
        if ($requester) {
            $contactSummary = ' Contact demandeur: ' . $requester['name']
                . ' | Email: ' . $requester['email']
                . ' | Telephone: ' . $requester['phone'];
        }

        $_SESSION['flash_success'] = 'Demande acceptee avec succes.' . $contactSummary;
        $this->redirect('/dashboard');
    }

    public function reject()
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $requestId = (int) ($_GET['id'] ?? 0);
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->respondError('Demande introuvable.', '/dashboard', 404);
            return;
        }

        $book = $this->books->find((int) $request['book_id']);
        if (!$book || (int) $book['owner_id'] !== (int) Auth::id()) {
            $this->respondError('Action interdite.', '/dashboard', 403);
            return;
        }

        $this->requests->reject($requestId);
        $this->notifications->create(
            (int) $request['requester_id'],
            'Votre demande pour "' . $book['title'] . '" a ete refusee.',
            (string) (Auth::user()['name'] ?? 'Utilisateur')
        );

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande refusee.';
        $this->redirect('/dashboard');
    }

    private function isApiRequest()
    {
        $requestUri = '';

        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }

        return str_contains($requestUri, '/api/');
    }

    private function respondError($message, $redirectPath, $statusCode)
    {
        if ($this->isApiRequest()) {
            $this->json(['success' => false, 'error' => $message], $statusCode);
            return;
        }

        $_SESSION['flash_error'] = $message;
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
