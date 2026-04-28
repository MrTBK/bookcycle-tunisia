<?php

namespace App\Models;

use App\Core\Database;
use PDO;

// This model is the helper that speaks with the books table.
// Think of it like a tiny librarian:
// it can fetch books, create books, count books, hide books, and change book status.
class Book
{
    private $db;

    public function __construct()
    {
        // Use the shared database connection for all book queries.
        $this->db = Database::connection();
    }

    public function latest($limit = 4)
    {
        // Oracle 11g uses ROWNUM for top-N queries, so we wrap the ordered result set.
        $safeLimit = max(1, (int) $limit);

        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT b.id, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.condition_label, b.estimated_price, b.description,
                       b.owner_id, b.status, b.is_active, b.created_at, b.updated_at, u.name AS owner_name
                FROM books b
                INNER JOIN users u ON u.id = b.owner_id
                WHERE b.is_active = 1 AND b.status = :status
                ORDER BY b.created_at DESC
             ) WHERE ROWNUM <= ' . $safeLimit
        );
        $statement->bindValue(':status', 'available');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function all($filters = [])
    {
        // Build the query progressively so each catalogue filter stays optional.
        $sql = 'SELECT b.id, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.condition_label, b.estimated_price, b.description,
                       b.owner_id, b.status, b.is_active, b.created_at, b.updated_at, u.name AS owner_name
                FROM books b
                INNER JOIN users u ON u.id = b.owner_id
                WHERE b.is_active = 1';
        $params = [];

        if (!empty($filters['level'])) {
            $sql .= ' AND b.school_level = :school_level_filter';
            $params['school_level_filter'] = $filters['level'];
        }

        if (!empty($filters['class_name'])) {
            $sql .= ' AND b.class_name = :class_name_filter';
            $params['class_name_filter'] = $filters['class_name'];
        }

        if (!empty($filters['subject'])) {
            $sql .= ' AND b.subject LIKE :subject';
            $params['subject'] = '%' . $filters['subject'] . '%';
        }

        if (!empty($filters['id'])) {
            $sql .= ' AND b.id = :id';
            $params['id'] = (int) $filters['id'];
        }

        $status = 'available';
        if (isset($filters['status'])) {
            $status = $filters['status'];
        }

        if ($status !== 'all') {
            $sql .= ' AND b.status = :status';
            $params['status'] = $status;
        }

        $sql .= ' ORDER BY b.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function create($data)
    {
        $statement = $this->db->prepare(
            'INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
             VALUES (:title, :subject, :class_name, :school_level, :book_condition, :estimated_price, :description, :owner_id, :status, :is_active)'
        );

        $statement->execute([
            'title' => $data['title'],
            'subject' => $data['subject'],
            'class_name' => $data['class_name'],
            'school_level' => $data['level'],
            'book_condition' => $data['condition'],
            'estimated_price' => (float) $data['estimated_price'],
            'description' => !empty($data['description']) ? $data['description'] : null,
            'owner_id' => (int) $data['owner_id'],
            'status' => 'available',
            'is_active' => 1,
        ]);

        // Read back the latest inserted book for this owner because Oracle XE here does not use RETURNING INTO.
        $statement = $this->db->prepare(
            'SELECT id
             FROM books
             WHERE owner_id = :owner_id
             ORDER BY id DESC'
        );
        $statement->execute(['owner_id' => (int) $data['owner_id']]);
        $rows = $statement->fetchAll();

        return isset($rows[0]['id']) ? (int) $rows[0]['id'] : 0;
    }

    public function mine($ownerId)
    {
        // Return only the active books owned by one person.
        $statement = $this->db->prepare(
            'SELECT id, title, subject, class_name AS class_label, school_level AS level_label, condition_label, estimated_price, description,
                    owner_id, status, is_active, created_at, updated_at
             FROM books
             WHERE owner_id = :owner_id AND is_active = 1
             ORDER BY created_at DESC'
        );
        $statement->execute(['owner_id' => $ownerId]);

        return $statement->fetchAll();
    }

    public function find($bookId)
    {
        // Limit to one row explicitly to stay compatible with Oracle's row limiting syntax.
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT b.id, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.condition_label, b.estimated_price, b.description,
                       b.owner_id, b.status, b.is_active, b.created_at, b.updated_at, u.name AS owner_name
                FROM books b
                INNER JOIN users u ON u.id = b.owner_id
                WHERE b.id = :id
             ) WHERE ROWNUM = 1'
        );
        $statement->execute(['id' => $bookId]);
        $book = $statement->fetch();

        return $book ?: null;
    }

    public function countActive()
    {
        // Count books that are still visible on the platform.
        return (int) $this->db->query('SELECT COUNT(*) FROM books WHERE is_active = 1')->fetchColumn();
    }

    public function countInactive()
    {
        // Count books hidden by moderation or admin action.
        return (int) $this->db->query('SELECT COUNT(*) FROM books WHERE is_active = 0')->fetchColumn();
    }

    public function adminAll()
    {
        $statement = $this->db->prepare(
            'SELECT b.id, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.condition_label,
                    b.estimated_price, b.description, b.owner_id, b.status, b.is_active, b.created_at, b.updated_at,
                    u.name AS owner_name, u.email AS owner_email
             FROM books b
             INNER JOIN users u ON u.id = b.owner_id
             ORDER BY b.created_at DESC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function deactivate($bookId)
    {
        $statement = $this->db->prepare(
            'UPDATE books
             SET is_active = 0,
                 updated_at = SYSDATE
             WHERE id = :id'
        );
        $statement->execute(['id' => (int) $bookId]);
    }

    public function reactivate($bookId)
    {
        $statement = $this->db->prepare(
            'UPDATE books
             SET is_active = 1,
                 updated_at = SYSDATE
             WHERE id = :id'
        );
        $statement->execute(['id' => (int) $bookId]);
    }

    public function countByLevel()
    {
        // This tells the admin how many active books belong to each school level.
        $statement = $this->db->prepare(
            'SELECT school_level, COUNT(*) AS total_books
             FROM books
             WHERE is_active = 1
             GROUP BY school_level'
        );
        $statement->execute();

        $rows = $statement->fetchAll();

        // Initialize every known school level so the admin dashboard always receives stable keys.
        $counts = [
            'Primaire' => 0,
            'College' => 0,
            'Lycee' => 0,
        ];

        foreach ($rows as $row) {
            if (isset($counts[$row['school_level']])) {
                $counts[$row['school_level']] = (int) $row['total_books'];
            }
        }

        return $counts;
    }

    public function mostRequestedSubjects($limit = 5)
    {
        // Join books to requests so the admin can see which subjects attract the most demand.
        $safeLimit = max(1, (int) $limit);
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT b.subject, COUNT(r.id) AS total_requests
                FROM books b
                LEFT JOIN requests r ON r.book_id = b.id
                GROUP BY b.subject
                ORDER BY COUNT(r.id) DESC, b.subject ASC
             ) WHERE ROWNUM <= ' . $safeLimit
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    public function sumEstimatedPricesForOwner($ownerId)
    {
        $statement = $this->db->prepare(
            'SELECT NVL(SUM(estimated_price), 0)
             FROM books
             WHERE owner_id = :owner_id AND is_active = 1'
        );
        $statement->execute(['owner_id' => $ownerId]);

        return (float) $statement->fetchColumn();
    }

    public function updateStatus($bookId, $status)
    {
        // Mettre a jour uniquement le statut d'un livre (available, reserved, exchanged).
        $statement = $this->db->prepare('UPDATE books SET status = :status, updated_at = SYSDATE WHERE id = :id');
        $statement->execute([
            'status' => $status,
            'id'     => $bookId,
        ]);
    }

    /**
     * Modifier les informations d'un livre appartenant a l'utilisateur connecte.
     * La condition owner_id = :owner_id garantit qu'un utilisateur ne peut
     * modifier que ses propres livres et non ceux d'un autre utilisateur.
     */
    public function update($bookId, $ownerId, $data)
    {
        // Requete UPDATE avec parametres prepares pour eviter les injections SQL.
        $statement = $this->db->prepare(
            'UPDATE books
             SET condition_label   = :condition_label,
                 estimated_price   = :estimated_price,
                 description       = :description,
                 updated_at        = SYSDATE
             WHERE id       = :id
               AND owner_id = :owner_id'
        );

        // Executer la requete avec les nouvelles valeurs fournies par le formulaire.
        $statement->execute([
            'condition_label' => $data['condition'],
            'estimated_price' => (float) $data['estimated_price'],
            'description'     => !empty($data['description']) ? $data['description'] : null,
            'id'              => (int) $bookId,
            'owner_id'        => (int) $ownerId,
        ]);

        // Liberer le curseur PDO apres execution pour liberer les ressources.
        $statement->closeCursor();
    }
}
