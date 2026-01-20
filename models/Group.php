<?php

class Group
{
    public static function create($teacherId, $name, $description = null)
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO groups (teacher_id, name, description) VALUES (?, ?, ?)');
        $stmt->execute([$teacherId, $name, $description]);
        return (int)$db->lastInsertId();
    }

    public static function findById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM groups WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function listByTeacher($teacherId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM groups WHERE teacher_id = ? ORDER BY created_at DESC');
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }

    public static function teacherOwnsGroup($teacherId, $groupId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT id FROM groups WHERE id = ? AND teacher_id = ? LIMIT 1');
        $stmt->execute([$groupId, $teacherId]);
        return (bool)$stmt->fetch();
    }

    public static function listByStudent($studentId)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT g.*
            FROM group_members gm
            JOIN groups g ON g.id = gm.group_id
            WHERE gm.student_id = ?
            ORDER BY g.created_at DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function studentInGroup($groupId, $studentId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT id FROM group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $stmt->execute([$groupId, $studentId]);
        return (bool)$stmt->fetch();
    }

    public static function addStudent($groupId, $studentId)
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT IGNORE INTO group_members (group_id, student_id) VALUES (?, ?)');
        return $stmt->execute([$groupId, $studentId]);
    }

    public static function members($groupId)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT u.id, u.name, u.email
            FROM group_members gm
            JOIN users u ON u.id = gm.student_id
            WHERE gm.group_id = ?
            ORDER BY u.name ASC
        ");
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }

    public static function isMember($groupId, $userId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT id FROM group_members WHERE group_id = ? AND student_id = ? LIMIT 1');
        $stmt->execute([$groupId, $userId]);
        return (bool)$stmt->fetch();
    }
}

