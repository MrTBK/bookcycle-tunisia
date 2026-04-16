<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Notification
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $userId, string $message): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO notifications (user_id, message, is_read) VALUES (:user_id, :message, :is_read)'
        );
        $statement->execute([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => 0,
        ]);
    }
}

