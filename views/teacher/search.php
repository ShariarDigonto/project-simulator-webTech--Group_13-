<?php
ob_start();
?>
<form class="auth-form" method="get" action="index.php">
    <input type="hidden" name="controller" value="TeacherController">
    <input type="hidden" name="action" value="search">
    <div class="form-group">
        <label for="q">Search by name or email</label>
        <input type="text" id="q" name="q" value="<?php echo htmlspecialchars($q); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>

<?php if ($q !== ''): ?>
    <h3>Results</h3>
    <div class="table-wrapper">
        <table class="simple-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($results)): ?>
                <tr><td colspan="4" class="text-center">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($results as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
                        <td>
                            <a href="index.php?controller=TeacherController&action=profile&id=<?php echo $u['id']; ?>">Profile</a>
                            |
                            <a href="index.php?controller=TeacherController&action=privateChat&id=<?php echo $u['id']; ?>">Chat</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

