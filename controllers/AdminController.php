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
    public function users()
    {
        $this->requireAdmin();

        $pageTitle = 'All Users';
        $users = User::all();
        require __DIR__ . '/../views/admin/users.php';
    }
     
    public function students()
    {
        $this->requireAdmin();

        $pageTitle = 'Student List';
        $users = User::allByRole('student');
        require __DIR__ . '/../views/admin/users.php';
    }

     public function teachers()
    {
        $this->requireAdmin();

        $pageTitle = 'Teacher List';
        $users = User::allByRole('teacher');
        require __DIR__ . '/../views/admin/users.php';
    }

     public function pending()
    {
        $this->requireAdmin();

        $pageTitle = 'Pending Signups';
        $users = User::allPending();
        require __DIR__ . '/../views/admin/pending.php';
    }
     public function approve()
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            User::updateStatus($id, 'approved');
        }
        header('Location: index.php?controller=AdminController&action=pending');
        exit;
    }
