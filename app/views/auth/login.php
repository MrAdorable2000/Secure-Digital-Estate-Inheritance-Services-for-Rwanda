<div class="auth-card">
    <div class="auth-logo">
        <a href="<?php echo url('/'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-right: 6px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            R-DEIP
        </a>
        <p class="text-muted" style="margin-top: var(--sp-2); text-align: center; font-size: var(--text-xs);">Rwanda Digital Estate &amp; Inheritance Platform</p>
    </div>

    <h1>Sign In</h1>
    <p class="text-muted">Access your R-DEIP account to manage your digital records.</p>

    <form method="POST" action="<?php echo url('login'); ?>" autocomplete="on">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-input"
                value="<?php echo e(old('email')); ?>"
                placeholder="you@example.com"
                required
                autofocus
                autocomplete="email"
            >
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <div class="form-row" style="display: flex; justify-content: space-between; align-items: center;">
            <label class="checkbox-wrapper" style="display: flex; align-items: center; gap: var(--sp-2); cursor: pointer; font-size: var(--text-sm); color: var(--color-text-secondary);">
                <input type="checkbox" name="remember" value="1" <?php echo old('remember') ? 'checked' : ''; ?> style="width: 16px; height: 16px; accent-color: var(--color-primary); cursor: pointer;">
                <span>Remember me</span>
            </label>
            <a href="<?php echo url('forgot-password'); ?>" class="auth-link" style="font-size: var(--text-sm); font-weight: 500; color: var(--color-primary);">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn--primary btn--block">Sign In</button>
    </form>

    <div class="auth-divider"><span>or</span></div>

    <p class="text-center" style="margin-top: var(--sp-4); font-size: var(--text-sm);">
        Don't have an account? <a href="<?php echo url('register'); ?>">Create one</a>
    </p>

    <p class="text-muted text-center" style="margin-top: var(--sp-6); font-size: var(--text-xs);">
        &copy; <?php echo date('Y'); ?> R-DEIP
    </p>
</div>
