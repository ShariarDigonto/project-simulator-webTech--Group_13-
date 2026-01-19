<?php
ob_start();
?>
<h3>Your Groups</h3>
<div class="table-wrapper">
    <table class="simple-table">
        <thead>
            
        <tr>
            <th>Group</th>
            <th>Teacher ID</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($groups)): ?>
            <tr><td colspan="3" class="text-center">No groups assigned yet.</td></tr>
        <?php else: ?>
            <?php foreach ($groups as $g): ?>
                <tr>
                    <td><?php echo htmlspecialchars($g['name']); ?></td>
                    <td><?php echo htmlspecialchars($g['teacher_id']); ?></td>
                    <td>
                        <a href="index.php?controller=StudentController&action=groupChat&id=<?php echo $g['id']; ?>">Group Chat</a>
                        |
                        <a href="index.php?controller=StudentController&action=assignments&group_id=<?php echo $g['id']; ?>">Assignments</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<h3>Your Chats</h3>
<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>With</th>
            <th>Email</th>
            <th>Role</th>
            <th>Last Message</th>
            <th>Open</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($privateThreads)): ?>
            <tr><td colspan="5" class="text-center">No private chats yet. Start one from Search.</td></tr>
        <?php else: ?>
            <?php foreach ($privateThreads as $t): ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['other_name']); ?></td>
                    <td><?php echo htmlspecialchars($t['other_email']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($t['other_role'])); ?></td>
                    <td><?php echo htmlspecialchars($t['last_message_at']); ?></td>
                    <td>
                        <a href="index.php?controller=StudentController&action=privateChat&id=<?php echo $t['other_id']; ?>">Open Chat</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

