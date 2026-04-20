<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

// This controller prepares the main pages of the website.
// Its job is not to change data directly, but to gather the right information
// and send that information to the correct view.
class PageController extends Controller
{
    public function home()
    {
        // Build the models we need for the homepage numbers and featured books.
        $bookModel = new Book();
        $requestModel = new BookRequest();
        $acceptedCount = $requestModel->countAccepted();

        // Send homepage data to the home view.
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
        // This page is simple: it only needs the page title and current user.
        $this->render('about', [
            'pageTitle' => 'A propos',
            'currentUser' => Auth::user(),
        ]);
    }

    public function catalog()
    {
        // Read search filters directly from the query string so the page stays bookmarkable.
        $filters = [
            'level' => $_GET['level'] ?? null,
            'class_name' => $_GET['class_name'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        // Send the filtered catalogue, selected book, and dropdown options to the catalog page.
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
        // This page only needs standard shared data.
        $this->render('contact', [
            'pageTitle' => 'Contact',
            'currentUser' => Auth::user(),
        ]);
    }

    public function login()
    {
        // Flash messages let the page show the last success or error after a redirect.
        $this->render('login', [
            'pageTitle' => 'Connexion',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function register()
    {
        // Registration page also reads flash messages after redirects.
        $this->render('register', [
            'pageTitle' => 'Inscription',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function privacyPolicy()
    {
        // Static information page.
        $this->render('privacy-policy', [
            'pageTitle' => 'Politique de confidentialite',
            'currentUser' => Auth::user(),
        ]);
    }

    public function dashboard()
    {
        // The dashboard is private, so anonymous visitors must go to login first.
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        // Build the data needed for the connected user's dashboard.
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
        // Only connected users may open the "add book" page.
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        // Send dropdown options and flash messages to the add-book form.
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
        // Only admins may enter the admin page.
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

        // Build every dataset the admin page needs in one place before rendering the view.
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
        // Flash messages live only for one request:
        // read the value, then delete it from session.
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }

        unset($_SESSION[$key]);

        return is_string($value) ? $value : null;
    }

    private function classOptions()
    {
        // This gives the UI the allowed classes for each school level.
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
        // This gives the UI the official subject list used by the forms and filters.
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
