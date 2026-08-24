<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact - R-DEIP</title>
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
            <h1 style="color:#fff;">Contact</h1>
            <p style="color:rgba(255,255,255,0.7);max-width:560px;margin:var(--sp-3) auto 0;font-size:var(--text-md);">Get in touch with the R-DEIP team.</p>
        </div>
    </section>

    <!-- Content -->
    <section class="section reveal">
        <div class="container container--narrow">
            <div class="card">
                <div class="card-body" style="padding:var(--sp-8);">
                    <h2 style="margin-bottom:var(--sp-5);">Send a Message</h2>
                    <form method="POST" action="<?php echo url('contact'); ?>">
                        <?php echo csrf_field(); ?>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--sp-4);">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="Your name" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="How can we help?" required>
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-textarea" placeholder="Your message..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary">Send Message</button>
                    </form>
                </div>
            </div>

            <div style="margin-top:var(--sp-8);text-align:center;">
                <h2 style="margin-bottom:var(--sp-4);">Other Ways to Reach Us</h2>
                <p class="text-muted">info@rdeip.rw &nbsp;&middot;&nbsp; Kigali, Rwanda</p>
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
