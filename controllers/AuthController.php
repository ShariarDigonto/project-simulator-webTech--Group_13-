<?php

class AuthController
{
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Please fill in all fields.';
            } else {
                //  admin account fixed
                if ($email === 'admin@gmail.com' && $password === 'admin12345') {
                    $db = getDB();
                    $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
                    $stmt->execute([$email]);
                    $data = $stmt->fetch();

                    if (!$data) {
                        // Create admin user 
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $insert = $db->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)');
                        $insert->execute(['Admin', $email, $hash, 'admin', 'approved']);
                        $stmt->execute([$email]);
                        $data = $stmt->fetch();
                    }

                    if ($data) {
                        $_SESSION['user_id'] = $data['id'];
                        $_SESSION['user_role'] = $data['role'];
                        header('Location: index.php?controller=AdminController&action=dashboard');
                        exit;
                    } else {
                        $error = 'Unable to login as admin.';
                    }
                } else {
                    //  users (teacher/student)
                    $user = User::findByEmail($email);
                    if ($user && $user->verifyPassword($password)) {
                        if ($user->status !== 'approved') {
                            $error = 'Your account is not approved yet.';
                            return;
                        }
                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['user_role'] = $user->role;

                        // Redirect based on role
                        if ($user->role === 'teacher') {
                            header('Location: index.php?controller=TeacherController&action=dashboard');
                        } elseif ($user->role === 'student') {
                            header('Location: index.php?controller=StudentController&action=dashboard');
                        } else {
                            //  send any other role to login
                            session_destroy();
                            $error = 'Invalid role for this account.';
                        }
                        exit;
                    } else {
                        $error = 'Invalid email or password.';
                    }
                }
            }
        }

        $pageTitle = 'Login';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm = trim($_POST['confirm_password'] ?? '');
            $role = trim($_POST['role'] ?? '');

            if ($name === '' || $email === '' || $password === '' || $confirm === '' || $role === '') {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif (!in_array($role, ['teacher', 'student'], true)) {
                $error = 'Invalid role selected. You can only register as Teacher or Student.';
            } elseif ($email === 'admin@gmail.com') {
                $error = 'This email is reserved for the admin account.';
            } elseif (User::findByEmail($email)) {
                $error = 'Email is already registered.';
            } else {
                // New signups are pending until admin approval
                User::create($name, $email, $password, $role, 'pending');
                $success = 'Registration successful. Please wait for admin approval.';
            }
        }

        $pageTitle = 'Register';
        require __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }


}

