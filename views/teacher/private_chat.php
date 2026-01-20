<?php
ob_start();
?>
<p><strong>Chat with:</strong> <?php echo htmlspecialchars($other->name); ?> (<?php echo htmlspecialchars($other->email); ?>)</p>

<div class="chat-box">
    <?php if (empty($messages)): ?>
        <p class="muted">No messages yet.</p>
    <?php else: ?>
        <?php foreach ($messages as $m): ?>
            <div class="chat-line">
                <strong><?php echo htmlspecialchars($m['sender_name']); ?>:</strong>
                <?php echo htmlspecialchars($m['body']); ?>
                <small class="muted"><?php echo htmlspecialchars($m['created_at']); ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<form class="auth-form" method="post">
    <div class="form-group">
        <label for="body">Message</label>
        <input type="text" id="body" name="body" required>
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';

