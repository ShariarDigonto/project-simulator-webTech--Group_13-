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

    public function changePassword()
    {
        // Only allow teacher and student roles
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['user_role'] ?? '';
        if ($role !== 'teacher' && $role !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = trim($_POST['current_password'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                $error = 'All fields are required.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } else {
                $userId = $_SESSION['user_id'];
                $user = User::findById($userId);
                
                if (!$user) {
                    $error = 'User not found.';
                } elseif (!$user->verifyPassword($currentPassword)) {
                    $error = 'Current password is incorrect.';
                } else {
                    if (User::updatePassword($userId, $newPassword)) {
                        $success = 'Password changed successfully.';
                    } else {
                        $error = 'Failed to update password. Please try again.';
                    }
                }
            }
        }

        $pageTitle = 'Change Password';
        require __DIR__ . '/../views/auth/change_password.php';
    }

    public function forgotPassword()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if ($email === '') {
                $error = 'Please enter your email address.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } else {
                $user = User::findByEmail($email);
                
                // Only allow password reset for teacher and student roles
                if ($user && in_array($user->role, ['teacher', 'student']) && $user->status === 'approved') {
                    $token = User::generatePasswordResetToken($user->id);
                    
                    if ($token) {
                        // In a real application, you would send an email here
                        // For now, we'll just show the reset link (NOT production ready)
                        $resetLink = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . 
                                     dirname($_SERVER['PHP_SELF']) . '/index.php?action=resetPassword&token=' . $token;
                        $success = 'Password reset link generated. Use this link to reset your password:<br><a href="' . 
                                   htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a><br><br>' .
                                   '<strong>Note:</strong> In production, this link would be sent via email.';
                    } else {
                        $error = 'Failed to generate reset token. Please contact administrator.';
                    }
                } else {
                    // Don't reveal if email exists or not (security best practice)
                    $success = 'If an account exists with that email, a password reset link has been sent.';
                }
            }
        }

        $pageTitle = 'Forgot Password';
        require __DIR__ . '/../views/auth/forgot_password.php';
    }

    public function resetPassword()
    {
        $error = '';
        $success = '';
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            $error = 'Invalid or missing reset token.';
            $pageTitle = 'Reset Password';
            require __DIR__ . '/../views/auth/reset_password.php';
            return;
        }

        $user = User::findByResetToken($token);
        
        if (!$user) {
            $error = 'Invalid or expired reset token.';
            $pageTitle = 'Reset Password';
            require __DIR__ . '/../views/auth/reset_password.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if ($newPassword === '' || $confirmPassword === '') {
                $error = 'All fields are required.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } else {
                if (User::updatePassword($user->id, $newPassword)) {
                    User::clearResetToken($user->id);
                    $success = 'Password reset successfully. You can now <a href="index.php?action=login">login</a> with your new password.';
                } else {
                    $error = 'Failed to reset password. Please try again.';
                }
            }
        }

        $pageTitle = 'Reset Password';
        require __DIR__ . '/../views/auth/reset_password.php';
    }


}

