<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

class PageController extends Controller
{
    public function home()
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

    public function about()
    {
        $this->render('about', [
            'pageTitle' => 'A propos',
            'currentUser' => Auth::user(),
        ]);
    }

    public function catalog()
    {
        $filters = [
            'level' => $_GET['level'] ?? null,
            'class_name' => $_GET['class_name'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        $this->render('catalog', [
            'pageTitle' => 'Catalogue',
            'currentUser' => Auth::user(),
            'catalogBooks' => (new Book())->all($filters),
            'selectedBook' => !empty($filters['id']) ? (new Book())->find((int) $filters['id']) : null,
            'classOptions' => $this->classOptions(),
            'subjectOptions' => $this->subjectOptions(),
        ]);
    }

    public function contact()
    {
        $this->render('contact', [
            'pageTitle' => 'Contact',
            'currentUser' => Auth::user(),
        ]);
    }

    public function login()
    {
        $this->render('login', [
            'pageTitle' => 'Connexion',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function register()
    {
        $this->render('register', [
            'pageTitle' => 'Inscription',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function privacyPolicy()
    {
        $this->render('privacy-policy', [
            'pageTitle' => 'Politique de confidentialite',
            'currentUser' => Auth::user(),
        ]);
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $requests = new BookRequest();
        $bookModel = new Book();
        $notificationModel = new Notification();
        $notifications = $notificationModel->latestForUser((int) Auth::id(), 10);
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
            'notifications' => $notifications,
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

    public function addBook()
    {
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $this->render('add-book', [
            'pageTitle' => 'Ajouter un livre',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
            'classOptions' => $this->classOptions(),
            'subjectOptions' => $this->subjectOptions(),
        ]);
    }

    public function admin()
    {
        if (!Auth::isAdmin()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/dashboard');
            exit;
        }

        $bookModel = new Book();
        $requestModel = new BookRequest();
        $userModel = new User();
        $acceptedCount = $requestModel->countAccepted();
        $userSearch = isset($_GET['user_search']) ? trim($_GET['user_search']) : '';
        $requestStatus = isset($_GET['request_status']) ? trim($_GET['request_status']) : '';

        $this->render('admin', [
            'pageTitle' => 'Administration',
            'currentUser' => Auth::user(),
            'adminStats' => [
                'totalUsers' => $userModel->countAll(),
                'totalBooks' => $bookModel->countActive(),
                'totalExchanges' => $acceptedCount,
                'moneySaved' => $requestModel->sumAcceptedValueGlobal(),
                'inactiveBooks' => $bookModel->countInactive(),
                'inactiveUsers' => $userModel->countInactive(),
                'booksByLevel' => $bookModel->countByLevel(),
            ],
            'adminBooks' => $bookModel->adminAll(),
            'adminUsers' => $userModel->all($userSearch),
            'notifyUsers' => $userModel->all(),
            'adminRequests' => $requestModel->allForAdmin($requestStatus),
            'adminRequestedSubjects' => $bookModel->mostRequestedSubjects(5),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    private function pullFlash($key)
    {
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }

        unset($_SESSION[$key]);

        return is_string($value) ? $value : null;
    }

    private function classOptions()
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

    private function subjectOptions()
    {
        return [
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
    }
}
