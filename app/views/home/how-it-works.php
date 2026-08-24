<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>How It Works - R-DEIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <style>body { font-family: 'Inter', var(--font-sans); }</style>
</head>
<body>

    <div class="toast-container"></div>

    <!-- ===== NAVIGATION ===== -->
    <nav class="site-nav" id="site-nav">
        <div class="container site-nav__inner">
            <a href="<?php echo url('/'); ?>" class="site-nav__brand">
                <div class="site-nav__logo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <div class="site-nav__wordmark">R-DEIP</div>
                    <div class="site-nav__descriptor">Rwanda Digital Estate &amp; Inheritance Platform</div>
                </div>
            </a>

            <div class="site-nav__links">
                <a href="#services" class="site-nav__link">Services</a>
                <a href="#how-it-works" class="site-nav__link">How It Works</a>
                <a href="#about-rwanda" class="site-nav__link">About</a>
            </div>

            <div class="site-nav__actions">
                <a href="<?php echo url('login'); ?>" class="btn btn--ghost btn--sm">Sign In</a>
                <a href="<?php echo url('register'); ?>" class="btn btn--primary btn--sm">Get Started</a>
            </div>

            <button class="site-nav__hamburger" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>
        </div>

        <div class="site-nav__mobile" id="mobile-menu">
            <a href="#services">Services</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#about-rwanda">About</a>
            <a href="<?php echo url('about'); ?>">About R-DEIP</a>
            <a href="<?php echo url('contact'); ?>">Contact</a>
            <div class="site-nav__mobile-actions">
                <a href="<?php echo url('login'); ?>" class="btn btn--secondary btn--block">Sign In</a>
                <a href="<?php echo url('register'); ?>" class="btn btn--primary btn--block">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section style="background:var(--bg-dark);padding:var(--sp-12) 0;text-align:center;">
        <div class="container">
            <h1 style="color:#fff;">How It Works</h1>
            <p style="color:rgba(255,255,255,0.7);max-width:560px;margin:var(--sp-3) auto 0;font-size:var(--text-md);">Getting started with R-DEIP is straightforward.</p>
        </div>
    </section>

    <!-- Steps -->
    <section class="section reveal">
        <div class="container">
            <div class="section__header">
                <span class="section__label">Phase 1</span>
                <h2>Getting Started</h2>
                <p>Follow these steps to create your account and access the platform.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-card__number">01</div>
                    <h3>Create an Account</h3>
                    <p>Visit the registration page and provide your name, email, and a secure password. You will receive a verification email.</p>
                </div>
                <div class="step-card">
                    <div class="step-card__number">02</div>
                    <h3>Verify Your Email</h3>
                    <p>Click the verification link in your email to activate your account and confirm your identity.</p>
                </div>
                <div class="step-card">
                    <div class="step-card__number">03</div>
                    <h3>Sign In</h3>
                    <p>Use your email and password to sign in. You will be directed to your role-specific dashboard.</p>
                </div>
                <div class="step-card">
                    <div class="step-card__number">04</div>
                    <h3>Manage Your Profile</h3>
                    <p>Update your profile information, change your password, and explore your available services.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Future -->
    <section class="section section--neutral reveal">
        <div class="container">
            <div class="section__header">
                <span class="section__label" style="background:#fffbeb;color:#92400e;">Coming Later</span>
                <h2>Future Workflows</h2>
                <p>Additional steps will become available as future phases are implemented.</p>
            </div>
            <div class="services-grid" style="max-width:800px;margin:0 auto;">
                <div class="service-card service-card--future" style="opacity:0.7;pointer-events:none;position:relative;"><span style="position:absolute;top:var(--sp-4);right:var(--sp-4);font-size:var(--text-xs);font-weight:600;color:var(--color-text-muted);background:var(--bg-subtle);padding:0.15rem 0.5rem;border-radius:var(--radius-full);">Phase 4</span><div class="service-card__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div><h3>Register an Estate</h3><p>Enter property and asset details with supporting documents.</p></div>
                <div class="service-card service-card--future" style="opacity:0.7;pointer-events:none;position:relative;"><span style="position:absolute;top:var(--sp-4);right:var(--sp-4);font-size:var(--text-xs);font-weight:600;color:var(--color-text-muted);background:var(--bg-subtle);padding:0.15rem 0.5rem;border-radius:var(--radius-full);">Phase 5</span><div class="service-card__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><h3>Link Beneficiaries</h3><p>Connect family members and assign inheritance shares.</p></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer__brand-name">R-DEIP</div>
                    <p class="footer__brand-desc">Rwanda Digital Estate &amp; Inheritance Platform — a secure digital foundation for managing estate and inheritance records.</p>
                </div>

                <div>
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="<?php echo url('about'); ?>">About</a></li>
                        <li><a href="<?php echo url('contact'); ?>">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Support</h4>
                    <ul>
                        <li><a href="<?php echo url('contact'); ?>">Contact</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Terms</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Status</h4>
                    <ul>
                        <li>
                            <span class="badge badge-info">Phase 1</span>
                        </li>
                        <li style="margin-top: var(--sp-3);">
                            <span style="font-size: var(--text-xs); color: rgba(255,255,255,0.35); line-height: var(--leading-relaxed);">Authentication, roles, and audit logging are live.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer__bottom">
                <p>&copy; <?php echo date('Y'); ?> R-DEIP. All rights reserved.</p>
                <div class="footer__phase-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Phase 1 — Active Development
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo asset('js/app.js'); ?>"></script>
    <script>
    (function() {
        // Sticky nav scroll effect
        var nav = document.getElementById('site-nav');
        if (nav) {
            var scrollThreshold = 40;
            window.addEventListener('scroll', function() {
                if (window.scrollY > scrollThreshold) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            }, { passive: true });
        }

        // Mobile menu
        var toggle = document.querySelector('.site-nav__hamburger');
        var mobile = document.getElementById('mobile-menu');
        if (toggle && mobile) {
            toggle.addEventListener('click', function() {
                toggle.classList.toggle('open');
                mobile.classList.toggle('open');
                document.body.style.overflow = mobile.classList.contains('open') ? 'hidden' : '';
            });
            mobile.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    toggle.classList.remove('open');
                    mobile.classList.remove('open');
                    document.body.style.overflow = '';
                });
            });
        }

        // Scroll reveal
        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!prefersReducedMotion) {
            var reveals = document.querySelectorAll('.reveal, .reveal-stagger');
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
                reveals.forEach(function(el) { observer.observe(el); });
            } else {
                reveals.forEach(function(el) { el.classList.add('revealed'); });
            }
        } else {
            document.querySelectorAll('.reveal, .reveal-stagger').forEach(function(el) { el.classList.add('revealed'); });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'start' });
                }
            });
        });
    })();
    </script>
</body>
</html>
