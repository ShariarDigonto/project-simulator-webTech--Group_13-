<?php

class Message
{
    public static function sendGroup($groupId, $senderId, $body)
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO messages (group_id, sender_id, body) VALUES (?, ?, ?)');
        return $stmt->execute([$groupId, $senderId, $body]);
    }

    public static function historyGroup($groupId, $limit = 100)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT m.*, u.name AS sender_name
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            WHERE m.group_id = ? AND m.receiver_id IS NULL
            ORDER BY m.created_at ASC
            LIMIT $limit
        ");
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }

    public static function sendPrivate($senderId, $receiverId, $body)
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO messages (sender_id, receiver_id, body) VALUES (?, ?, ?)');
        return $stmt->execute([$senderId, $receiverId, $body]);
    }

    public static function historyPrivate($userA, $userB, $limit = 200)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT m.*, u.name AS sender_name
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            WHERE m.group_id IS NULL
              AND (
                (m.sender_id = ? AND m.receiver_id = ?)
                OR
                (m.sender_id = ? AND m.receiver_id = ?)
              )
            ORDER BY m.created_at ASC
            LIMIT $limit
        ");
        $stmt->execute([$userA, $userB, $userB, $userA]);
        return $stmt->fetchAll();
    }

    public static function privateThreadsForUser($userId, $limit = 30)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT
                CASE
                    WHEN m.sender_id = :uid THEN m.receiver_id
                    ELSE m.sender_id
                END AS other_id,
                u.name AS other_name,
                u.email AS other_email,
                u.role AS other_role,
                MAX(m.created_at) AS last_message_at
            FROM messages m
            JOIN users u
              ON u.id = CASE
                           WHEN m.sender_id = :uid THEN m.receiver_id
                           ELSE m.sender_id
                        END
            WHERE m.group_id IS NULL
              AND (m.sender_id = :uid OR m.receiver_id = :uid)
            GROUP BY other_id, other_name, other_email, other_role
            ORDER BY last_message_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':uid', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

