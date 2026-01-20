<?php
ob_start();
?>
<p><strong>Assignment:</strong> <?php echo htmlspecialchars($assignment['title']); ?></p>

<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>Student</th>
            <th>Email</th>
            <th>Submitted At</th>
            <th>File</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($submissions)): ?>
            <tr><td colspan="4" class="text-center">No submissions yet.</td></tr>
        <?php else: ?>
            <?php foreach ($submissions as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($s['student_email']); ?></td>
                    <td><?php echo htmlspecialchars($s['submitted_at']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($s['file_path']); ?>" target="_blank">Download</a></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

