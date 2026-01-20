<?php

class Assignment
{
    public static function create($groupId, $teacherId, $title, $description, $filePath, $dueAt, $allowLate)
    {
        $db = getDB();
        $stmt = $db->prepare('
            INSERT INTO assignments (group_id, teacher_id, title, description, file_path, due_at, allow_late)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$groupId, $teacherId, $title, $description, $filePath, $dueAt, (int)$allowLate]);
        return (int)$db->lastInsertId();
    }

    public static function listByGroup($groupId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM assignments WHERE group_id = ? ORDER BY created_at DESC');
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM assignments WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}

