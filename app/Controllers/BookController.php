<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;

class BookController extends Controller
{
    private $books;
    private $requests;

    public function __construct()
    {
        $this->books = new Book();
        $this->requests = new BookRequest();
    }

    public function latest()
    {
        //raja3 el ktob el jdod 
        $this->json($this->books->latest(4));
    }

    public function index()
    {
        //a9ra catalogue w 7ot fih les filtres eli jayin
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
        // Only connected users are allowed to publish books.
        //ken el user connecte inajem y3ml post l book 
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }
        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        //wa9ef ken fama champ obligatoire mch m3abi
        foreach (['subject', 'level', 'class_name', 'condition', 'estimated_price'] as $field) {
            if (empty($payload[$field])) {
                $this->respondError('Veuillez remplir tous les champs obligatoires.', '/add-book', 422);
                return;
            }
        }
        //khali el level w class mte3ou ykounou m3a ba3thom
        if (!$this->isValidClassForLevel((string) $payload['level'], (string) $payload['class_name'])) {
            $this->respondError('La classe selectionnee ne correspond pas au niveau choisi.', '/add-book', 422);
            return;
        }
        if (!$this->isValidSubject((string) $payload['subject'])) {
            $this->respondError('Veuillez choisir une matiere valide dans la liste.', '/add-book', 422);
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
        //ken el user inajem ichouf el ktob mt3ou
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->books->mine((int) Auth::id()));
    }

    public function stats()
    {
        //afichage  les stats 3al landing page w admin page
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
        //a3mel title mte3 el book 3al 9a3da : matiere - classe - niveau
        $subject = isset($payload['subject']) ? trim((string) $payload['subject']) : '';
        $className = isset($payload['class_name']) ? trim((string) $payload['class_name']) : '';
        $level = isset($payload['level']) ? trim((string) $payload['level']) : '';

        return $subject
            . ' - '
            . $className
            . ' - '
            . $level;
    }

    private function isValidClassForLevel($level, $className)
    {
        //a3mel validation ken el class name mte3ou ykoun m3a el level eli ikhtarou l user
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

        $classes = [];
        if (isset($options[$level])) {
            $classes = $options[$level];
        }

        return in_array($className, $classes, true);
    }

    private function isValidSubject($subject)
    {
        //khali les matieres m3a ba3thom w ma3andhomch orthographe mouchkla 3al reporting w filtering
        $subjects = [
            'Arabe',
            'Francais',
            'Anglais',
            'Mathematiques',
            'Sciences',
            'Physique',
            'Chimie',
            'Informatique',
            'Histoire',
            'Geographie',
            'Education islamique',
            'Philosophie',
            'Economie',
            'Gestion',
            'Technique',
        ];

        return in_array(trim($subject), $subjects, true);
    }
}
