<?php
ob_start();
?>
<div class="table-header">
    <div>
        <strong>Total Users:</strong> <?php echo count($users); ?>
    </div>
    <a class="btn btn-primary" href="index.php?controller=AdminController&action=add">Add User</a>
</div>
