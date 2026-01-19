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