<?php/**
 * Toast notification partial.
 * Renders flash messages as toast-style notifications.
 * Used as a helper partial — the main layout (layouts/app.php) handles
 * flash message display via alert divs. This partial is available for
 * optional use in AJAX responses or custom toast implementations.
 */
$types = ['success', 'error', 'warning', 'info'];

foreach ($types as $type):
    if (Flash::has($type)):
        $message = Flash::get($type);
        $iconMap = [
            'success' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            'error'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            'warning' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'info'    => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        ];
?>
    <div class="toast toast-<?= e($type) ?>" role="alert">
        <span class="toast-icon"><?= $iconMap[$type] ?? '' ?></span>
        <span class="toast-message"><?= e($message) ?></span>
        <button type="button" class="toast-close" aria-label="Close">&times;</button>
    </div>
<?php
    endif;
endforeach;
?>