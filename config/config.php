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
