<?php

class AdminController
{
    private function requireAdmin()
    {
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function dashboard()
    {
        $this->requireAdmin();