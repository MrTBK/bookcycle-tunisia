<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Book
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function latest($limit = 4)
    {
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
        return (int) $this->db->query('SELECT COUNT(*) FROM books WHERE is_active = 1')->fetchColumn();
    }

    public function countInactive()
    {
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
        $statement = $this->db->prepare(
            'SELECT school_level, COUNT(*) AS total_books
             FROM books
             WHERE is_active = 1
             GROUP BY school_level'
        );
        $statement->execute();

        $rows = $statement->fetchAll();
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
        $statement = $this->db->prepare('UPDATE books SET status = :status, updated_at = SYSDATE WHERE id = :id');
        $statement->execute([
            'status' => $status,
            'id' => $bookId,
        ]);
    }
}
