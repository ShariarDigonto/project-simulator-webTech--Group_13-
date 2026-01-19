<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : ''; ?>Admin Panel</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Admin Panel</h1>
            <?php if (!empty($pageTitle)): ?>
                <p class="auth-subtitle"><?php echo htmlspecialchars($pageTitle); ?></p>
            <?php endif; ?>
        </div>