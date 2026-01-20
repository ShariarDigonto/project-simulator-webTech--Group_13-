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
         $pageTitle = 'Admin Dashboard';
        $pendingCount = count(User::allPending());
        $content = '<p>Welcome, Admin.</p>';
        $content .= '<p><a href="index.php?controller=AdminController&action=users">Manage All Users</a></p>';
        $content .= '<p><a href="index.php?controller=AdminController&action=pending">Pending Approvals (' . $pendingCount . ')</a></p>';
        $content .= '<p><a href="index.php?action=logout">Logout</a></p>';
        require __DIR__ . '/../views/admin/layout.php';
    }