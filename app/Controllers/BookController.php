<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\AcademicOption;
use App\Models\Book;
use App\Models\BookRequest;

class BookController extends Controller
{
    private $books;
    private $requests;
    private $academicOptions;

    public function __construct()
    {
        $this->books = new Book();
        $this->requests = new BookRequest();
        $this->academicOptions = new AcademicOption();
    }

    public function latest()
    {
        $this->json($this->books->latest(4));
    }

    public function index()
    {
        $filters = [
            'level' => $_GET['level'] ?? null,
            'class_name' => $_GET['class_name'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        $this->json($this->books->all($filters));
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

        if (!$this->isValidSubject((string) $payload['level'], (string) $payload['class_name'], (string) $payload['subject'])) {
            $this->respondError('La matiere selectionnee ne correspond pas a la classe choisie.', '/add-book', 422);
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

    public function mine()
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->books->mine((int) Auth::id()));
    }

    public function stats()
    {
        $this->json([
            'totalBooks' => $this->books->countActive(),
            'totalExchanges' => $this->requests->countAccepted(),
            'moneySaved' => $this->requests->sumAcceptedValueGlobal(),
        ]);
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

    private function buildBookTitle($payload)
    {
        $subject = isset($payload['subject']) ? trim((string) $payload['subject']) : '';
        $className = isset($payload['class_name']) ? trim((string) $payload['class_name']) : '';
        $level = isset($payload['level']) ? trim((string) $payload['level']) : '';

        return $subject . ' - ' . $className . ' - ' . $level;
    }

    private function isValidClassForLevel($level, $className)
    {
        return $this->academicOptions->hasClassForLevel($level, $className);
    }

    private function isValidSubject($level, $className, $subject)
    {
        return $this->academicOptions->hasSubjectForClass($level, $className, $subject);
    }
}
