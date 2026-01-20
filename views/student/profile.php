<?php
ob_start();
?>
<?php if (empty($user)): ?>
    <p class="muted">User not found.</p>
<?php else: ?>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user->name); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user->role)); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($user->status)); ?></p>
    <p>
        <a class="btn btn-primary" href="index.php?controller=StudentController&action=privateChat&id=<?php echo $user->id; ?>">Start Chat</a>
    </p>
<?php endif; ?>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';