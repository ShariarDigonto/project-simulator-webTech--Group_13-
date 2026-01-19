<?php
ob_start();
?>
<p><strong>Assignment:</strong> <?php echo htmlspecialchars($assignment['title']); ?></p>
<?php if (!empty($assignment['description'])): ?>
    <p class="muted"><?php echo htmlspecialchars($assignment['description']); ?></p>
<?php endif; ?>
<p><strong>Due:</strong> <?php echo htmlspecialchars($assignment['due_at'] ?? '-'); ?> | <strong>Allow Late:</strong> <?php echo (int)$assignment['allow_late'] === 1 ? 'Yes' : 'No'; ?></p>

<?php if (!empty($existing)): ?>
    <p class="alert alert-success">You already submitted at <?php echo htmlspecialchars($existing['submitted_at']); ?>. You can resubmit to replace.</p>
<?php endif; ?>
<form class="auth-form" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="submission_file">Upload File</label>
        <input type="file" id="submission_file" name="submission_file" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

