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

     public function reject()
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            User::updateStatus($id, 'rejected');
        }
        header('Location: index.php?controller=AdminController&action=pending');
        exit;
    }
      public function delete()
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            User::deleteById($id);
        }
        header('Location: index.php?controller=AdminController&action=users');
        exit;
    }
     public function edit()
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $error = '';

         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $status = trim($_POST['status'] ?? '');
             if ($name === '' || $email === '' || $role === '' || $status === '') {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif (!in_array($role, ['teacher', 'student', 'admin'], true)) {
                $error = 'Invalid role.';
            } elseif (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
                $error = 'Invalid status.';
            } else {
                User::updateUser($id, $name, $email, $role, $status);
                header('Location: index.php?controller=AdminController&action=users');
                exit;
            }
        }
 $user = User::findById($id);
        $pageTitle = 'Edit User';
        require __DIR__ . '/../views/admin/user_form.php';
    }
 public function add()
    {
        $this->requireAdmin();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? '');
        if ($name === '' || $email === '' || $password === '' || $role === '') {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif (!in_array($role, ['teacher', 'student'], true)) {
                $error = 'Role must be Teacher or Student.';
            } else {
                User::create($name, $email, $password, $role, 'approved');
                header('Location: index.php?controller=AdminController&action=users');
                exit;
            }
        }
