<?php

namespace App\Models;

use App\Core\Database;
use PDO;

// This model is the helper that speaks with the users table.
// It creates users, finds users, counts users, and changes whether an account is active.
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create($data)
    {
        $statement = $this->db->prepare(
            'INSERT INTO users (name, email, password, phone, role, is_active) VALUES (:name, :email, :password, :phone, :role, :is_active)'
        );

        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            // Passwords are always stored hashed before reaching the database.
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'role' => isset($data['role']) ? $data['role'] : 'user',
            'is_active' => 1,
        ]);

        // Reload the user to recover the generated Oracle id in a simple way.
        $createdUser = $this->findByEmail($data['email']);

        if (isset($createdUser['id'])) {
            return (int) $createdUser['id'];
        }

        return 0;
    }

    public function findByEmail($email)
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

    public function findById($id)
    {
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT * FROM users WHERE id = :id
            ) WHERE ROWNUM = 1'
        );
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function countAll()
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function all($search = '')
    {
        // Search is optional and matches both name and email for the admin screen.
        $sql = 'SELECT id, name, email, phone, role, created_at, NVL(is_active, 1) AS is_active
                FROM users
                WHERE 1 = 1';
        $params = [];

        if ($search !== '') {
            $sql .= ' AND (LOWER(name) LIKE :search OR LOWER(email) LIKE :search)';
            $params['search'] = '%' . strtolower($search) . '%';
        }

        $sql .= ' ORDER BY created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function countInactive()
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users WHERE NVL(is_active, 1) = 0');
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function setActive($userId, $isActive)
    {
        $statement = $this->db->prepare(
            'UPDATE users
             SET is_active = :is_active
             WHERE id = :id'
        );

        $statement->execute([
            'is_active' => $isActive ? 1 : 0,
            'id' => (int) $userId,
        ]);
    }
}
