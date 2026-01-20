<form class="auth-form" method="post">
    <div class="form-row">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   value="<?php echo $isEdit ? htmlspecialchars($user->name) : ''; ?>" required>
        </div>
         <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?php echo $isEdit ? htmlspecialchars($user->email) : ''; ?>" required>
        </div>
    </div>
     <?php if (!$isEdit): ?>
        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="6" required>
            </div>
        </div>
    <?php endif; ?>
<div class="form-row">
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">Select role</option>
                <option value="teacher" <?php echo $isEdit && $user->role === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="student" <?php echo $isEdit && $user->role === 'student' ? 'selected' : ''; ?>>Student</option>
                <?php if ($isEdit && $user->role === 'admin'): ?>
                    <option value="admin" selected>Admin</option>
                <?php endif; ?>
            </select>
        </div>