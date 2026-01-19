<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : ''; ?>Student Panel</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Student Panel</h1>
            <?php if (!empty($pageTitle)): ?>
                <p class="auth-subtitle"><?php echo htmlspecialchars($pageTitle); ?></p>
            <?php endif; ?>
            </div>
        <div class="auth-body">
            <nav class="admin-nav">
                <a href="index.php?controller=StudentController&action=dashboard">Dashboard</a>
                <a href="index.php?controller=StudentController&action=search">Search Users</a>
                <a href="index.php?action=changePassword">Change Password</a>
                <a href="index.php?action=logout">Logout</a>

            </nav>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php echo $content ?? ''; ?>
        </div>
       
    </div>
</div>
<script src="public/js/app.js"></script>
</body>
</html>