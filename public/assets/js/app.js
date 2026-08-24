/* ==========================================================================
   R-DEIP — Rwanda Digital E-Infrastructure Platform
   Main Application JavaScript
   Vanilla JS · Zero Dependencies · Premium Micro-Interactions
   ========================================================================== */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ========================================================================
       0.  Reduced-Motion Preference (shared across all modules)
       ======================================================================== */
    var prefersReducedMotion = window.matchMedia &&
      window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ========================================================================
       1.  Mobile Hamburger Menu
       Toggles .site-nav__mobile, locks body scroll, closes on link click
       and on ESC key. Used on homepage, about, services, how-it-works,
       contact pages.
       ======================================================================== */
    (function initMobileHamburger() {
      var hamburger  = document.querySelector('.site-nav__hamburger');
      var mobileMenu = document.querySelector('.site-nav__mobile');
      if (!hamburger || !mobileMenu) return;

      var isOpen = false;

      function openMenu() {
        isOpen = true;
        hamburger.classList.add('is-active');
        hamburger.setAttribute('aria-expanded', 'true');
        mobileMenu.classList.add('is-open');
        mobileMenu.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      }

      function closeMenu() {
        isOpen = false;
        hamburger.classList.remove('is-active');
        hamburger.setAttribute('aria-expanded', 'false');
        mobileMenu.classList.remove('is-open');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      hamburger.addEventListener('click', function () {
        isOpen ? closeMenu() : openMenu();
      });

      // Close when any nav link is tapped
      mobileMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
      });

      // Close on Escape
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeMenu();
      });
    })();

    /* ========================================================================
       2.  Dashboard Sidebar Toggle
       Shows/hides the sidebar on mobile viewports with an overlay backdrop.
       Selectors: #sidebar-toggle, .sidebar, .sidebar-overlay
       ======================================================================== */
    (function initSidebarToggle() {
      var toggleBtn = document.querySelector('#sidebar-toggle');
      var sidebar   = document.querySelector('.sidebar');
      var overlay   = document.querySelector('.sidebar-overlay');
      if (!toggleBtn || !sidebar) return;

      // Create overlay dynamically if missing
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.setAttribute('aria-hidden', 'true');
        document.body.appendChild(overlay);
      }

      function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        toggleBtn.setAttribute('aria-expanded', 'true');
      }

      function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        toggleBtn.setAttribute('aria-expanded', 'false');
      }

      toggleBtn.addEventListener('click', function () {
        sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
      });

      overlay.addEventListener('click', closeSidebar);

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
          closeSidebar();
        }
      });
    })();

    /* ========================================================================
       3.  User Dropdown Menu
       Click-to-toggle the top-right user dropdown in the dashboard.
       Selectors: #user-dropdown, .nav-dropdown-menu
       ======================================================================== */
    (function initUserDropdown() {
      var trigger = document.querySelector('#user-dropdown');
      var menu    = document.querySelector('.nav-dropdown-menu');
      if (!trigger || !menu) return;

      var isOpen = false;

      function toggleDropdown() {
        isOpen = !isOpen;
        menu.classList.toggle('is-open', isOpen);
        menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }

      function closeDropdown() {
        isOpen = false;
        menu.classList.remove('is-open');
        menu.setAttribute('aria-hidden', 'true');
        trigger.setAttribute('aria-expanded', 'false');
      }

      trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleDropdown();
      });

      // Close on outside click
      document.addEventListener('click', function (e) {
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
          closeDropdown();
        }
      });

      // Close on Escape
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeDropdown();
      });
    })();

    /* ========================================================================
       4.  Password Visibility Toggle
       Swaps input type between password/text and toggles the eye icon.
       Selector: .password-toggle
       ======================================================================== */
    (function initPasswordToggle() {
      var buttons = document.querySelectorAll('.password-toggle');
      if (!buttons.length) return;

      buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var wrapper = btn.closest('.password-wrapper') || btn.parentElement;
          var input   = wrapper.querySelector('input');
          if (!input) return;

          var isHidden = input.type === 'password';
          input.type = isHidden ? 'text' : 'password';

          // Swap icon — assumes an <svg> or <i> inside the button
          var icon = btn.querySelector('svg') || btn.querySelector('i');
          if (icon) {
            if (icon.tagName.toLowerCase() === 'svg') {
              // Toggle a data attribute or class on the SVG
              icon.classList.toggle('eye-open', isHidden);
              icon.classList.toggle('eye-closed', !isHidden);
            } else {
              // Fallback: swap icon class names
              icon.classList.toggle('fa-eye', !isHidden);
              icon.classList.toggle('fa-eye-slash', isHidden);
            }
          }

          btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
      });
    })();

    /* ========================================================================
       5.  Password Strength Indicator
       Real-time strength calculation based on length, character classes.
       Selectors: [data-password-strength], .password-strength-fill
       ======================================================================== */
    (function initPasswordStrength() {
      var fields = document.querySelectorAll('[data-password-strength]');
      if (!fields.length) return;

      fields.forEach(function (input) {
        var fill = input.closest('.form-group, .field')
          ? input.closest('.form-group, .field').querySelector('.password-strength-fill')
          : null;
        if (!fill) {
          // Fall back to sibling search
          fill = input.parentElement.querySelector('.password-strength-fill');
        }
        if (!fill) return;

        input.addEventListener('input', function () {
          var val   = input.value;
          var score = 0;
          var label = '';
          var width = '0%';

          if (val.length === 0) {
            fill.style.width = width;
            fill.className = 'password-strength-fill';
            fill.removeAttribute('data-strength');
            return;
          }

          // Scoring criteria (0–5)
          if (val.length >= 8)                              score++;
          if (/[a-z]/.test(val))                           score++;  // lowercase
          if (/[A-Z]/.test(val))                           score++;  // uppercase
          if (/[0-9]/.test(val))                           score++;  // number
          if (/[^a-zA-Z0-9]/.test(val))                   score++;  // special char

          // Map score to label, width, and CSS class
          if (score <= 1) {
            label = 'Weak';   width = '20%';
            fill.className = 'password-strength-fill strength-weak';
          } else if (score <= 2) {
            label = 'Fair';   width = '40%';
            fill.className = 'password-strength-fill strength-fair';
          } else if (score <= 3) {
            label = 'Good';   width = '60%';
            fill.className = 'password-strength-fill strength-good';
          } else if (score === 4) {
            label = 'Strong'; width = '80%';
            fill.className = 'password-strength-fill strength-strong';
          } else {
            label = 'Very Strong'; width = '100%';
            fill.className = 'password-strength-fill strength-very-strong';
          }

          fill.style.width = width;
          fill.setAttribute('data-strength', label);
        });
      });
    })();

    /* ========================================================================
       6.  Toast Notification System
       Finds .alert elements NOT inside .auth-card, moves them into
       #toast-container as toast elements, auto-dismisses after 5 s.
       ======================================================================== */
    (function initToastNotifications() {
      var container = document.querySelector('#toast-container');
      if (!container) return;

      // Collect all .alert elements that are NOT descendants of .auth-card
      var alerts = document.querySelectorAll('.alert');
      var toasts  = [];

      alerts.forEach(function (alert) {
        // Skip if inside an auth card
        if (alert.closest('.auth-card')) return;

        // Determine toast type from existing classes
        var type = 'info';
        if (alert.classList.contains('alert-success')) type = 'success';
        else if (alert.classList.contains('alert-danger'))  type = 'error';
        else if (alert.classList.contains('alert-warning')) type = 'warning';
        else if (alert.classList.contains('alert-info'))    type = 'info';

        // Build toast element
        var toast = document.createElement('div');
        toast.className = 'toast toast--' + type;
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');

        // Copy the alert's inner text content
        toast.innerHTML = '<span class="toast__message">' + alert.innerHTML.trim() + '</span>' +
          '<button class="toast__close" aria-label="Dismiss">&times;</button>';

        // Remove original alert from the DOM
        if (alert.parentNode) alert.parentNode.removeChild(alert);

        container.appendChild(toast);
        toasts.push(toast);

        // Dismiss on close-button click
        toast.querySelector('.toast__close').addEventListener('click', function () {
          dismissToast(toast);
        });

        // Auto-dismiss after 5 seconds
        var timer = setTimeout(function () {
          dismissToast(toast);
        }, 5000);

        // Pause auto-dismiss on hover
        toast.addEventListener('mouseenter', function () {
          clearTimeout(timer);
        });
        toast.addEventListener('mouseleave', function () {
          timer = setTimeout(function () {
            dismissToast(toast);
          }, 3000);
        });
      });

      function dismissToast(toast) {
        if (toast.classList.contains('toast--removing')) return;
        toast.classList.add('toast--removing');
        toast.addEventListener('animationend', function () {
          if (toast.parentNode) toast.parentNode.removeChild(toast);
        });
      }
    })();

    /* ========================================================================
       7.  Smooth Scroll for Anchor Links
       Intercepts anchor links and scrolls smoothly — skipped entirely
       when the user prefers reduced motion.
       ======================================================================== */
    (function initSmoothScroll() {
      if (prefersReducedMotion) return;

      document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
          var href = link.getAttribute('href');
          if (href === '#' || href.length < 2) return;

          var target = document.querySelector(href);
          if (!target) return;

          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });

          // Update URL hash without jumping
          if (history.pushState) {
            history.pushState(null, null, href);
          }
        });
      });
    })();

    /* ========================================================================
       8.  Scroll Reveal (IntersectionObserver)
       Animates .reveal and .reveal-stagger elements into view.
       Completely skipped when prefers-reduced-motion is active.
       ======================================================================== */
    (function initScrollReveal() {
      if (prefersReducedMotion) {
        // Immediately make everything visible
        document.querySelectorAll('.reveal, .reveal-stagger').forEach(function (el) {
          el.classList.add('is-visible');
        });
        return;
      }

      var reveals = document.querySelectorAll('.reveal, .reveal-stagger');
      if (!reveals.length) return;

      // Stagger delay helper — reads index among siblings
      function getStaggerDelay(el) {
        if (!el.classList.contains('reveal-stagger')) return 0;
        var parent = el.parentElement;
        if (!parent) return 0;
        var siblings = parent.querySelectorAll(':scope > .reveal-stagger');
        var index = Array.prototype.indexOf.call(siblings, el);
        return index * 80; // 80 ms between each staggered item
      }

      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var delay = getStaggerDelay(entry.target);
            if (delay > 0) {
              entry.target.style.transitionDelay = delay + 'ms';
            }
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
      });

      reveals.forEach(function (el) {
        observer.observe(el);
      });
    })();

    /* ========================================================================
       9.  Form Validation UX
       On submit, uses the native Constraint Validation API. Adds .error
       class to invalid inputs and shows per-field validation messages.
       ======================================================================== */
    (function initFormValidation() {
      var forms = document.querySelectorAll('form[data-validate], form:not([data-no-validate])');
      if (!forms.length) return;

      forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
          // Only intercept if the browser would consider it invalid
          if (!form.checkValidity()) {
            e.preventDefault();

            // Clear previous errors
            form.querySelectorAll('.error').forEach(function (el) {
              el.classList.remove('error');
            });
            form.querySelectorAll('.field-error').forEach(function (el) {
              if (el.parentNode) el.parentNode.removeChild(el);
            });

            var firstInvalid = null;

            // Iterate over all invalid fields
            var invalids = form.querySelectorAll(':invalid');
            invalids.forEach(function (field) {
              field.classList.add('error');

              // Build a human-readable message from the validity state
              var message = field.validationMessage || 'This field is invalid.';

              // Try to map native messages to something friendlier
              if (field.validity.valueMissing)      message = 'This field is required.';
              else if (field.validity.typeMismatch)   message = 'Please enter a valid value.';
              else if (field.validity.tooShort)       message = 'Must be at least ' + field.minLength + ' characters.';
              else if (field.validity.tooLong)        message = 'Must be no more than ' + field.maxLength + ' characters.';
              else if (field.validity.patternMismatch) message = field.getAttribute('data-pattern-error') || 'Invalid format.';

              // Create or update the error message element
              var errorEl = field.parentElement.querySelector('.field-error');
              if (!errorEl) {
                errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                field.parentElement.appendChild(errorEl);
              }
              errorEl.textContent = message;

              // Track the first invalid field for focus
              if (!firstInvalid) firstInvalid = field;

              // Clear error on input
              field.addEventListener('input', function handler() {
                field.classList.remove('error');
                var err = field.parentElement.querySelector('.field-error');
                if (err && err.parentNode) err.parentNode.removeChild(err);
                field.removeEventListener('input', handler);
              });
            });

            // Focus the first invalid field
            if (firstInvalid) {
              firstInvalid.focus();
            }
          }
        });
      });
    })();

    /* ========================================================================
       10. Loading State on Form Submit
        Adds .loading class to the submit button and disables it.
        Automatically removes after 3 seconds as a safety fallback.
       ======================================================================== */
    (function initSubmitLoadingState() {
      var forms = document.querySelectorAll('form');
      if (!forms.length) return;

      forms.forEach(function (form) {
        var submitBtn = form.querySelector('[type="submit"]');
        if (!submitBtn) return;

        form.addEventListener('submit', function () {
          // Skip if the button is already loading (prevents double-fire)
          if (submitBtn.classList.contains('loading')) return;

          // Store original content so we can restore it
          submitBtn._originalHTML = submitBtn.innerHTML;
          submitBtn._originalDisabled = submitBtn.disabled;

          submitBtn.classList.add('loading');
          submitBtn.disabled = true;

          // Safety fallback: remove loading state after 3 seconds
          setTimeout(function () {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = !!submitBtn._originalDisabled;
            if (submitBtn._originalHTML) {
              submitBtn.innerHTML = submitBtn._originalHTML;
            }
          }, 3000);
        });
      });
    })();

    /* ========================================================================
       Public API — expose utility functions globally for inline / template use
       ======================================================================== */
    window.RDEIP = window.RDEIP || {};

  }); // end DOMContentLoaded

})(); // end IIFE
