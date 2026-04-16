<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Book
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function latest(int $limit = 4): array
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

    public function all(array $filters = []): array
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

        if (($filters['status'] ?? '') !== 'all') {
            $sql .= ' AND b.status = :status';
            $params['status'] = $filters['status'] ?? 'available';
        }

        $sql .= ' ORDER BY b.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function create(array $data): int
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
            'description' => $data['description'] ?: null,
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

    public function mine(int $ownerId): array
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

    public function find(int $bookId): ?array
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

    public function countActive(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM books WHERE is_active = 1')->fetchColumn();
    }

    public function sumEstimatedPricesForOwner(int $ownerId): float
    {
        $statement = $this->db->prepare(
            'SELECT NVL(SUM(estimated_price), 0)
             FROM books
             WHERE owner_id = :owner_id AND is_active = 1'
        );
        $statement->execute(['owner_id' => $ownerId]);

        return (float) $statement->fetchColumn();
    }

    public function updateStatus(int $bookId, string $status): void
    {
        $statement = $this->db->prepare('UPDATE books SET status = :status, updated_at = SYSDATE WHERE id = :id');
        $statement->execute([
            'status' => $status,
            'id' => $bookId,
        ]);
    }
}
