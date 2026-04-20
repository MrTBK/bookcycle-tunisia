<?php

namespace App\Models;

use App\Core\Database;
use PDO;

// This model stores the small in-app messages users receive.
// These messages are the notifications shown in the navbar and dashboard.
class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create($userId, $message, $senderName = 'Systeme')
    {
        // Create one notification for one user.
        $statement = $this->db->prepare(
            'INSERT INTO notifications (user_id, sender_name, message, is_read) VALUES (:user_id, :sender_name, :message, :is_read)'
        );
        $statement->execute([
            'user_id' => $userId,
            'sender_name' => $senderName,
            'message' => $message,
            'is_read' => 0,
        ]);
    }

    public function createForAll($message, $senderName = 'Administration')
    {
        // Use one INSERT...SELECT so a broadcast notification can be created in a single query.
        $statement = $this->db->prepare(
            'INSERT INTO notifications (user_id, sender_name, message, is_read)
             SELECT id, :sender_name, :message, 0
             FROM users
             WHERE NVL(is_active, 1) = 1'
        );
        $statement->execute([
            'sender_name' => $senderName,
            'message' => $message,
        ]);
    }

    public function unreadCountForUser($userId)
    {
        // Count only the notifications the user has not opened yet.
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM notifications
             WHERE user_id = :user_id
               AND is_read = 0'
        );
        $statement->execute([
            'user_id' => (int) $userId,
        ]);

        return (int) $statement->fetchColumn();
    }

    public function latestForUser($userId, $limit = 10)
    {
        // Sort by creation date and id to keep ordering stable when timestamps are close.
        $safeLimit = max(1, (int) $limit);
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT id, sender_name, message, is_read, created_at
                FROM notifications
                WHERE user_id = :user_id
                ORDER BY created_at DESC, id DESC
             ) WHERE ROWNUM <= ' . $safeLimit
        );
        $statement->execute([
            'user_id' => (int) $userId,
        ]);

        return $statement->fetchAll();
    }

    public function unreadLatestForUser($userId, $limit = 5)
    {
        // The navbar dropdown only needs unread notifications, not the full history.
        $safeLimit = max(1, (int) $limit);
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT id, sender_name, message, is_read, created_at
                FROM notifications
                WHERE user_id = :user_id
                  AND is_read = 0
                ORDER BY created_at DESC, id DESC
             ) WHERE ROWNUM <= ' . $safeLimit
        );
        $statement->execute([
            'user_id' => (int) $userId,
        ]);

        return $statement->fetchAll();
    }

    public function findForUser($notificationId, $userId)
    {
        // Find one notification, but only if it belongs to the current user.
        $statement = $this->db->prepare(
            'SELECT * FROM (
                SELECT id, user_id, sender_name, message, is_read, created_at
                FROM notifications
                WHERE id = :id
                  AND user_id = :user_id
             ) WHERE ROWNUM = 1'
        );
        $statement->execute([
            'id' => (int) $notificationId,
            'user_id' => (int) $userId,
        ]);

        $notification = $statement->fetch();

        return $notification ?: null;
    }

    public function markAsRead($notificationId, $userId)
    {
        // Update only unread rows to avoid useless writes when a notification is opened twice.
        $statement = $this->db->prepare(
            'UPDATE notifications
             SET is_read = 1
             WHERE id = :id
               AND user_id = :user_id
               AND is_read = 0'
        );
        $statement->execute([
            'id' => (int) $notificationId,
            'user_id' => (int) $userId,
        ]);
    }
}
