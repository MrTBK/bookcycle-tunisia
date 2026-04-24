<?php
namespace App\Models;

use App\Core\Database;
use PDOException;

class AcademicOption
{
    private $db;
    private $classSubjectMap = null;
    private $hasClassSubjectMappings = null;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function subjects($level = null, $className = null)
    {
        $allSubjects = $this->allSubjects();

        if ($level === null && $className === null) {
            return $allSubjects;
        }

        $subjectMap = $this->classSubjectsByLevel();
        $resolvedLevel = trim((string) $level);
        $resolvedClass = trim((string) $className);

        if ($resolvedLevel !== '' && $resolvedClass !== '') {
            return $subjectMap[$resolvedLevel][$resolvedClass] ?? [];
        }

        if ($resolvedLevel !== '' && isset($subjectMap[$resolvedLevel])) {
            $mergedSubjects = [];

            foreach ($subjectMap[$resolvedLevel] as $classSubjects) {
                foreach ($classSubjects as $subject) {
                    $mergedSubjects[$subject] = $subject;
                }
            }

            if (!empty($mergedSubjects)) {
                return array_values($mergedSubjects);
            }
        }

        return $allSubjects;
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

    public function classSubjectsByLevel()
    {
        if (is_array($this->classSubjectMap)) {
            return $this->classSubjectMap;
        }

        $classOptions = $this->classesByLevel();
        $allSubjects = $this->allSubjects();
        $subjectMap = [];

        foreach ($classOptions as $level => $classes) {
            if (!isset($subjectMap[$level])) {
                $subjectMap[$level] = [];
            }

            foreach ($classes as $className) {
                $subjectMap[$level][$className] = [];
            }
        }

        if (!$this->supportsClassSubjectMappings()) {
            foreach ($subjectMap as $level => $classes) {
                foreach (array_keys($classes) as $className) {
                    $subjectMap[$level][$className] = $allSubjects;
                }
            }

            $this->classSubjectMap = $subjectMap;

            return $this->classSubjectMap;
        }

        $sql = "SELECT c.school_level, c.class_name, s.name
                FROM class_subjects cs
                INNER JOIN school_classes c ON c.id = cs.class_id
                INNER JOIN subjects s ON s.id = cs.subject_id
                WHERE cs.is_active = 1
                  AND c.is_active = 1
                  AND s.is_active = 1
                ORDER BY CASE c.school_level WHEN 'Primaire' THEN 1 WHEN 'College' THEN 2 WHEN 'Lycee' THEN 3 ELSE 99 END,
                         c.sort_order ASC,
                         cs.sort_order ASC,
                         s.sort_order ASC,
                         s.name ASC";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        foreach ($statement->fetchAll() as $row) {
            $level = (string) $row['school_level'];
            $className = (string) $row['class_name'];

            if (!isset($subjectMap[$level][$className])) {
                $subjectMap[$level][$className] = [];
            }

            $subjectMap[$level][$className][] = (string) $row['name'];
        }

        $this->classSubjectMap = $subjectMap;

        return $this->classSubjectMap;
    }

    public function hasSubject($subject)
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM subjects WHERE is_active = 1 AND name = :name");
        $statement->execute(['name' => trim((string) $subject)]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function hasSubjectForClass($level, $className, $subject)
    {
        $resolvedLevel = trim((string) $level);
        $resolvedClass = trim((string) $className);
        $resolvedSubject = trim((string) $subject);

        if ($resolvedLevel === '' || $resolvedClass === '' || $resolvedSubject === '') {
            return false;
        }

        if (!$this->supportsClassSubjectMappings()) {
            return $this->hasSubject($resolvedSubject);
        }

        $statement = $this->db->prepare(
            "SELECT COUNT(*)
             FROM class_subjects cs
             INNER JOIN school_classes c ON c.id = cs.class_id
             INNER JOIN subjects s ON s.id = cs.subject_id
             WHERE cs.is_active = 1
               AND c.is_active = 1
               AND s.is_active = 1
               AND c.school_level = :school_level
               AND c.class_name = :class_name
               AND s.name = :subject_name"
        );
        $statement->execute([
            'school_level' => $resolvedLevel,
            'class_name' => $resolvedClass,
            'subject_name' => $resolvedSubject,
        ]);

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

    private function allSubjects()
    {
        $statement = $this->db->prepare("SELECT name FROM subjects WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
        $statement->execute();

        return array_map(static function ($row) {
            return (string) $row['name'];
        }, $statement->fetchAll());
    }

    private function supportsClassSubjectMappings()
    {
        if ($this->hasClassSubjectMappings !== null) {
            return $this->hasClassSubjectMappings;
        }

        try {
            $statement = $this->db->query("SELECT COUNT(*) FROM user_tables WHERE table_name = 'CLASS_SUBJECTS'");
            $this->hasClassSubjectMappings = (int) $statement->fetchColumn() > 0;
        } catch (PDOException $exception) {
            $this->hasClassSubjectMappings = false;
        }

        return $this->hasClassSubjectMappings;
    }
}
