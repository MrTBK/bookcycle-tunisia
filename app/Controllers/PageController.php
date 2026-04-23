<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;

// el conroller hedha howa eli i7adher el main pages
// khedhemtou yjib les données eli test7a9ha les pages w b3d yeb3athha l view
class PageController extends Controller
{
    public function home()
    {
        //yebni el homepage data 
        $bookModel = new Book();
        $requestModel = new BookRequest();
        $acceptedCount = $requestModel->countAccepted();

        // yab3th el data ll home view
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
        //page hedhi simple test7a9 ken ek page title w current user
        $this->render('about', [
            'pageTitle' => 'A propos',
            'currentUser' => Auth::user(),
        ]);
    }

    public function catalog()
    {
        //ya9ra les filters men query string w yeb3athhom l book model bch yjib les ktob eli 3la les filters
        $filters = [
            'level' => $_GET['level'] ?? null,
            'class_name' => $_GET['class_name'] ?? null,
            'subject' => $_GET['subject'] ?? null,
            'status' => $_GET['status'] ?? null,
            'id' => $_GET['id'] ?? null,
        ];

        //yab3th les données eli test7a9ha el catalog view 
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
        // testha9 ken el page title w current user
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
        //ta9ra les données eli b3aththom el auth controller bch taffichi les messages error wela success
        $this->render('register', [
            'pageTitle' => 'Inscription',
            'currentUser' => Auth::user(),
            'flashError' => $this->pullFlash('flash_error'),
            'flashSuccess' => $this->pullFlash('flash_success'),
        ]);
    }

    public function privacyPolicy()
    {
        // testha9 ken el page title w current user
        $this->render('privacy-policy', [
            'pageTitle' => 'Politique de confidentialite',
            'currentUser' => Auth::user(),
        ]);
    }

    public function dashboard()
    {
        // dashboard page test7a9 user ykoun connected, q ken le yeb3athou l login page
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        //tebni el data eli test7a9ha el dashboard page w b3d teb3athha l view bch taffichiha
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
        //ken el connected user inajem yodkhel ll add book page
        if (!Auth::check()) {
            $basePath = '';
            if (isset($_SERVER['APP_BASE_PATH'])) {
                $basePath = $_SERVER['APP_BASE_PATH'];
            }

            header('Location: ' . $basePath . '/login');
            exit;
        }

        //ab3th dropdown options w flash messages l add book view 
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
        //ken el admin yodkhel ll admin page
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

        // ebni el data eli test7a9ha el admin page w b3d teb3athha l view bch taffichiha
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
        // flash messages live ken lil request el jey w ba3d ma yeb3athom l view yetfasekh
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }

        unset($_SESSION[$key]);

        return is_string($value) ? $value : null;
    }

    private function classOptions()
    {
        // hedhi les class options eli test7a9ha les forms w les filters, w 3la 7asb el level eli y5tarou l user, el class options yetbadel
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
                '2eme informatique',
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
        //hehdi les subject options eli test7a9ha les forms w les filters, w 3la 7asb el level eli y5tarou l user, el subject options yetbadel
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
