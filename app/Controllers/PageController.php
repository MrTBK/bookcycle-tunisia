<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\User;

final class PageController extends Controller
{
    public function home(): void
    {
        $bookModel = new Book();
        $requestModel = new BookRequest();
        $acceptedCount = $requestModel->countAccepted();

        $this->render('home', [
            'pageTitle' => 'Accueil',
            'currentUser' => Auth::user(),
            'featuredBooks' => $bookModel->latest(4),
            'homeStats' => [
                'totalBooks' => $bookModel->countActive(),
                'totalExchanges' => $acceptedCount,
                'moneySaved' => $requestModel->sumAcceptedValueGlobal(),
            ],
        ]);
    }

    public function catalog(): void
    {
        $filters = [
            'level' => $_GET['level'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        $this->render('catalog', [
            'pageTitle' => 'Catalogue',
            'currentUser' => Auth::user(),
            'catalogBooks' => (new Book())->all($filters),
            'selectedBook' => !empty($filters['id']) ? (new Book())->find((int) $filters['id']) : null,
        ]);
    }

    public function login(): void
    {
        $this->render('login', [
            'pageTitle' => 'Connexion',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function register(): void
    {
        $this->render('register', [
            'pageTitle' => 'Inscription',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function dashboard(): void
    {
        if (!Auth::check()) {
            header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . '/login');
            exit;
        }

        $requests = new BookRequest();
        $bookModel = new Book();
        $booksReceived = $requests->countAcceptedForRequester((int) Auth::id());
        $booksGiven = $requests->countAcceptedForOwner((int) Auth::id());
        $moneySaved = $requests->sumAcceptedValueForRequester((int) Auth::id());
        $moneySavedForOthers = $requests->sumAcceptedValueForOwner((int) Auth::id());

        $this->render('dashboard', [
            'pageTitle' => 'Tableau de bord',
            'currentUser' => Auth::user(),
            'myBooks' => $bookModel->mine((int) Auth::id()),
            'receivedRequests' => $requests->received((int) Auth::id()),
            'sentRequests' => $requests->mine((int) Auth::id()),
            'dashboardStats' => [
                'booksReceived' => $booksReceived,
                'booksGiven' => $booksGiven,
                'moneySaved' => $moneySaved,
                'moneySavedForOthers' => $moneySavedForOthers,
            ],
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function addBook(): void
    {
        if (!Auth::check()) {
            header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . '/login');
            exit;
        }

        $this->render('add-book', [
            'pageTitle' => 'Ajouter un livre',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
            'classOptions' => $this->classOptions(),
        ]);
    }

    public function admin(): void
    {
        if (!Auth::isAdmin()) {
            header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . '/dashboard');
            exit;
        }

        $bookModel = new Book();
        $requestModel = new BookRequest();
        $acceptedCount = $requestModel->countAccepted();

        $this->render('admin', [
            'pageTitle' => 'Administration',
            'currentUser' => Auth::user(),
            'adminStats' => [
                'totalUsers' => (new User())->countAll(),
                'totalBooks' => $bookModel->countActive(),
                'totalExchanges' => $acceptedCount,
                'moneySaved' => $requestModel->sumAcceptedValueGlobal(),
            ],
            'adminBooks' => array_slice($bookModel->all(['status' => 'all']), 0, 10),
        ]);
    }

    private function pullFlash(string $key): ?string
    {
        $value = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);

        return is_string($value) ? $value : null;
    }

    private function classOptions(): array
    {
        return [
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
    }
}
