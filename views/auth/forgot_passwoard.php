<?php
ob_start();
?>
<form class="auth-form" method="post" action="index.php?action=forgotPassword">
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>
        <span class="muted">Enter your registered email address</span>
    </div>
  
    <button type="submit" class="btn btn-primary">Send Reset Link</button>
    <p class="auth-switch">
        Remember your password? <a href="index.php?action=login">Login</a>
    </p>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
