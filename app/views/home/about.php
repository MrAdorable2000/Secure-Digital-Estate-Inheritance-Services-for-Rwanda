<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>About R-DEIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <style>body { font-family: 'Inter', var(--font-sans); }</style>
</head>
<body>

    <?php if (\Flash::has('success')): ?>
        <?php foreach (\Flash::get('success') as $msg): ?>
            <div class="alert alert-success"><?php echo e($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

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
            <h1 style="color:#fff;">About R-DEIP</h1>
            <p style="color:rgba(255,255,255,0.7);max-width:560px;margin:var(--sp-3) auto 0;font-size:var(--text-md);">The platform and its mission</p>
        </div>
    </section>

    <!-- Content -->
    <section class="section reveal">
        <div class="container container--narrow">
            <h2 style="margin-bottom:var(--sp-4);">Our Mission</h2>
            <p style="font-size:var(--text-md);line-height:var(--leading-relaxed);margin-bottom:var(--sp-5);">
                R-DEIP is a Rwanda-focused digital platform designed to provide a secure foundation for managing estate and inheritance information. The platform is being developed in a phased approach, with Phase 1 establishing the core security, authentication, and administrative infrastructure.
            </p>
            <p style="font-size:var(--text-md);line-height:var(--leading-relaxed);margin-bottom:var(--sp-5);">
                Built with Rwanda at its centre, the platform addresses local needs in estate and inheritance management while maintaining the flexibility to support additional jurisdictions in the future.
            </p>

            <h2 style="margin-bottom:var(--sp-4);">Phase 1: Foundation</h2>
            <p style="font-size:var(--text-md);line-height:var(--leading-relaxed);margin-bottom:var(--sp-4);">
                The first phase focuses on building the secure foundation upon which all future modules will operate:
            </p>
            <ul style="font-size:var(--text-base);line-height:2.2;padding-left:var(--sp-5);margin-bottom:var(--sp-6);list-style:disc;">
                <li><strong>Secure Authentication</strong> — Login with session management and password recovery.</li>
                <li><strong>User Management</strong> — Admin tools for creating, editing, and managing user accounts.</li>
                <li><strong>Role-Based Access Control</strong> — Granular permissions for different user roles.</li>
                <li><strong>Audit Trail</strong> — Every action logged with timestamps, user info, and IP addresses.</li>
            </ul>

            <div style="background:var(--color-primary-50);border-left:3px solid var(--color-primary);padding:var(--sp-5) var(--sp-6);border-radius:0 var(--radius-md) var(--radius-md) 0;">
                <p style="font-size:var(--text-base);color:var(--color-text-secondary);line-height:var(--leading-relaxed);margin:0;font-style:italic;">
                    R-DEIP represents a commitment to digital governance — ensuring every citizen's estate and inheritance rights are protected through transparent, secure, and accessible technology.
                </p>
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
