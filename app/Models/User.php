<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO users (name, email, password, phone, role) VALUES (:name, :email, :password, :phone, :role)'
        );

        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'role' => $data['role'] ?? 'user',
        ]);

        $createdUser = $this->findByEmail($data['email']);

        return (int) ($createdUser['id'] ?? 0);
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT * FROM users WHERE email = :email
            ) WHERE ROWNUM = 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
