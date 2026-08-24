<?php
/**
 * User Profile Page
 *
 * Data variable:
 *   $user - current authenticated user record
 */

$u = $user ?? [];
$fullName  = trim(e($u['first_name'] ?? '') . ' ' . e($u['last_name'] ?? ''));
$firstName = e($u['first_name'] ?? '');
$lastName  = e($u['last_name'] ?? '');
$initials  = strtoupper(mb_substr($u['first_name'] ?? '', 0, 1) . mb_substr($u['last_name'] ?? '', 0, 1));
$photoUrl  = !empty($u['profile_photo']) ? e($u['profile_photo']) : '';
$roleName  = e(ucfirst(str_replace('_', ' ', $u['role_name'] ?? $u['role_slug'] ?? $u['role'] ?? 'User')));
$status    = $u['status'] ?? 'inactive';
$statusBadge = 'badge-info';
if ($status === 'active')   $statusBadge = 'badge-success';
elseif ($status === 'pending')  $statusBadge = 'badge-warning';
elseif ($status === 'suspended') $statusBadge = 'badge-danger';
$emailVerified = !empty($u['email_verified_at']);
$memberSince   = !empty($u['created_at']) ? format_datetime($u['created_at']) : 'N/A';
$errors = $errors ?? [];
$pwErrors = $pwErrors ?? [];
?>

<div class="container">
    <!-- Page Header -->
    <div class="mb-3">
        <h1>My Profile</h1>
        <p class="text-muted">Manage your account settings and personal information</p>
    </div>

    <!-- Flash Messages (profile-specific) -->
    <?php if (Flash::has('profile_success')): ?>
        <div class="alert alert-success" style="margin-bottom: 1rem; border-radius: 8px; padding: 0.75rem 1rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
            <?= e(Flash::get('profile_success')) ?>
        </div>
    <?php endif; ?>
    <?php if (Flash::has('password_success')): ?>
        <div class="alert alert-success" style="margin-bottom: 1rem; border-radius: 8px; padding: 0.75rem 1rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
            <?= e(Flash::get('password_success')) ?>
        </div>
    <?php endif; ?>
    <?php if (Flash::has('profile_error')): ?>
        <div class="alert alert-error" style="margin-bottom: 1rem; border-radius: 8px; padding: 0.75rem 1rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
            <?= e(Flash::get('profile_error')) ?>
        </div>
    <?php endif; ?>

    <!-- Two-column layout -->
    <div class="d-flex gap-2" style="align-items: flex-start; flex-wrap: wrap;">

        <!-- ========== LEFT COLUMN: Profile Card ========== -->
        <div style="width: 300px; flex-shrink: 0; min-width: 260px;">
            <div class="card">
                <div class="card-body text-center p-3">
                    <!-- Avatar -->
                    <?php if ($photoUrl): ?>
                        <div style="position: relative; display: inline-block; margin-bottom: 1rem;">
                            <img
                                src="<?= $photoUrl ?>"
                                alt="<?= $fullName ?>"
                                style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-primary, #3b82f6);"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                            />
                            <div style="display:none;width:96px;height:96px;border-radius:50%;background:var(--color-primary,#3b82f6);color:#fff;align-items:center;justify-content:center;font-size:1.75rem;font-weight:700;border:3px solid var(--color-primary,#3b82f6);"><?= $initials ?></div>
                        </div>
                    <?php else: ?>
                        <div style="width:96px;height:96px;border-radius:50%;background:var(--color-primary,#3b82f6);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:700;margin:0 auto 1rem auto;border:3px solid var(--color-primary,#3b82f6);">
                            <?= $initials ?: '?' ?>
                        </div>
                    <?php endif; ?>

                    <!-- Name -->
                    <h3 style="margin: 0 0 0.25rem 0; font-size: 1.25rem;"><?= $fullName ?: 'Unknown User' ?></h3>

                    <!-- Role Badge -->
                    <div class="mb-1">
                        <span class="badge"><?= $roleName ?></span>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-2">
                        <span class="badge <?= $statusBadge ?>"><?= e(ucfirst($status)) ?></span>
                    </div>

                    <!-- Email Verified -->
                    <div class="d-flex align-center gap-2 justify-center mb-1" style="font-size: 0.875rem;">
                        <?php if ($emailVerified): ?>
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span style="color: #10b981; font-weight: 500;">Email Verified</span>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span style="color: #f59e0b; font-weight: 500;">Email Not Verified</span>
                        <?php endif; ?>
                    </div>

                    <!-- Member Since -->
                    <div class="text-muted" style="font-size: 0.8rem;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Member since <?= $memberSince ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== RIGHT COLUMN: Forms ========== -->
        <div style="flex: 1; min-width: 320px;">

            <!-- Card 1: Edit Profile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h2 style="margin: 0; font-size: 1.125rem;">Edit Profile</h2>
                    <p class="text-muted" style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">Update your personal information</p>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="<?= url('profile') ?>" id="edit-profile-form" novalidate>
                        <?= csrf_field() ?>

                        <div class="d-flex gap-2" style="flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 200px;">
                                <label class="form-label" for="profile_first_name">First Name</label>
                                <input
                                    type="text"
                                    id="profile_first_name"
                                    name="first_name"
                                    class="form-input<?= isset($errors['first_name']) ? ' is-invalid' : '' ?>"
                                    value="<?= e(old('first_name', $u['first_name'] ?? '')) ?>"
                                    autocomplete="given-name"
                                />
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="form-error"><?= e(is_array($errors['first_name']) ? $errors['first_name'][0] : $errors['first_name']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 200px;">
                                <label class="form-label" for="profile_last_name">Last Name</label>
                                <input
                                    type="text"
                                    id="profile_last_name"
                                    name="last_name"
                                    class="form-input<?= isset($errors['last_name']) ? ' is-invalid' : '' ?>"
                                    value="<?= e(old('last_name', $u['last_name'] ?? '')) ?>"
                                    autocomplete="family-name"
                                />
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="form-error"><?= e(is_array($errors['last_name']) ? $errors['last_name'][0] : $errors['last_name']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="profile_email">Email Address</label>
                            <input
                                type="email"
                                id="profile_email"
                                name="email"
                                class="form-input<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('email', $u['email'] ?? '')) ?>"
                                autocomplete="email"
                            />
                            <?php if (isset($errors['email'])): ?>
                                <div class="form-error"><?= e(is_array($errors['email']) ? $errors['email'][0] : $errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="profile_phone">Phone Number</label>
                            <input
                                type="tel"
                                id="profile_phone"
                                name="phone"
                                class="form-input<?= isset($errors['phone']) ? ' is-invalid' : '' ?>"
                                value="<?= e(old('phone', $u['phone'] ?? '')) ?>"
                                autocomplete="tel"
                            />
                            <?php if (isset($errors['phone'])): ?>
                                <div class="form-error"><?= e(is_array($errors['phone']) ? $errors['phone'][0] : $errors['phone']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 2: Change Password -->
            <div class="card">
                <div class="card-header">
                    <h2 style="margin: 0; font-size: 1.125rem;">Change Password</h2>
                    <p class="text-muted" style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">Ensure your account is using a strong password</p>
                </div>
                <div class="card-body">
                    <?php if (!empty($pwErrors)): ?>
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1.25rem;color:#991b1b;">
                            <strong>Please fix the following errors:</strong>
                            <ul style="margin:0.5rem 0 0 0;padding-left:1.25rem;">
                                <?php foreach ($pwErrors as $field => $message): ?>
                                    <li><?= e(is_array($message) ? implode(', ', $message) : $message) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= url('profile/password') ?>" id="change-password-form" novalidate>
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label class="form-label" for="current_password">Current Password <span style="color:#ef4444;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    class="form-input<?= isset($pwErrors['current_password']) ? ' is-invalid' : '' ?>"
                                    required
                                    autocomplete="current-password"
                                    style="padding-right: 2.5rem;"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.25rem 0.5rem; border: none; background: none; color: var(--color-muted, #6b7280); cursor: pointer;"
                                    onclick="togglePasswordVisibility('current_password', this)"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            <?php if (isset($pwErrors['current_password'])): ?>
                                <div class="form-error"><?= e(is_array($pwErrors['current_password']) ? $pwErrors['current_password'][0] : $pwErrors['current_password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_password">New Password <span style="color:#ef4444;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="password"
                                    id="new_password"
                                    name="new_password"
                                    class="form-input<?= isset($pwErrors['new_password']) ? ' is-invalid' : '' ?>"
                                    required
                                    autocomplete="new-password"
                                    style="padding-right: 2.5rem;"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.25rem 0.5rem; border: none; background: none; color: var(--color-muted, #6b7280); cursor: pointer;"
                                    onclick="togglePasswordVisibility('new_password', this)"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            <!-- Password Strength Meter -->
                            <div id="pw-strength" style="margin-top: 0.5rem; display: none;">
                                <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                                    <div id="pw-bar-1" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="pw-bar-2" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="pw-bar-3" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                    <div id="pw-bar-4" style="flex:1;height:4px;border-radius:2px;background:#e5e7eb;"></div>
                                </div>
                                <small id="pw-str-text" class="text-muted" style="font-size: 0.75rem;"></small>
                            </div>
                            <?php if (isset($pwErrors['new_password'])): ?>
                                <div class="form-error"><?= e(is_array($pwErrors['new_password']) ? $pwErrors['new_password'][0] : $pwErrors['new_password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_password_confirmation">Confirm New Password <span style="color:#ef4444;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="password"
                                    id="new_password_confirmation"
                                    name="new_password_confirmation"
                                    class="form-input<?= isset($pwErrors['new_password_confirmation']) ? ' is-invalid' : '' ?>"
                                    required
                                    autocomplete="new-password"
                                    style="padding-right: 2.5rem;"
                                />
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.25rem 0.5rem; border: none; background: none; color: var(--color-muted, #6b7280); cursor: pointer;"
                                    onclick="togglePasswordVisibility('new_password_confirmation', this)"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            <?php if (isset($pwErrors['new_password_confirmation'])): ?>
                                <div class="form-error"><?= e(is_array($pwErrors['new_password_confirmation']) ? $pwErrors['new_password_confirmation'][0] : $pwErrors['new_password_confirmation']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
    const pwInput = document.getElementById('new_password');
    const strengthContainer = document.getElementById('pw-strength');
    if (!pwInput || !strengthContainer) return;

    const bars = [
        document.getElementById('pw-bar-1'),
        document.getElementById('pw-bar-2'),
        document.getElementById('pw-bar-3'),
        document.getElementById('pw-bar-4')
    ];
    const textEl = document.getElementById('pw-str-text');

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