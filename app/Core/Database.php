<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function connection()
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require dirname(__DIR__) . '/Config/config.php';
        $db = $config['db'];

        try {
            self::$connection = new PDO($db['dsn'], $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            http_response_code(500);
            exit('Database connection failed: ' . $exception->getMessage());
        }

        return self::$connection;
    }
}
