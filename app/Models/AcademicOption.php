<?php
namespace App\Models;

use App\Core\Database;

class AcademicOption
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function subjects()
    {
        $statement = $this->db->prepare("SELECT name FROM subjects WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
        $statement->execute();

        return array_map(static function ($row) {
            return (string) $row['name'];
        }, $statement->fetchAll());
    }

    public function levels()
    {
        $sql = "SELECT DISTINCT school_level FROM school_classes WHERE is_active = 1 ORDER BY CASE school_level WHEN 'Primaire' THEN 1 WHEN 'College' THEN 2 WHEN 'Lycee' THEN 3 ELSE 99 END";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return array_map(static function ($row) {
            return (string) $row['school_level'];
        }, $statement->fetchAll());
    }

    public function classesByLevel()
    {
        $sql = "SELECT school_level, class_name FROM school_classes WHERE is_active = 1 ORDER BY CASE school_level WHEN 'Primaire' THEN 1 WHEN 'College' THEN 2 WHEN 'Lycee' THEN 3 ELSE 99 END, sort_order ASC, class_name ASC";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        $grouped = [];
        foreach ($statement->fetchAll() as $row) {
            $level = (string) $row['school_level'];
            if (!isset($grouped[$level])) {
                $grouped[$level] = [];
            }

            $grouped[$level][] = (string) $row['class_name'];
        }

        return $grouped;
    }

    public function hasSubject($subject)
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM subjects WHERE is_active = 1 AND name = :name");
        $statement->execute(['name' => trim((string) $subject)]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function hasClassForLevel($level, $className)
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM school_classes WHERE is_active = 1 AND school_level = :school_level AND class_name = :class_name");
        $statement->execute([
            'school_level' => trim((string) $level),
            'class_name' => trim((string) $className),
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}