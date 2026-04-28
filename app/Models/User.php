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
        // Activer ou desactiver un compte utilisateur sans le supprimer de la base.
        $statement = $this->db->prepare(
            'UPDATE users
             SET is_active = :is_active
             WHERE id = :id'
        );

        $statement->execute([
            'is_active' => $isActive ? 1 : 0,
            'id'        => (int) $userId,
        ]);

        // Liberer le curseur PDO apres execution.
        $statement->closeCursor();
    }

    /**
     * Supprimer physiquement un utilisateur de la base de donnees (DELETE reel).
     * Contrairement a setActive() qui fait une suppression logique,
     * cette methode efface definitivement la ligne de la table users.
     * Elle supprime d'abord les notifications de l'utilisateur pour
     * respecter les contraintes de cles etrangeres.
     */
    public function delete($userId)
    {
        // Supprimer d'abord les notifications pour respecter la contrainte FK.
        $stmt = $this->db->prepare('DELETE FROM notifications WHERE user_id = :id');
        $stmt->execute(['id' => (int) $userId]);
        $stmt->closeCursor();

        // Supprimer les demandes envoyees par cet utilisateur.
        $stmt = $this->db->prepare('DELETE FROM requests WHERE requester_id = :id');
        $stmt->execute(['id' => (int) $userId]);
        $stmt->closeCursor();

        // Supprimer l'utilisateur lui-meme de la table users.
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => (int) $userId]);
        $stmt->closeCursor();
    }

    /**
     * Verifier si un utilisateur possede encore des livres actifs.
     * Utilise avant la suppression pour eviter d'orpheliner des livres.
     */
    public function hasActiveBooks($userId)
    {
        // Compter les livres actifs de cet utilisateur.
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM books WHERE owner_id = :id AND is_active = 1'
        );
        $stmt->execute(['id' => (int) $userId]);
        $count = (int) $stmt->fetchColumn();
        $stmt->closeCursor();

        return $count > 0;
    }
}
