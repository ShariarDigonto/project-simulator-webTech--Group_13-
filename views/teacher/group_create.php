<?php
ob_start();
?>
<form class="auth-form" method="post">
    <div class="form-group">
        <label for="name">Group Name</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Description (optional)</label>
        <input type="text" id="description" name="description">
    </div>
    <button type="submit" class="btn btn-primary">Create Group</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
