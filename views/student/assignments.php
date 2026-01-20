<p><strong>Group:</strong> <?php echo htmlspecialchars($group['name']); ?></p>

<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Due</th>
            <th>Late?</th>
            <th>File</th>
            <th>Submit</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($assignments)): ?>
            <tr><td colspan="5" class="text-center">No assignments yet.</td></tr>
        <?php else: ?>
            <?php foreach ($assignments as $a): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['title']); ?></td>
                    <td><?php echo htmlspecialchars($a['due_at'] ?? '-'); ?></td>
                    <td><?php echo (int)$a['allow_late'] === 1 ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php if (!empty($a['file_path'])): ?>
                            <a href="<?php echo htmlspecialchars($a['file_path']); ?>" target="_blank">Download</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?controller=StudentController&action=submit&assignment_id=<?php echo $a['id']; ?>">Submit</a>
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
