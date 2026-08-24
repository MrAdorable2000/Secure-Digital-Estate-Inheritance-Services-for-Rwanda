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

    <h1>Forgot Your Password?</h1>
    <p class="text-muted">Enter your email address and we will send you a link to reset your password.</p>

    <form method="POST" action="<?php echo url('forgot-password'); ?>">
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
            <p class="form-hint">Enter the email address associated with your R-DEIP account.</p>
        </div>

        <button type="submit" class="btn btn--primary btn--block">Send Reset Link</button>
    </form>

    <p class="text-center" style="margin-top: var(--sp-5); font-size: var(--text-sm);">
        <a href="<?php echo url('login'); ?>" style="color: var(--color-text-secondary); font-weight: 500;">&larr; Back to sign in</a>
    </p>

    <p class="text-muted text-center" style="margin-top: var(--sp-6); font-size: var(--text-xs);">
        &copy; <?php echo date('Y'); ?> R-DEIP
    </p>
</div>
