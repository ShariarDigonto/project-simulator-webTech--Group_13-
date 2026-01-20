<?php
ob_start();
?>
<form class="auth-form" method="post" action="index.php?action=register" onsubmit="return validateRegisterForm();">
    <div class="form-row">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="6" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
        </div>
    </div>
    <div class="form-group">
        <label for="role">Register as</label>
        <select id="role" name="role" required>
            <option value="">Select role</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create Account</button>
    <p class="auth-switch">
        Already have an account?
        <a href="index.php?action=login">Login</a>
    </p>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

