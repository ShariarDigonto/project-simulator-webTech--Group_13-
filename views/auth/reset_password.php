<?php
ob_start();
?>
<form class="auth-form" method="post" action="index.php?action=resetPassword&token=<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
    <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" minlength="6" required>
        <span class="muted">Minimum 6 characters</span>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
    </div>
  
    <button type="submit" class="btn btn-primary">Reset Password</button>
    <p class="auth-switch">
        <a href="index.php?action=login">Back to Login</a>
    </p>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';