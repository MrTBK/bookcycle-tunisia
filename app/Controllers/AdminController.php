<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\User;
// hedhi control room ll admin
// tkhalih yethakem fl users books msgs
class AdminController extends Controller
{
    private $users;
    private $books;
    private $requests;
    private $notifications;

    public function __construct()
    {
        // Build all helper objects once so each admin action can reuse them.
        $this->users = new User();
        $this->books = new Book();
        $this->requests = new BookRequest();
        $this->notifications = new Notification();
    }

    public function stats()
    {
        // ken el admin ira el nwemer
        if (!Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Acces administrateur requis.'], 403);
            return;
        }

        // traja3ek el dashbored mta3 el admin w t3tik les stats mta3 el platform
        $this->json([
            'totalUsers' => $this->users->countAll(),
            'totalBooks' => $this->books->countActive(),
            'totalExchanges' => $this->requests->countAccepted(),
            'inactiveBooks' => $this->books->countInactive(),
            'booksByLevel' => $this->books->countByLevel(),
            'mostRequestedSubjects' => $this->books->mostRequestedSubjects(5),
        ]);
    }

    public function toggleUser()
    {
        //thabet kenou admin
        if (!$this->checkAdminAccess()) {
            return;
        }

        //a9ra el id mta3 el user li hab yed5el
        $userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $targetUser = $this->users->findById($userId);

        //thabet ken el user mawjoud, ken mawjoudch raja3 error
        if (!$targetUser) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        //ma n5alouch el admin y3ml disable lel compte mta3ou belghalet, 7ata ken howa admin
        if ((int) $targetUser['id'] === (int) Auth::id()) {
            $this->setFlashAndRedirect('flash_error', 'Vous ne pouvez pas desactiver votre propre compte.', '/admin');
        }

        //ne3mlou toggle lel status mta3 el user, ken howa active ywalli desactive w ken howa desactive ywalli active
        $isActive = isset($targetUser['is_active']) ? (int) $targetUser['is_active'] : 1;
        $this->users->setActive($userId, $isActive === 0);

        if ($isActive === 0) {
            $this->setFlashAndRedirect('flash_success', 'Utilisateur reactive avec succes.', '/admin');
        }

        $this->setFlashAndRedirect('flash_success', 'Utilisateur desactive avec succes.', '/admin');
    }

    public function deleteBook()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        //a9ra id el kteb w thabet ken el kteb mawjoud, ken mch mawjoud raja3 error
        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $book = $this->books->find($bookId);

        if (!$book) {
            $this->setFlashAndRedirect('flash_error', 'Livre introuvable.', '/admin');
        }

        //delete lena ma3neha hide ml platform, mch erase ml database
        $this->books->deactivate($bookId);
        $this->setFlashAndRedirect('flash_success', 'Livre masque avec succes.', '/admin');
    }

    public function restoreBook()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        $bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $book = $this->books->find($bookId);

        //arja3 ll dataset mta3 admin khater el kteb mhidi maybanch fl finder mta3 el public
        if (!$book) {
            $allBooks = $this->books->adminAll();
            $book = null;
            foreach ($allBooks as $bookRow) {
                if ((int) $bookRow['id'] === $bookId) {
                    $book = $bookRow;
                    break;
                }
            }
        }

        if (!$book) {
            $this->setFlashAndRedirect('flash_error', 'Livre introuvable.', '/admin');
        }

        // Ken el admin l9a el kteb li howa mhidi, iraj3ou public
        $this->books->reactivate($bookId);
        $this->setFlashAndRedirect('flash_success', 'Livre reactive avec succes.', '/admin');
    }

    public function cancelRequest()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        // Find the request the admin wants to stop.
        //al9a el request li hab yelgheha
        $requestId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $request = $this->requests->find($requestId);

        if (!$request) {
            $this->setFlashAndRedirect('flash_error', 'Demande introuvable.', '/admin');
        }

        //raja3 el request lel rejected wla cancelled men and el admin
        $this->requests->cancelByAdmin($requestId);

        // Ken el request accepted cancelled iraja3 el book available
        if (isset($request['status']) && $request['status'] === 'accepted' && isset($request['book_id'])) {
            $this->books->updateStatus((int) $request['book_id'], 'available');
        }

        $this->setFlashAndRedirect('flash_success', 'Demande annulee avec succes.', '/admin');
    }

    public function notify()
    {
        if (!$this->checkAdminAccess()) {
            return;
        }

        //a9ra el id mta3 el user li hab yeb3atlou w el message 
        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';

        if ($message === '') {
            $this->setFlashAndRedirect('flash_error', 'Le message est obligatoire.', '/admin');
        }

        // el user id 0 ma3neha bch yeb3ath el message lel kol les utilisateurs actifs
        if ($userId === 0) {
            $this->notifications->createForAll($message, 'Administration');
            $this->setFlashAndRedirect('flash_success', 'Notification envoyee a tous les utilisateurs actifs.', '/admin');
        }

        // ken el admin hab yeb3ath message l user wa7ed, thabet ken el user mawjoud
        $user = $this->users->findById($userId);
        if (!$user) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
        }

        $this->notifications->create($userId, $message, 'Administration');
        $this->setFlashAndRedirect('flash_success', 'Notification envoyee avec succes.', '/admin');
    }

    /**
     * Supprimer definitivement un utilisateur de la base de donnees (DELETE physique).
     * Cette action est irreversible. Elle n'est disponible que si l'utilisateur
     * n'a pas de livres actifs sur la plateforme.
     */
    public function permanentDeleteUser()
    {
        // Verifier que la personne connectee est bien administrateur.
        if (!$this->checkAdminAccess()) {
            return;
        }

        // Lire l'identifiant de l'utilisateur a supprimer depuis la requete POST.
        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $targetUser = $this->users->findById($userId);

        // Verifier que l'utilisateur cible existe dans la base.
        if (!$targetUser) {
            $this->setFlashAndRedirect('flash_error', 'Utilisateur introuvable.', '/admin');
            return;
        }

        // Interdire la suppression de son propre compte pour eviter de bloquer l'administration.
        if ((int) $targetUser['id'] === (int) Auth::id()) {
            $this->setFlashAndRedirect('flash_error', 'Vous ne pouvez pas supprimer votre propre compte.', '/admin');
            return;
        }

        // Interdire la suppression si l'utilisateur possede encore des livres actifs.
        // Il faut d'abord masquer ou transferer ses livres avant de supprimer le compte.
        if ($this->users->hasActiveBooks($userId)) {
            $this->setFlashAndRedirect('flash_error', 'Impossible de supprimer : cet utilisateur a encore des livres actifs. Masquez-les d\'abord.', '/admin');
            return;
        }

        // Executer la suppression physique : notifications, demandes, puis utilisateur.
        $this->users->delete($userId);

        $this->setFlashAndRedirect('flash_success', 'Utilisateur supprime definitivement.', '/admin');
    }

    private function checkAdminAccess()
    {
        //ken el user li 7ab yed5el lel admin area mch admin, iraja3ou lel dashboard w may5alouch yed5el
        if (!Auth::isAdmin()) {
            $this->redirect('/dashboard');
            return false;
        }

        return true;
    }

    private function setFlashAndRedirect($flashKey, $message, $path)
    {
        // El flash message howa message yeb9a mawjoud fel session juste lel request el jey, w ba3d ma yetaffichih yetsafakh.
        $_SESSION[$flashKey] = $message;
        $this->redirect($path);
    }

    private function redirect($path)
    {
        //a3mlou redirect lel path li hab yed5elou, w zid el base path mta3 el app 9bal ma yredirekti
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }

        header('Location: ' . $basePath . $path);
        exit;
    }
}
