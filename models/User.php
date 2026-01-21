<?php

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role; // admin, teacher, student
    public $status; // pending, approved, rejected

    public static function findByEmail($email)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        if ($data) {
            $user = new self();
            foreach ($data as $key => $value) {
                if (property_exists($user, $key)) {
                    $user->$key = $value;
                }
            }
            return $user;
        }
        return null;
    }

    public static function create($name, $email, $password, $role, $status = 'pending')
    {
        $db = getDB();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hash, $role, $status]);
        return $db->lastInsertId();
    }

    public static function findById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $user = new self();
            foreach ($data as $key => $value) {
                if (property_exists($user, $key)) {
                    $user->$key = $value;
                }
            }
            return $user;
        }
        return null;
    }

    public static function all()
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function allByRole($role)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE role = ? ORDER BY created_at DESC');
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public static function searchApprovedTeacherStudent($q, $limit = 30)
    {
        $db = getDB();
        $q = '%' . $q . '%';
        $stmt = $db->prepare("
            SELECT id, name, email, role, status, created_at
            FROM users
            WHERE status = 'approved'
              AND role IN ('teacher', 'student')
              AND (name LIKE ? OR email LIKE ?)
            ORDER BY name ASC
            LIMIT ?
        ");
        $stmt->bindValue(1, $q, PDO::PARAM_STR);
        $stmt->bindValue(2, $q, PDO::PARAM_STR);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function allPending()
    {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM users WHERE status = 'pending' ORDER BY created_at ASC");
        return $stmt->fetchAll();
    }

    public static function updateUser($id, $name, $email, $role, $status)
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE users SET name = ?, email = ?, role = ?, status = ? WHERE id = ?');
        return $stmt->execute([$name, $email, $role, $status, $id]);
    }

    public static function updateStatus($id, $status)
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE users SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public static function deleteById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public static function updatePassword($userId, $newPassword)
    {
        $db = getDB();
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([$hash, $userId]);
    }
    public static function generatePasswordResetToken($userId)
    {
        $db = getDB();
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // First, check if columns exist by trying to update them
        try {
            $stmt = $db->prepare('UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?');
            $stmt->execute([$token, $expiresAt, $userId]);
            return $token;
        } catch (PDOException $e) {
            // If columns don't exist, return null (database needs migration)
            return null;
        }
    }

    public static function findByResetToken($token)
    {
        $db = getDB();
        try {
            // Use UTC_TIMESTAMP() or compare with PHP datetime for better timezone handling
            $stmt = $db->prepare('SELECT * FROM users WHERE password_reset_token = ? AND password_reset_expires > UTC_TIMESTAMP() LIMIT 1');
            $stmt->execute([$token]);
            $data = $stmt->fetch();
            if ($data) {
                // Double-check expiration in PHP (timezone-safe)
                $expiresAt = strtotime($data['password_reset_expires']);
                if ($expiresAt <= time()) {
                    return null; // Token has expired
                }
                
                $user = new self();
                foreach ($data as $key => $value) {
                    if (property_exists($user, $key)) {
                        $user->$key = $value;
                    }
                }
                return $user;
            }
        } catch (PDOException $e) {
            // Columns don't exist or query error
            error_log("findByResetToken error: " . $e->getMessage());
            return null;
        }
        return null;
    }

    public static function clearResetToken($userId)
    {
        $db = getDB();
        try {
            $stmt = $db->prepare('UPDATE users SET password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?');
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    
}

