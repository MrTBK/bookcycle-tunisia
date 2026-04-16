<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

final class AuthController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function register(): void
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

    public function login(): void
    {
        $payload = $this->requestData();
        $user = $this->users->findByEmail($payload['email'] ?? '');

        if (!$user || !password_verify((string) ($payload['password'] ?? ''), $user['password'])) {
            $this->respondError('Email ou mot de passe invalide.', '/login', 401);
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

    public function logout(): void
    {
        Auth::logout();

        if ($this->isApiRequest()) {
            $this->json(['success' => true]);
            return;
        }

        $this->redirect('/');
    }

    public function me(): void
    {
        $this->json([
            'loggedIn' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }

    private function requestData(): array
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

    private function isApiRequest(): bool
    {
        return str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
    }

    private function respondError(string $message, string $redirectPath, int $statusCode): void
    {
        if ($this->isApiRequest()) {
            $this->json(['success' => false, 'error' => $message], $statusCode);
            return;
        }

        $_SESSION['flash_error'] = $message;
        $this->redirect($redirectPath);
    }

    private function redirect(string $path): void
    {
        header('Location: ' . ($_SERVER['APP_BASE_PATH'] ?? '') . $path);
        exit;
    }
}
