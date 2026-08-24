<div class="auth-card">
    <div class="auth-logo">
        <a href="<?php echo url('/'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -3px; margin-right: 6px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            R-DEIP
        </a>
        <p class="text-muted" style="margin-top: var(--sp-2); text-align: center; font-size: var(--text-xs);">Rwanda Digital Estate &amp; Inheritance Platform</p>
    </div>

    <h1>Create Your Account</h1>
    <p class="text-muted">Join the platform to access secure digital estate services.</p>

    <form method="POST" action="<?php echo url('register'); ?>" autocomplete="on">
        <?php echo csrf_field(); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--sp-3);">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    class="form-input"
                    value="<?php echo e(old('first_name')); ?>"
                    placeholder="John"
                    required
                    autofocus
                    autocomplete="given-name"
                >
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    class="form-input"
                    value="<?php echo e(old('last_name')); ?>"
                    placeholder="Doe"
                    required
                    autocomplete="family-name"
                >
            </div>
        </div>

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
                autocomplete="email"
            >
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Phone Number <span class="text-muted">(optional)</span></label>
            <div class="input-group" style="display: flex;">
                <span class="input-group-addon" style="display: flex; align-items: center; padding: 0 var(--sp-3); background: var(--color-primary-50); border: 1px solid var(--color-border); border-right: none; border-radius: var(--radius-md) 0 0 var(--radius-md); font-size: var(--text-sm); font-weight: 500; color: var(--color-text-secondary); white-space: nowrap; user-select: none;">+250</span>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-input"
                    value="<?php echo e(old('phone')); ?>"
                    placeholder="7XX XXX XXX"
                    autocomplete="tel"
                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                >
            </div>
            <p class="form-hint">Rwandan phone number format preferred</p>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Create a strong password"
                    required
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
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Confirm your password"
                    required
                    autocomplete="new-password"
                >
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn--primary btn--block">Create Account</button>
    </form>

    <div class="auth-divider"><span>or</span></div>

    <p class="text-center" style="margin-top: var(--sp-4); font-size: var(--text-sm);">
        Already have an account? <a href="<?php echo url('login'); ?>">Sign in</a>
    </p>

    <p class="text-muted text-center" style="margin-top: var(--sp-6); font-size: var(--text-xs);">
        &copy; <?php echo date('Y'); ?> R-DEIP
    </p>
</div>
