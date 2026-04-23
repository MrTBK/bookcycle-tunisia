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

    public function store()
    {
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        $payload = $_POST;

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

        if (!$this->isValidSubject((string) $payload['subject'])) {
            $this->respondError('Veuillez choisir une matiere valide dans la liste.', '/add-book', 422);
            return;
        }

        $this->books->create(array_merge($payload, [
            'title' => $this->buildBookTitle($payload),
            'owner_id' => Auth::id(),
        ]));

        $_SESSION['flash_success'] = 'Livre ajoute avec succes.';
        $this->redirect('/dashboard');
    }

    private function respondError($message, $redirectPath, $statusCode)
    {
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

    private function isValidSubject($subject)
    {
        return $this->academicOptions->hasSubject($subject);
    }
}
