<?php
/**
 * Create User Form Page
 *
 * Data variables:
 *   $roles       - array of roles (id, name/slug, display_name)
 *   $departments - array of departments (id, name)
 *   $errors      - array of validation errors (optional)
 */

$errors = $errors ?? [];
?>

<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb mb-2" aria-label="Breadcrumb">
        <a href="<?= url('/dashboard') ?>" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-separator" aria-hidden="true">/</span>
        <a href="<?= url('users') ?>" class="breadcrumb-item">Users</a>
        <span class="breadcrumb-separator" aria-hidden="true">/</span>
        <span class="breadcrumb-item active" aria-current="page">Create User</span>
    </nav>

    <div class="card">
        <div class="card-header">
            <h2>Create New User</h2>
            <p class="text-muted">Fill in the details below to add a new user to the system.</p>
        </div>
        <div class="card-body">
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

            <form method="POST" action="<?= url('users') ?>" id="create-user-form" novalidate>
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
                                value="<?= e(old('first_name')) ?>"
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
                                value="<?= e(old('last_name')) ?>"
                                required
                                autocomplete="family-name"
                            />
                            <?php if (isset($errors['last_name'])): ?>
                                <div class="form-error"><?= e(is_array($errors['last_name']) ? $errors['last_name'][0] : $errors['last_name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address <span style="color:#ef4444;">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('email')) ?>"
                                required
                                autocomplete="email"
                            />
                            <?php if (isset($errors['email'])): ?>
                                <div class="form-error"><?= e(is_array($errors['email']) ? $errors['email'][0] : $errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-input<?= isset($errors['phone']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('phone')) ?>"
                                autocomplete="tel"
                            />
                            <?php if (isset($errors['phone'])): ?>
                                <div class="form-error"><?= e(is_array($errors['phone']) ? $errors['phone'][0] : $errors['phone']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div style="flex: 1; min-width: 280px;">
                        <!-- Password -->
                        <div class="form-group">
                            <label class="form-label" for="password">Password <span style="color:#ef4444;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-input<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                                    required
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
                                        $selected = old('role_id') == $roleValue ? 'selected' : '';
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
                                        $selected = old('department_id') == $deptValue ? 'selected' : '';
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
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        Create User
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