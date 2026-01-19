<?php

class Auth
{
    public static function requireLogin()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }
    public static function requireRole($role)
    {
        self::requireLogin();
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {

        }
    }

    public static function userId()
    {
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    }
}

