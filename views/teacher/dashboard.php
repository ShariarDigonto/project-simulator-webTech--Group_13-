<?php
ob_start();
?>
<h3>Your Groups</h3>
<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>Group</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($groups)): ?>
            <tr><td colspan="3" class="text-center">No groups yet. Create your first group.</td></tr>
        <?php else: ?>
            <?php foreach ($groups as $g): ?>
                <tr>
                    <td><?php echo htmlspecialchars($g['name']); ?></td>
                    <td><?php echo htmlspecialchars($g['created_at']); ?></td>
                    <td>
                        <a href="index.php?controller=TeacherController&action=groupManage&id=<?php echo $g['id']; ?>">Manage</a>
                        |
                        <a href="index.php?controller=TeacherController&action=groupChat&id=<?php echo $g['id']; ?>">Group Chat</a>
                        |
                        <a href="index.php?controller=TeacherController&action=assignments&group_id=<?php echo $g['id']; ?>">Assignments</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<h3>Your Chats</h3>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

