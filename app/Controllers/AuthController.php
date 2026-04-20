<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

// This controller is the "front door" for users.
// It helps people create an account, enter the app, leave the app,
// and ask the app who is connected right now.
class AuthController extends Controller
{
    // This variable will hold our helper that talks to the users table.
    private $users;

    public function __construct()
    {
        // Build the helper object once so we can reuse it in every method.
        $this->users = new User();
    }

    public function register()
    {
        // Step 1: collect the information the visitor sent.
        $payload = $this->requestData();

        // Step 2: make sure the important boxes are not empty.
        if (empty($payload['name']) || empty($payload['email']) || empty($payload['password']) || empty($payload['phone'])) {
            $this->respondError('Tous les champs sont obligatoires.', '/register', 422);
            return;
        }

        // Step 3: do not allow two different accounts with the same email.
        if ($this->users->findByEmail($payload['email'])) {
            $this->respondError('Cet email existe deja.', '/register', 422);
            return;
        }

        // Step 4: save the new person in the database.
        $id = $this->users->create($payload);

        // API clients want JSON, but browser forms want a redirect.
        if ($this->isApiRequest()) {
            $this->json(['success' => true, 'userId' => $id]);
            return;
        }

        // For normal pages, save a success message and send the user to login.
        $_SESSION['flash_success'] = 'Inscription reussie. Vous pouvez maintenant vous connecter.';
        $this->redirect('/login');
    }

    public function login()
    {
        // Read the data sent by the user.
        $payload = $this->requestData();
        $email = '';

        if (isset($payload['email'])) {
            $email = $payload['email'];
        }

        // Try to find a user with this email.
        $user = $this->users->findByEmail($email);

        $password = '';
        if (isset($payload['password'])) {
            $password = (string) $payload['password'];
        }

        // Check whether the secret password matches the saved hashed password.
        if (!$user || !password_verify($password, $user['password'])) {
            $this->respondError('Email ou mot de passe invalide.', '/login', 401);
            return;
        }

        // Even if the password is correct, a disabled account must stay outside.
        if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $this->respondError('Votre compte est desactive. Contactez l administrateur.', '/login', 403);
            return;
        }

        // Store the user in the session so the app remembers them on the next pages.
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
        // Remove the current user from the session.
        Auth::logout();

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $this->redirect('/');
    }

    public function me()
    {
        // This is a tiny helper endpoint that answers:
        // "Is someone connected? If yes, who?"
        $this->json([
            'loggedIn' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }

    private function requestData()
    {
        // Some clients send JSON instead of normal form fields,
        // so we try to read JSON first.
        $raw = file_get_contents('php://input');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // If there was no JSON body, use the classic POST form data.
        return $_POST;
    }

    private function isApiRequest()
    {
        // Simple rule:
        // if the URL contains /api/, we answer like an API endpoint.
        $requestUri = '';

        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }

        return str_contains($requestUri, '/api/');
    }

    private function respondError($message, $redirectPath, $statusCode)
    {
        // API calls need a JSON error answer.
        if ($this->isApiRequest()) {
            $this->json(['success' => false, 'error' => $message], $statusCode);
            return;
        }

        // Browser pages need a flash message and a redirect.
        $_SESSION['flash_error'] = $message;
        $this->redirect($redirectPath);
    }

    private function redirect($path)
    {
        // Add the base path before redirecting, in case the app is not at the web root.
        $basePath = '';
        if (isset($_SERVER['APP_BASE_PATH'])) {
            $basePath = $_SERVER['APP_BASE_PATH'];
        }

        header('Location: ' . $basePath . $path);
        exit;
    }
}
