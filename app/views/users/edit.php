<?php
/**
 * Edit User Form Page
 *
 * Data variables:
 *   $editUser  - the user record being edited
 *   $roles     - array of roles (id, name/slug, display_name)
 *   $departments - array of departments (id, name)
 *   $userRole  - current role_id of the user
 *   $errors    - array of validation errors (optional)
 */

$editUser = $editUser ?? [];
$errors = $errors ?? [];
$editFullName = trim(e($editUser['first_name'] ?? '') . ' ' . e($editUser['last_name'] ?? ''));
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb mb-2" aria-label="Breadcrumb">
        <a href="<?= url('/dashboard') ?>" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-separator" aria-hidden="true">/</span>
        <a href="<?= url('users') ?>" class="breadcrumb-item">Users</a>
        <span class="breadcrumb-separator" aria-hidden="true">/</span>
        <span class="breadcrumb-item active" aria-current="page">Edit User</span>
    </nav>

    <div class="card">
        <div class="card-header">
            <h2>Edit User: <?= $editFullName ?></h2>
            <p class="text-muted">Update user details below. Leave password blank to keep the current one.</p>
        </div>
        <div class="card-body">
            <!-- Current Status Info -->
            <div class="d-flex gap-2 mb-3" style="flex-wrap: wrap;">
                <div class="d-flex align-center gap-2" style="background: #f9fafb; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span class="text-muted">Email:</span>
                    <strong><?= e($editUser['email'] ?? '') ?></strong>
                </div>
                <div class="d-flex align-center gap-2" style="background: #f9fafb; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="text-muted">Status:</span>
                    <?php
                        $editStatus = $editUser['status'] ?? 'inactive';
                        $editStatusBadge = 'badge-info';
                        if ($editStatus === 'active') $editStatusBadge = 'badge-success';
                        elseif ($editStatus === 'pending') $editStatusBadge = 'badge-warning';
                        elseif ($editStatus === 'suspended') $editStatusBadge = 'badge-danger';
                    ?>
                    <span class="badge <?= $editStatusBadge ?>"><?= e(ucfirst($editStatus)) ?></span>
                </div>
            </div>

            <!-- Form Errors Summary -->
            <?php if (!empty($errors)): ?>
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1.25rem;color:#991b1b;">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin:0.5rem 0 0 0;padding-left:1.25rem;">
                        <?php foreach ($errors as $field => $message): ?>
                            <li><?= e(is_array($message) ? implode(', ', $message) : $message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('users/' . $editUser['id'] . '/update') ?>" id="edit-user-form" novalidate>
                <?= csrf_field() ?>

                <div class="d-flex gap-2" style="flex-wrap: wrap;">
                    <!-- Left Column -->
                    <div style="flex: 1; min-width: 280px;">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label" for="first_name">First Name <span style="color:#ef4444;">*</span></label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="form-input<?= isset($errors['first_name']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('first_name', $editUser['first_name'] ?? '')) ?>"
                                required
                                autocomplete="given-name"
                            />
                            <?php if (isset($errors['first_name'])): ?>
                                <div class="form-error"><?= e(is_array($errors['first_name']) ? $errors['first_name'][0] : $errors['first_name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label" for="last_name">Last Name <span style="color:#ef4444;">*</span></label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="form-input<?= isset($errors['last_name']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('last_name', $editUser['last_name'] ?? '')) ?>"
                                required
                                autocomplete="family-name"
                            />
                            <?php if (isset($errors['last_name'])): ?>
                                <div class="form-error"><?= e(is_array($errors['last_name']) ? $errors['last_name'][0] : $errors['last_name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email (read-only display, actual email change handled separately) -->
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input
                                type="email"
                                id="email_display"
                                class="form-input"
                                value="<?= e($editUser['email'] ?? '') ?>"
                                disabled
                                style="background: #f9fafb; cursor: not-allowed;"
                            />
                            <small class="text-muted">Email cannot be changed here. Contact a system administrator if needed.</small>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-input<?= isset($errors['phone']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('phone', $editUser['phone'] ?? '')) ?>"
                                autocomplete="tel"
                            />
                            <?php if (isset($errors['phone'])): ?>
                                <div class="form-error"><?= e(is_array($errors['phone']) ? $errors['phone'][0] : $errors['phone']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div style="flex: 1; min-width: 280px;">
                        <!-- Password (optional) -->
                        <div class="form-group">
                            <label class="form-label" for="password">New Password</label>
                            <div style="position: relative;">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-input<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                                    autocomplete="new-password"
                                    style="padding-right: 2.5rem;"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.25rem 0.5rem; border: none; background: none; color: var(--color-muted, #6b7280); cursor: pointer;"
                                    onclick="togglePasswordVisibility('password', this)"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            <!-- Password Strength Indicator -->
                            <div id="password-strength" style="margin-top: 0.5rem; display: none;">
                                <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                                    <div id="str-bar-1" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="str-bar-2" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="str-bar-3" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="str-bar-4" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                </div>
                                <small id="str-text" class="text-muted" style="font-size: 0.75rem;"></small>
                            </div>
                            <small class="text-muted">Leave blank to keep the current password.</small>
                            <?php if (isset($errors['password'])): ?>
                                <div class="form-error"><?= e(is_array($errors['password']) ? $errors['password'][0] : $errors['password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label class="form-label" for="role_id">Role <span style="color:#ef4444;">*</span></label>
                            <select
                                id="role_id"
                                name="role_id"
                                class="form-select<?= isset($errors['role_id']) ? ' is-invalid' : '' ?>"
                                required
                            >
                                <option value="">Select a role...</option>
                                <?php foreach ($roles as $role): ?>
                                    <?php
                                        $roleValue = $role['id'] ?? $role['value'] ?? '';
                                        $roleLabel = $role['display_name'] ?? $role['name'] ?? $role['label'] ?? '';
                                        $oldRole = old('role_id', $userRole ?? '');
                                        $selected = (string) $oldRole === (string) $roleValue ? 'selected' : '';
                                    ?>
                                    <option value="<?= e($roleValue) ?>" <?= $selected ?>><?= e($roleLabel) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['role_id'])): ?>
                                <div class="form-error"><?= e(is_array($errors['role_id']) ? $errors['role_id'][0] : $errors['role_id']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label class="form-label" for="department_id">Department</label>
                            <select
                                id="department_id"
                                name="department_id"
                                class="form-select<?= isset($errors['department_id']) ? ' is-invalid' : '' ?>"
                            >
                                <option value="">Select a department...</option>
                                <?php foreach ($departments as $dept): ?>
                                    <?php
                                        $deptValue = $dept['id'] ?? $dept['value'] ?? '';
                                        $deptLabel = $dept['name'] ?? $dept['label'] ?? '';
                                        $oldDept = old('department_id', $editUser['department_id'] ?? '');
                                        $selected = (string) $oldDept === (string) $deptValue ? 'selected' : '';
                                    ?>
                                    <option value="<?= e($deptValue) ?>" <?= $selected ?>><?= e($deptLabel) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['department_id'])): ?>
                                <div class="form-error"><?= e(is_array($errors['department_id']) ? $errors['department_id'][0] : $errors['department_id']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-between mt-3">
                    <a href="<?= url('users') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}

(function() {
    const pwInput = document.getElementById('password');
    const strengthContainer = document.getElementById('password-strength');
    if (!pwInput || !strengthContainer) return;

    const bars = [
        document.getElementById('str-bar-1'),
        document.getElementById('str-bar-2'),
        document.getElementById('str-bar-3'),
        document.getElementById('str-bar-4')
    ];
    const textEl = document.getElementById('str-text');

    pwInput.addEventListener('input', function() {
        const val = this.value;
        if (val.length === 0) {
            strengthContainer.style.display = 'none';
            return;
        }
        strengthContainer.style.display = 'block';

        let score = 0;
        if (val.length >= 8)  score++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];

        bars.forEach(function(bar, i) {
            bar.style.background = i < score ? colors[score - 1] : '#e5e7eb';
        });
        textEl.textContent = labels[score - 1] || 'Too short';
        textEl.style.color = colors[score - 1] || '#6b7280';
    });
})();
</script>