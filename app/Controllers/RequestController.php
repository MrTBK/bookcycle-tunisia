<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;

final class RequestController extends Controller
{
    private BookRequest $requests;
    private Book $books;
    private Notification $notifications;

    public function __construct()
    {
        $this->requests = new BookRequest();
        $this->books = new Book();
        $this->notifications = new Notification();
    }

    public function store(): void
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $payload = json_decode((string) file_get_contents('php://input'), true) ?? $_POST;
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
        $this->notifications->create((int) $book['owner_id'], 'Nouvelle demande pour le livre "' . $book['title'] . '".');

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande envoyee avec succes.';
        $this->redirect('/dashboard');
    }

    public function mine(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->mine((int) Auth::id()));
    }

    public function received(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->received((int) Auth::id()));
    }

    public function accept(): void
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $requestId = (int) ($_GET['id'] ?? 0);
        $payload = json_decode((string) file_get_contents('php://input'), true) ?? $_POST;
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
        $this->notifications->create((int) $request['requester_id'], 'Votre demande pour "' . $book['title'] . '" a ete acceptee.');

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande acceptee avec succes.';
        $this->redirect('/dashboard');
    }

    private function isApiRequest(): bool
    {
        return str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
    }

    private function respondError(string $message, string $redirectPath, int $statusCode): void
    {
        if ($this->isApiRequest()) {
            $this->json(['success' => false, 'error' => $message], $statusCode);
            return;
        }

        $_SESSION['flash_error'] = $message;
        $this->redirect($redirectPath);
    }

    private function redirect(string $path): void
    {
        header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . $path);
        exit;
    }
}
