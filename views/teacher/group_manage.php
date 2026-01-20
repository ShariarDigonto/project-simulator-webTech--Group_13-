<?php
ob_start();
?>
<p><strong>Group:</strong> <?php echo htmlspecialchars($group['name']); ?></p>
<?php if (!empty($group['description'])): ?>
    <p class="muted"><?php echo htmlspecialchars($group['description']); ?></p>
<?php endif; ?>

<h3>Add Student</h3>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<form class="auth-form" method="post" action="index.php?controller=TeacherController&action=groupAddStudent&group_id=<?php echo $group['id']; ?>">
    <div class="form-group">
        <label for="student_email">Student Email</label>
        <input type="email" id="student_email" name="student_email" placeholder="student@example.com" required>
        <small class="muted">Enter the student's email address to add them to this group.</small>
    </div>
    <button type="submit" class="btn btn-primary">Add Student</button>
</form>

<h3>Members</h3>
<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($members)): ?>
            <tr><td colspan="3" class="text-center">No students added yet.</td></tr>
        <?php else: ?>
            <?php foreach ($members as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['id']); ?></td>
                    <td><?php echo htmlspecialchars($m['name']); ?></td>
                    <td><?php echo htmlspecialchars($m['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

