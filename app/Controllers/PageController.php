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
        $books = (new Book())->latest(4);

        $this->render('home', [
            'pageTitle' => 'Accueil',
            'currentUser' => Auth::user(),
            'featuredBooks' => $books,
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

        $this->render('dashboard', [
            'pageTitle' => 'Tableau de bord',
            'currentUser' => Auth::user(),
            'myBooks' => $bookModel->mine((int) Auth::id()),
            'receivedRequests' => $requests->received((int) Auth::id()),
            'sentRequests' => $requests->mine((int) Auth::id()),
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
        ]);
    }

    public function admin(): void
    {
        if (!Auth::isAdmin()) {
            header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . '/dashboard');
            exit;
        }

        $bookModel = new Book();
        $acceptedCount = (new BookRequest())->countAccepted();

        $this->render('admin', [
            'pageTitle' => 'Administration',
            'currentUser' => Auth::user(),
            'adminStats' => [
                'totalUsers' => (new User())->countAll(),
                'totalBooks' => $bookModel->countActive(),
                'totalExchanges' => $acceptedCount,
                'moneySaved' => $acceptedCount * 25,
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
}
