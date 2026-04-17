<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function register()
    {
        $payload = $this->requestData();

        if (empty($payload['name']) || empty($payload['email']) || empty($payload['password']) || empty($payload['phone'])) {
            $this->respondError('Tous les champs sont obligatoires.', '/register', 422);
            return;
        }

        if ($this->users->findByEmail($payload['email'])) {
            $this->respondError('Cet email existe deja.', '/register', 422);
            return;
        }

        $id = $this->users->create($payload);

        if ($this->isApiRequest()) {
            $this->json(['success' => true, 'userId' => $id]);
            return;
        }

        $_SESSION['flash_success'] = 'Inscription reussie. Vous pouvez maintenant vous connecter.';
        $this->redirect('/login');
    }

    public function login()
    {
        $payload = $this->requestData();
        $email = '';

        if (isset($payload['email'])) {
            $email = $payload['email'];
        }

        $user = $this->users->findByEmail($email);

        $password = '';
        if (isset($payload['password'])) {
            $password = (string) $payload['password'];
        }

        if (!$user || !password_verify($password, $user['password'])) {
            $this->respondError('Email ou mot de passe invalide.', '/login', 401);
            return;
        }

        if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $this->respondError('Votre compte est desactive. Contactez l administrateur.', '/login', 403);
            return;
        }

        Auth::login($user);

        if ($this->isApiRequest()) {
            $this->json([
                'success' => true,
                'user' => Auth::user(),
            ]);
            return;
        }

        $this->redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $this->redirect('/');
    }

    public function me()
    {
        $this->json([
            'loggedIn' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }

    private function requestData()
    {
        $raw = file_get_contents('php://input');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return $_POST;
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
