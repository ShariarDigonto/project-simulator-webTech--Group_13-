<?php
ob_start();
?>
<form class="auth-form" method="post" action="index.php?action=login">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
  
    <button type="submit" class="btn btn-primary">Login</button>
    <p class="auth-switch">
        <a href="index.php?action=forgotPassword">Forgot Password?</a>
    </p>
    <p class="auth-switch">
        Don't have an account?
        <a href="index.php?action=register">Create one</a>
    </p>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
