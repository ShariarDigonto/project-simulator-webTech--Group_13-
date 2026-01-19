<?php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'digg1_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Start session for auth
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple function to get PDO instance
function getDB()
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

