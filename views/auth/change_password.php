<?php
ob_start();
?>
<form class="auth-form" method="post" action="index.php?action=changePassword">
    <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" minlength="6" required>
        <span class="muted">Minimum 6 characters</span>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
    </div>
  
    <button type="submit" class="btn btn-primary">Change Password</button>
    <p class="auth-switch">
        <a href="index.php?controller=<?php echo htmlspecialchars($_SESSION['user_role'] === 'teacher' ? 'TeacherController' : 'StudentController'); ?>&action=dashboard">Back to Dashboard</a>
    </p>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
