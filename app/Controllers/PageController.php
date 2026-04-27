<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\AcademicOption;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

class PageController extends Controller
{
    private $academicOptions;

    public function __construct()
    {
        $this->academicOptions = new AcademicOption();
    }

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
        $classOptions = $this->academicOptions->classesByLevel();

        $this->render('catalog', [
            'pageTitle' => 'Catalogue',
            'currentUser' => Auth::user(),
            'catalogBooks' => (new Book())->all($filters),
            'selectedBook' => !empty($filters['id']) ? (new Book())->find((int) $filters['id']) : null,
            'levelOptions' => $this->academicOptions->levels(),
            'classOptions' => $classOptions,
            'subjectOptions' => $this->academicOptions->subjects($filters['level'] ?? null, $filters['class_name'] ?? null),
            'allSubjectOptions' => $this->academicOptions->subjects(),
            'subjectOptionsByClass' => $this->academicOptions->classSubjectsByLevel(),
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

        $levelOptions = $this->academicOptions->levels();
        $classOptions = $this->academicOptions->classesByLevel();
        $defaultLevel = isset($levelOptions[0]) ? $levelOptions[0] : null;
        $defaultClass = ($defaultLevel !== null && isset($classOptions[$defaultLevel][0])) ? $classOptions[$defaultLevel][0] : null;

        $this->render('add-book', [
            'pageTitle' => 'Ajouter un livre',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
            'levelOptions' => $levelOptions,
            'classOptions' => $classOptions,
            'subjectOptions' => $this->academicOptions->subjects($defaultLevel, $defaultClass),
            'allSubjectOptions' => $this->academicOptions->subjects(),
            'subjectOptionsByClass' => $this->academicOptions->classSubjectsByLevel(),
        ]);
    }

    /**
     * Afficher le formulaire de modification d'un livre.
     * L'identifiant du livre est lu depuis le parametre GET ?id=X.
     * La page n'est accessible qu'au proprietaire du livre.
     */
    public function editBook()
    {
        // Rediriger vers la connexion si l'utilisateur n'est pas authentifie.
        if (!Auth::check()) {
            $basePath = isset($_SERVER['APP_BASE_PATH']) ? $_SERVER['APP_BASE_PATH'] : '';
            header('Location: ' . $basePath . '/login');
            exit;
        }

        // Lire l'identifiant du livre depuis l'URL (?id=X).
        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $bookModel = new Book();
        $book = $bookModel->find($bookId);

        // Verifier que le livre existe et appartient a l'utilisateur connecte.
        if (!$book || (int) $book['owner_id'] !== (int) Auth::id()) {
            $basePath = isset($_SERVER['APP_BASE_PATH']) ? $_SERVER['APP_BASE_PATH'] : '';
            header('Location: ' . $basePath . '/dashboard');
            exit;
        }

        // Afficher la page avec les donnees actuelles du livre pre-remplies.
        $this->render('edit-book', [
            'pageTitle'    => 'Modifier le livre',
            'currentUser'  => Auth::user(),
            'book'         => $book,
            'flashError'   => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
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
}
