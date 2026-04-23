<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

// el controller hedha howa beb el users y3awen les utilisateurs y3mlou compte, yconnectiw, ydisconnectiw, w ys2lou 3la chkon ely mconnecti houni tw
class AuthController extends Controller
{
    // el variable hedha bech ykoun fih el helper li ykhdem m3a table users. yani bech y3awenna n3mlou compte, nconnectiw, w ndisconnectiw ....
    private $users;

    public function __construct()
    {
        //ebni el helper mta3i m3a table users
        $this->users = new User();
    }

    public function register()
    {
        //step 1 : lem el ma3loumet li baathhom el visitor
        $payload = $this->requestData();

        //step 2 : t2aked li el ma3loumet el mohimmin mesh ferghin
        if (empty($payload['name']) || empty($payload['email']) || empty($payload['password']) || empty($payload['phone'])) {
            $this->respondError('Tous les champs sont obligatoires.', '/register', 422);
            return;
        }

        //step 3: matkhalich 2 comptes yest3mlou nafs email. yani el email lazem ykoun unique fi database
        if ($this->users->findByEmail($payload['email'])) {
            $this->respondError('Cet email existe deja.', '/register', 422);
            return;
        }

        //step 4:  9ayed el new user fl database
        $this->users->create($payload);

        // el message hedha bech iji fi login page ken el inscription reussie
        $_SESSION['flash_success'] = 'Inscription reussie. Vous pouvez maintenant vous connecter.';
        $this->redirect('/login');
    }

    public function login()
    {
        // a9ra el data eli b3athha el user
        $payload = $this->requestData();
        $email = '';

        if (isset($payload['email'])) {
            $email = $payload['email'];
        }

        //lawej ala user bel mail hedha
        $user = $this->users->findByEmail($email);

        $password = '';
        if (isset($payload['password'])) {
            $password = (string) $payload['password'];
        }

        // thabet mel pass
        if (!$user || !password_verify($password, $user['password'])) {
            $this->respondError('Email ou mot de passe invalide.', '/login', 401);
            return;
        }

        //hata ken el pass s7i7 el disabled acc maynajmch iconnecti
        if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $this->respondError('Votre compte est desactive. Contactez l administrateur.', '/login', 403);
            return;
        }

        //9ayed el user fl session bech el app t3rf eli mconnecti w t3tih les droits mta3ou
        Auth::login($user);

        $this->redirect('/dashboard');
    }

    public function logout()
    {
        // na7i el user mel session bech ydisconnecti
        Auth::logout();

        $this->redirect('/');
    }

    private function requestData()
    {
        return $_POST;
    }

    private function respondError($message, $redirectPath, $statusCode)
    {
        // browser yestha9 flash msg
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
