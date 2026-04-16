<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;

final class BookController extends Controller
{
    private Book $books;
    private BookRequest $requests;

    public function __construct()
    {
        $this->books = new Book();
        $this->requests = new BookRequest();
    }

    public function latest(): void
    {
        $this->json($this->books->latest(4));
    }

    public function index(): void
    {
        $filters = [
            'level' => $_GET['level'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        $this->json($this->books->all($filters));
    }

    public function store(): void
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $payload = json_decode((string) file_get_contents('php://input'), true) ?? $_POST;

        foreach (['subject', 'level', 'class_name', 'condition', 'estimated_price'] as $field) {
            if (empty($payload[$field])) {
                $this->respondError('Veuillez remplir tous les champs obligatoires.', '/add-book', 422);
                return;
            }
        }

        if (!$this->isValidClassForLevel((string) $payload['level'], (string) $payload['class_name'])) {
            $this->respondError('La classe selectionnee ne correspond pas au niveau choisi.', '/add-book', 422);
            return;
        }

        $bookId = $this->books->create(array_merge($payload, [
            'title' => $this->buildBookTitle($payload),
            'owner_id' => Auth::id(),
        ]));

        if ($this->isApiRequest()) {
            $this->json(['success' => true, 'bookId' => $bookId]);
            return;
        }

        $_SESSION['flash_success'] = 'Livre ajoute avec succes.';
        $this->redirect('/dashboard');
    }

    public function mine(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->books->mine((int) Auth::id()));
    }

    public function stats(): void
    {
        $this->json([
            'totalBooks' => $this->books->countActive(),
            'totalExchanges' => $this->requests->countAccepted(),
            'moneySaved' => $this->requests->sumAcceptedValueGlobal(),
        ]);
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

    private function buildBookTitle(array $payload): string
    {
        return trim((string) ($payload['subject'] ?? ''))
            . ' - '
            . trim((string) ($payload['class_name'] ?? ''))
            . ' - '
            . trim((string) ($payload['level'] ?? ''));
    }

    private function isValidClassForLevel(string $level, string $className): bool
    {
        $options = [
            'Primaire' => [
                '1ere annee',
                '2eme annee',
                '3eme annee',
                '4eme annee',
                '5eme annee',
                '6eme annee',
            ],
            'College' => [
                '7eme annee',
                '8eme annee',
                '9eme annee',
            ],
            'Lycee' => [
                '1ere secondaire',
                '2eme info',
                '2eme sc',
                '2eme lettre',
                '2eme eco',
                '3eme math',
                '3eme tech',
                '3eme info',
                '3eme sc',
                '3eme lettre',
                '3eme eco',
                'bac math',
                'bac tech',
                'bac info',
                'bac sc',
                'bac lettre',
                'bac eco',
            ],
        ];

        return in_array($className, $options[$level] ?? [], true);
    }
}
