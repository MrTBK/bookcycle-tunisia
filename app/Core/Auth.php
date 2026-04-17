<?php

namespace App\Core;

class Auth
{
    public static function user()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        return null;
    }

    public static function id()
    {
        return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function isAdmin()
    {
        $user = self::user();
        $role = '';

        if (is_array($user) && isset($user['role'])) {
            $role = $user['role'];
        }

        return $role === 'admin';
    }

    public static function login($user)
    {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }
}
