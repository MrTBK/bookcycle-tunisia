<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class BookRequest
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function existsPending(int $bookId, int $requesterId): bool
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*) FROM requests WHERE book_id = :book_id AND requester_id = :requester_id AND status = :status'
        );
        $statement->execute([
            'book_id' => $bookId,
            'requester_id' => $requesterId,
            'status' => 'pending',
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(int $bookId, int $requesterId): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO requests (book_id, requester_id, status) VALUES (:book_id, :requester_id, :status)'
        );
        $statement->execute([
            'book_id' => $bookId,
            'requester_id' => $requesterId,
            'status' => 'pending',
        ]);

        $statement = $this->db->prepare(
            'SELECT id
             FROM requests
             WHERE book_id = :book_id AND requester_id = :requester_id
             ORDER BY id DESC'
        );
        $statement->execute([
            'book_id' => $bookId,
            'requester_id' => $requesterId,
        ]);
        $rows = $statement->fetchAll();

        return isset($rows[0]['id']) ? (int) $rows[0]['id'] : 0;
    }

    public function mine(int $requesterId): array
    {
        $statement = $this->db->prepare(
            'SELECT r.*, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.estimated_price,
                    u.name AS owner_name, u.email AS owner_email, u.phone AS owner_phone
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             INNER JOIN users u ON u.id = b.owner_id
             WHERE r.requester_id = :requester_id
             ORDER BY r.request_date DESC'
        );
        $statement->execute(['requester_id' => $requesterId]);

        return $statement->fetchAll();
    }

    public function received(int $ownerId): array
    {
        $statement = $this->db->prepare(
            'SELECT r.*, b.title, b.subject, b.class_name AS class_label, b.school_level AS level_label, b.estimated_price, b.owner_id,
                    u.name AS requester_name, u.email AS requester_email, u.phone AS requester_phone
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             INNER JOIN users u ON u.id = r.requester_id
             WHERE b.owner_id = :owner_id AND r.status = :status
             ORDER BY r.request_date DESC'
        );
        $statement->execute([
            'owner_id' => $ownerId,
            'status' => 'pending',
        ]);

        return $statement->fetchAll();
    }

    public function find(int $requestId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT * FROM requests WHERE id = :id
            ) WHERE ROWNUM = 1'
        );
        $statement->execute(['id' => $requestId]);
        $request = $statement->fetch();

        return $request ?: null;
    }

    public function accept(int $requestId, int $bookId, string $meetingNote): void
    {
        $this->db->beginTransaction();

        $updateAccepted = $this->db->prepare(
            'UPDATE requests SET status = :status, meeting_note = :meeting_note WHERE id = :id'
        );
        $updateAccepted->execute([
            'status' => 'accepted',
            'meeting_note' => $meetingNote,
            'id' => $requestId,
        ]);

        $rejectOthers = $this->db->prepare(
            'UPDATE requests SET status = :status WHERE book_id = :book_id AND id != :request_id AND status = :pending'
        );
        $rejectOthers->execute([
            'status' => 'rejected',
            'book_id' => $bookId,
            'request_id' => $requestId,
            'pending' => 'pending',
        ]);

        $this->db->commit();
    }

    public function reject(int $requestId): void
    {
        $statement = $this->db->prepare(
            'UPDATE requests SET status = :status WHERE id = :id'
        );
        $statement->execute([
            'status' => 'rejected',
            'id' => $requestId,
        ]);
    }

    public function countAccepted(): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM requests WHERE status = :status');
        $statement->execute(['status' => 'accepted']);

        return (int) $statement->fetchColumn();
    }

    public function countAcceptedForRequester(int $requesterId): int
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM requests
             WHERE requester_id = :requester_id AND status = :status'
        );
        $statement->execute([
            'requester_id' => $requesterId,
            'status' => 'accepted',
        ]);

        return (int) $statement->fetchColumn();
    }

    public function countAcceptedForOwner(int $ownerId): int
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             WHERE b.owner_id = :owner_id AND r.status = :status'
        );
        $statement->execute([
            'owner_id' => $ownerId,
            'status' => 'accepted',
        ]);

        return (int) $statement->fetchColumn();
    }

    public function sumAcceptedValueForRequester(int $requesterId): float
    {
        $statement = $this->db->prepare(
            'SELECT NVL(SUM(b.estimated_price), 0)
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             WHERE r.requester_id = :requester_id AND r.status = :status'
        );
        $statement->execute([
            'requester_id' => $requesterId,
            'status' => 'accepted',
        ]);

        return (float) $statement->fetchColumn();
    }

    public function sumAcceptedValueForOwner(int $ownerId): float
    {
        $statement = $this->db->prepare(
            'SELECT NVL(SUM(b.estimated_price), 0)
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             WHERE b.owner_id = :owner_id AND r.status = :status'
        );
        $statement->execute([
            'owner_id' => $ownerId,
            'status' => 'accepted',
        ]);

        return (float) $statement->fetchColumn();
    }

    public function sumAcceptedValueGlobal(): float
    {
        $statement = $this->db->prepare(
            'SELECT NVL(SUM(b.estimated_price), 0)
             FROM requests r
             INNER JOIN books b ON b.id = r.book_id
             WHERE r.status = :status'
        );
        $statement->execute([
            'status' => 'accepted',
        ]);

        return (float) $statement->fetchColumn();
    }
}
