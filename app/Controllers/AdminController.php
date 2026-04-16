<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

final class AdminController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function stats(): void
    {
        if (!Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Accès administrateur requis.'], 403);
            return;
        }

        $this->json([
            'totalUsers' => $this->users->countAll(),
        ]);
    }
}
