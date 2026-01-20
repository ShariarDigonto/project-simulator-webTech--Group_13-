<?php
class Submission
{
    public static function upsert($assignmentId, $studentId, $filePath)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO submissions (assignment_id, student_id, file_path)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE file_path = VALUES(file_path), submitted_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$assignmentId, $studentId, $filePath]);
    }

    public static function listByAssignment($assignmentId)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT s.*, u.name AS student_name, u.email AS student_email
            FROM submissions s
            JOIN users u ON u.id = s.student_id
            WHERE s.assignment_id = ?
            ORDER BY s.submitted_at DESC
        ");
        $stmt->execute([$assignmentId]);
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM submissions WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByAssignmentAndStudent($assignmentId, $studentId)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM submissions WHERE assignment_id = ? AND student_id = ? LIMIT 1');
        $stmt->execute([$assignmentId, $studentId]);
        return $stmt->fetch();
    }
}

