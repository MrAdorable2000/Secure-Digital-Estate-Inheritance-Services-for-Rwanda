<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>R-DEIP — Rwanda Digital Estate &amp; Inheritance Platform</title>
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

    <?php if (\Flash::has('error')): ?>
        <?php foreach (\Flash::get('error') as $msg): ?>
            <div class="alert alert-error"><?php echo e($msg); ?></div>
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

    <!-- ===== HERO ===== -->
    <section class="hero reveal">
        <div class="container">
            <div class="hero__inner">
                <div class="hero__content">
                    <span class="hero__eyebrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
                        Rwanda’s Digital Estate Platform
                    </span>

                    <h1 class="hero__heading">Secure Digital Estate &amp; Inheritance Services for Rwanda</h1>

                    <p class="hero__description">A trusted digital foundation for managing personal, family and inheritance-related information with security, transparency and accountability.</p>

                    <div class="hero__actions">
                        <a href="<?php echo url('register'); ?>" class="btn btn--primary btn--lg">Get Started <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m0 0L13 6m6 6l-6 6"/></svg></a>
                        <a href="#how-it-works" class="btn btn--secondary btn--lg">Explore How It Works</a>
                    </div>

                    <div class="hero__trust-card">
                        <div class="hero__trust-card-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        </div>
                        <div class="hero__trust-card-text">
                            <strong>Secure Digital Service</strong>
                            Phase 1 — Authentication, roles &amp; audit logging
                        </div>
                    </div>
                </div>

                <div class="hero__media">
                    <?php echo image_tag('hero/hero-family.webp', 'Rwandan family', ['hero/hero-family-sm.webp', 'placeholders/hero-placeholder.svg'], 640, 480, false, ''); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TRUST STRIP ===== -->
    <section class="trust-strip reveal">
        <div class="container">
            <div class="trust-strip__inner">
                <div class="trust-strip__item">
                    <div class="trust-strip__icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <span class="trust-strip__label">Security</span>
                        <span class="trust-strip__desc">Protected authentication</span>
                    </div>
                </div>
                <div class="trust-strip__item">
                    <div class="trust-strip__icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <span class="trust-strip__label">Access</span>
                        <span class="trust-strip__desc">Role-based services</span>
                    </div>
                </div>
                <div class="trust-strip__item">
                    <div class="trust-strip__icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <span class="trust-strip__label">Accountability</span>
                        <span class="trust-strip__desc">Full audit trails</span>
                    </div>
                </div>
                <div class="trust-strip__item">
                    <div class="trust-strip__icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <span class="trust-strip__label">Privacy</span>
                        <span class="trust-strip__desc">Secure data foundation</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SERVICES ===== -->
    <section class="section section--neutral" id="services">
        <div class="container">
            <div class="section__header reveal">
                <span class="section__label">Phase 1</span>
                <h2>Currently Available Services</h2>
                <p>Foundational capabilities that are live and ready for use.</p>
            </div>

            <div class="services-grid reveal-stagger">
                <div class="service-card">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
                    </div>
                    <h3>Secure Account Access</h3>
                    <p>Secure authentication and account management with email verification and password recovery.</p>
                    <span class="service-card__arrow">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m0 0L13 6m6 6l-6 6"/></svg></span>
                </div>

                <div class="service-card">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3>Role-Based Services</h3>
                    <p>Different access levels for citizens, government officers, and administrators with clear boundaries.</p>
                    <span class="service-card__arrow">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m0 0L13 6m6 6l-6 6"/></svg></span>
                </div>

                <div class="service-card">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <h3>Secure Records Foundation</h3>
                    <p>A secure foundation prepared for future estate and inheritance record workflows and document management.</p>
                    <span class="service-card__arrow">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m0 0L13 6m6 6l-6 6"/></svg></span>
                </div>

                <div class="service-card">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3>Transparent Administration</h3>
                    <p>Audit trails and accountable administrative actions for every operation on the platform.</p>
                    <span class="service-card__arrow">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m0 0L13 6m6 6l-6 6"/></svg></span>
                </div>

                <!-- Future services -->
                <div class="service-card service-card--future">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <h3>Estate Management</h3>
                    <p>Register, manage and track estate assets and property records.</p>
                </div>

                <div class="service-card service-card--future">
                    <div class="service-card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Court &amp; Legal Workflow</h3>
                    <p>Integration with court systems for legal processing of inheritance cases.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FAMILY / HUMAN SECTION ===== -->
    <section class="section section--warm" id="families">
        <div class="container">
            <div class="split-section reveal">
                <div class="split-section__image">
                    <?php echo image_tag('family/family-together.webp', 'Family gathered together', ['family/family-portrait.webp', 'placeholders/family.svg'], 640, 480, true, ''); ?>
                    <div class="split-section__float-card">
                        <div class="split-section__float-card-label">Built Around</div>
                        <div class="split-section__float-card-value">People &amp; Families</div>
                    </div>
                </div>
                <div class="split-section__text">
                    <span class="section__label">Built Around People</span>
                    <h2>Designed for families and the people they serve</h2>
                    <p>R-DEIP is built around the needs of Rwandan families. The platform provides a secure digital space for managing information that matters most — from personal records to future estate planning workflows.</p>
                    <p>Every design decision prioritises clarity, accessibility, and trust, so that families can engage with confidence and peace of mind.</p>

                    <div class="split-section__checks">
                        <div class="split-section__check">
                            <div class="split-section__check-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span>Secure by design</span>
                        </div>
                        <div class="split-section__check">
                            <div class="split-section__check-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span>Transparent processes</span>
                        </div>
                        <div class="split-section__check">
                            <div class="split-section__check-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span>Accessible to everyone</span>
                        </div>
                        <div class="split-section__check">
                            <div class="split-section__check-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span>Accountable governance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="section" id="how-it-works">
        <div class="container">
            <div class="section__header reveal">
                <span class="section__label">Getting Started</span>
                <h2>How It Works</h2>
                <p>Getting started is straightforward. Follow these steps to access the platform.</p>
            </div>

            <div class="steps-grid reveal-stagger">
                <div class="step-card">
                    <div class="step-card__number">01</div>
                    <h3>Create an Account</h3>
                    <p>Register with your email and set up a secure password.</p>
                </div>

                <div class="step-card">
                    <div class="step-card__number">02</div>
                    <h3>Verify Your Email</h3>
                    <p>Confirm your email address to activate your account.</p>
                </div>

                <div class="step-card">
                    <div class="step-card__number">03</div>
                    <h3>Access Role-Based Services</h3>
                    <p>Use the services available to your assigned role.</p>
                </div>

                <div class="step-card">
                    <div class="step-card__number">04</div>
                    <h3>Manage Your Workspace</h3>
                    <p>Update your profile and manage your digital records securely.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== RWANDA SECTION ===== -->
    <section class="section section--neutral" id="about-rwanda">
        <div class="container">
            <div class="split-section reverse reveal">
                <div class="split-section__image">
                    <?php echo image_tag('city/kigali-skyline.webp', 'Kigali city skyline', ['city/kigali-district.webp', 'placeholders/hero-placeholder.svg'], 640, 480, true, ''); ?>
                </div>
                <div class="split-section__text">
                    <span class="section__label">Rwanda-First</span>
                    <h2>Designed for Rwanda. Built for tomorrow.</h2>
                    <p>R-DEIP is architected with Rwanda at its centre. The platform addresses local needs in estate and inheritance management while maintaining the flexibility to support additional jurisdictions in the future.</p>
                    <p>The design draws from Rwanda’s commitment to digital transformation and accountable governance, providing a foundation that can evolve alongside the country’s digital public-service infrastructure.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== GOVERNMENT / PROFESSIONAL SECTION ===== -->
    <section class="section section--warm" id="government">
        <div class="container">
            <div class="split-section reveal">
                <div class="split-section__image">
                    <?php echo image_tag('government/professional-woman.webp', 'Professional woman working in office', ['legal/lawyer-documents.webp', 'placeholders/government.svg'], 640, 480, true, ''); ?>
                </div>
                <div class="split-section__text">
                    <span class="section__label">Accountable Administration</span>
                    <h2>Clear workflows. Accountable access. Secure administration.</h2>
                    <p>The platform provides structured access for government officers and administrators, with clear role boundaries and comprehensive audit logging. Every action is recorded, creating a transparent chain of accountability.</p>
                    <p>Role-based access control ensures that sensitive operations are restricted to authorised personnel, while citizens maintain full visibility into their own records.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECURITY SECTION ===== -->
    <section class="section section--dark">
        <div class="container">
            <div class="section__header reveal">
                <span class="section__label" style="color:rgba(255,255,255,0.5);background:rgba(255,255,255,0.08);">Security</span>
                <h2 style="color:#fff;">Security is part of the foundation</h2>
                <p>Every layer of the platform is designed with security as a core requirement, not an afterthought.</p>
            </div>

            <div class="security-grid reveal-stagger">
                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <h3>Secure Authentication</h3>
                    <p>Password-based authentication with email verification and session management.</p>
                </div>

                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <h3>Role-Based Authorization</h3>
                    <p>Granular permissions ensure users only access what their role allows.</p>
                </div>

                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>CSRF Protection</h3>
                    <p>Cross-site request forgery tokens protect every form submission.</p>
                </div>

                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3>Audit Logging</h3>
                    <p>Every significant action is logged with user, timestamp and details.</p>
                </div>

                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <h3>Secure Storage Architecture</h3>
                    <p>Architecture prepared for encrypted document and record storage.</p>
                </div>

                <div class="security-item">
                    <div class="security-item__icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <h3>Session Management</h3>
                    <p>Controlled session lifetimes with timeout warnings and secure handling.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FINAL CTA ===== -->
    <section class="cta-section reveal">
        <div class="container">
            <div class="cta-inner">
                <h2>Build a more secure digital foundation for your family’s future.</h2>
                <p>Join R-DEIP today and be part of Rwanda’s digital transformation in estate and inheritance management.</p>
                <div class="cta-actions">
                    <a href="<?php echo url('register'); ?>" class="btn btn--primary btn--lg">Get Started</a>
                    <a href="<?php echo url('about'); ?>" class="btn btn--secondary btn--lg">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
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
