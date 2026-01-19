<?php
ob_start();
?>
<div class="table-header">
    <div>
        <strong>Total Users:</strong> <?php echo count($users); ?>
    </div>
    <a class="btn btn-primary" href="index.php?controller=AdminController&action=add">Add User</a>
</div>

<div class="table-wrapper">
    <table class="simple-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($users as $u): ?>
                <tr>

                <td><?php echo htmlspecialchars($u['id']); ?></td>
                    <td><?php echo htmlspecialchars($u['name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($u['status'])); ?></td>
                    <td>