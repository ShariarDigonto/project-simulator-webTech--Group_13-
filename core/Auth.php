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
