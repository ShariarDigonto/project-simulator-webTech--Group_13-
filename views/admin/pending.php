<?php
ob_start();
?>
<div class="table-header">
    <div>
        <strong>Pending Requests:</strong> <?php echo count($users); ?>
    </div>
</div>
<div class="table-wrapper">
    <table class="simple-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Requested At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>