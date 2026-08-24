<div class="auth-card">
    <div class="auth-logo">
        <a href="<?php echo url('/'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-right: 6px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            R-DEIP
        </a>
        <p class="text-muted" style="margin-top: var(--sp-2); text-align: center; font-size: var(--text-xs);">Rwanda Digital Estate &amp; Inheritance Platform</p>
    </div>

    <div style="text-align: center; margin-bottom: var(--sp-2);">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: var(--sp-2);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
    </div>

    <h1>Reset Your Password</h1>
    <p class="text-muted">Enter your new password below.</p>

    <form method="POST" action="<?php echo url('reset-password'); ?>" autocomplete="on">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="token" value="<?php echo e($token); ?>">

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Create a strong password"
                    required
                    autofocus
                    autocomplete="new-password"
                    data-password-strength
                >
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <div class="password-strength">
                <div class="password-strength-bar"><div class="password-strength-fill"></div></div>
            </div>
            <p class="form-hint">Minimum 8 characters with uppercase, lowercase, number, and special character.</p>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Confirm your new password"
                    required
                    autocomplete="new-password"
                >
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn--primary btn--block">Reset Password</button>
    </form>

    <p class="text-center" style="margin-top: var(--sp-5); font-size: var(--text-sm);">
        <a href="<?php echo url('login'); ?>" style="color: var(--color-text-secondary); font-weight: 500;">&larr; Back to sign in</a>
    </p>

    <p class="text-muted text-center" style="margin-top: var(--sp-6); font-size: var(--text-xs);">
        &copy; <?php echo date('Y'); ?> R-DEIP
    </p>
</div>
