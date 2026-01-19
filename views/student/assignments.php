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