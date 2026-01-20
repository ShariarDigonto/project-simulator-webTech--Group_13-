<?php
ob_start();
?>
<p><strong>Group:</strong> <?php echo htmlspecialchars($group['name']); ?></p>

<form class="auth-form" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="description">Description (optional)</label>
        <input type="text" id="description" name="description">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="due_at">Due At (optional)</label>
            <input type="datetime-local" id="due_at" name="due_at">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="allow_late" value="1">
                Allow Late Submission
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="assignment_file">Assignment File (optional)</label>
        <input type="file" id="assignment_file" name="assignment_file">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

