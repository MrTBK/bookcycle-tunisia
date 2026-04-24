<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;


// el controller hedha inadhem el processus "Nheb el kteb hedha". Y9ala , ywarri , ya9boul , w yerfez des demandes.
class RequestController extends Controller
{
    private $requests;
    private $books;
    private $notifications;
    private $users;

    public function __construct()
    {
        $this->requests = new BookRequest();
        $this->books = new Book();
        $this->notifications = new Notification();
        $this->users = new User();
    }

    public function store()
    {
        //ken el connected users inajem yab3th dommande 
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }
        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        //a9ra el id mta3 el book li 7ab yotlbou
        $bookId = (int) ($payload['bookId'] ?? 0);
        $userId = (int) Auth::id();
        $book = $this->books->find($bookId);

        if (!$book) {
            $this->respondError('Livre introuvable.', '/catalog', 404);
            return;
        }

        // el user ma ynajmch yotleb el kteb mt3ou
        if ((int) $book['owner_id'] === $userId) {
            $this->respondError('Vous ne pouvez pas demander votre propre livre.', '/catalog?id=' . $bookId, 422);
            return;
        }

        // na7i el duplacation mta3 el demandes en attente l nafs el user w nafs el book
        if ($this->requests->existsPending($bookId, $userId)) {
            $this->respondError('Une demande en attente existe deja.', '/catalog?id=' . $bookId, 422);
            return;
        }

        // a3mel el demande 9bal w ba3d ab3ath notification lel owner mta3 el book 
        $this->requests->create($bookId, $userId);
        $this->notifications->create(
            (int) $book['owner_id'],
            'Nouvelle demande pour le livre "' . $book['title'] . '".',
            (string) (Auth::user()['name'] ?? 'Utilisateur')
        );

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande envoyee avec succes.';
        $this->redirect('/dashboard');
    }

    public function mine()
    {
        //raja3 el demandes li ba3thom el user el connected
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->mine((int) Auth::id()));
    }

    public function received()
    {
        //raja3 el demandes li waslou lel moula lekteb 3la ktbou
        if (!Auth::check()) {
            $this->json(['success' => false, 'error' => 'Authentification requise.'], 401);
            return;
        }

        $this->json($this->requests->received((int) Auth::id()));
    }

    public function accept()
    {
        //ken el connected users inajem y9bal dommande
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        //a9ra el id mta3 el demande li 7ab y9balha w el meeting note eli 7ab y7ottha lel demandeur
        $requestId = (int) ($_GET['id'] ?? 0);
        $payload = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }
        $meetingNote = trim((string) ($payload['meetingNote'] ?? ''));
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->respondError('Demande introuvable.', '/dashboard', 404);
            return;
        }

        $book = $this->books->find((int) $request['book_id']);
        if (!$book || (int) $book['owner_id'] !== (int) Auth::id()) {
            $this->respondError('Action interdite.', '/dashboard', 403);
            return;
        }

        if ($meetingNote === '') {
            $this->respondError('La note de rendez-vous est obligatoire.', '/dashboard', 422);
            return;
        }

        // accept el demande hedhi w ina7i el demandes l okhra l nafs el kteb ba3d update status mta3 el book lel reserved
        $this->requests->accept($requestId, (int) $request['book_id'], $meetingNote);
        $this->books->updateStatus((int) $request['book_id'], 'reserved');
        $owner = $this->users->findById((int) $book['owner_id']);
        $requester = $this->users->findById((int) $request['requester_id']);

        // ab3th notification lel demandeur w lel owner
        if ($requester && $owner) {
            $this->notifications->create(
                (int) $request['requester_id'],
                'Votre demande pour "' . $book['title'] . '" a ete acceptee. Contact du proprietaire: '
                . $owner['name'] . ' | Email: ' . $owner['email'] . ' | Telephone: ' . $owner['phone'],
                (string) $owner['name']
            );
            $this->notifications->create(
                (int) $book['owner_id'],
                'Demande acceptee pour "' . $book['title'] . '". Contact du demandeur: '
                . $requester['name'] . ' | Email: ' . $requester['email'] . ' | Telephone: ' . $requester['phone'],
                (string) $requester['name']
            );
        }

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $contactSummary = '';
        if ($requester) {
            $contactSummary = ' Contact demandeur: ' . $requester['name']
                . ' | Email: ' . $requester['email']
                . ' | Telephone: ' . $requester['phone'];
        }

        $_SESSION['flash_success'] = 'Demande acceptee avec succes.' . $contactSummary;
        $this->redirect('/dashboard');
    }

    public function reject()
    {
        // ken el connected users inajem maye9belch dommande
        if (!Auth::check()) {
            $this->respondError('Authentification requise.', '/login', 401);
            return;
        }

        //a9ra el id mta3 el demande li 7ab yorfedhha
        $requestId = (int) ($_GET['id'] ?? 0);
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->respondError('Demande introuvable.', '/dashboard', 404);
            return;
        }

        $book = $this->books->find((int) $request['book_id']);
        if (!$book || (int) $book['owner_id'] !== (int) Auth::id()) {
            $this->respondError('Action interdite.', '/dashboard', 403);
            return;
        }

        // cancel el request hedhi w ab3ath notification lel demandeur
        $this->requests->reject($requestId);
        $this->notifications->create(
            (int) $request['requester_id'],
            'Votre demande pour "' . $book['title'] . '" a ete refusee.',
            (string) (Auth::user()['name'] ?? 'Utilisateur')
        );

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $_SESSION['flash_success'] = 'Demande refusee.';
        $this->redirect('/dashboard');
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
}
