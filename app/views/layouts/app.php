<?php
/** @var string $pageTitle */ /** @var string $content */

$authUser = user();
$userFirstName = $authUser ? e($authUser['first_name'] ?? 'User') : 'User';
$userLastName = $authUser ? e($authUser['last_name'] ?? '') : '';
$userFullName = trim("$userFirstName $userLastName");
if (empty($userFullName)) $userFullName = 'User';

$primaryRole = $authUser ? auth()->primaryRole() : null;
$userRoleSlug = $primaryRole ? e($primaryRole['slug'] ?? 'citizen') : 'citizen';
$userRoleName = $primaryRole ? e($primaryRole['name'] ?? 'User') : 'User';

$currentPage = $pageTitle ?? 'Dashboard';
$isAdmin = auth()->hasRole('super_admin', 'administrator');
$isSuperAdmin = auth()->hasRole('super_admin');

$breadcrumbItems = $breadcrumbItems ?? [['label' => 'Home', 'url' => url('dashboard')]];

// Flash messages
$flashMessages = Flash::all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'R-DEIP') ?> | R-DEIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="session-lifetime" content="<?= e(config('session.lifetime') ?? 7200) ?>">
    <style>body { font-family: 'Inter', var(--font-sans); }</style>
</head>
<body>

<?php if (!empty($flashMessages['success'])): ?>
    <?php foreach ($flashMessages['success'] as $msg): ?>
    <div class="alert alert-success" role="alert"><?= e($msg) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($flashMessages['error'])): ?>
    <?php foreach ($flashMessages['error'] as $msg): ?>
    <div class="alert alert-error" role="alert"><?= e($msg) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($flashMessages['warning'])): ?>
    <?php foreach ($flashMessages['warning'] as $msg): ?>
    <div class="alert alert-warning" role="alert"><?= e($msg) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($flashMessages['info'])): ?>
    <?php foreach ($flashMessages['info'] as $msg): ?>
    <div class="alert alert-info" role="alert"><?= e($msg) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div id="toast-container"></div>

<div class="app-layout">
    <!-- Sidebar -->
    <aside class="app-sidebar" id="app-sidebar">
        <div class="sidebar-brand" style="padding:var(--sp-5) var(--sp-5);">
            <a href="<?= url('dashboard') ?>" class="sidebar-brand-link" style="gap:var(--sp-3);">
                <div style="width:38px;height:38px;background:var(--color-primary);border-radius:var(--radius-md);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(79,70,229,0.25);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <span class="sidebar-brand-text">R-DEIP</span>
                    <span class="sidebar-brand-subtitle">Administration</span>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav" aria-label="Main navigation" style="padding:var(--sp-3) var(--sp-3);gap:var(--sp-1);">
            <div class="sidebar-section-title" style="padding:var(--sp-3) var(--sp-3) var(--sp-1);">Main</div>

            <a href="<?= url('dashboard') ?>" class="sidebar-link <?= ($currentPage === 'Dashboard' || str_contains($currentPage, 'Dashboard')) ? 'active' : '' ?>" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="4" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="11" width="7" height="10" rx="1"/></svg>
                <span>Dashboard</span>
            </a>

            <?php if ($isAdmin): ?>
            <a href="<?= url('users') ?>" class="sidebar-link <?= ($currentPage === 'Users Management') ? 'active' : '' ?>" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Users</span>
            </a>

            <a href="<?= url('audit') ?>" class="sidebar-link <?= ($currentPage === 'Audit Logs') ? 'active' : '' ?>" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Audit Logs</span>
            </a>
            <?php endif; ?>

            <a href="<?= url('profile') ?>" class="sidebar-link <?= ($currentPage === 'My Profile') ? 'active' : '' ?>" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>My Profile</span>
            </a>

            <div class="sidebar-section-title" style="margin-top: 1.5rem;padding:var(--sp-3) var(--sp-3) var(--sp-1);">Upcoming</div>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Estate Management</span>
                <span class="sidebar-badge">Phase 4</span>
            </a>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Family &amp; Beneficiaries</span>
                <span class="sidebar-badge">Phase 5</span>
            </a>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Digital Will</span>
                <span class="sidebar-badge">Phase 6</span>
            </a>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Court &amp; Legal</span>
                <span class="sidebar-badge">Phase 7</span>
            </a>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <span>Government Admin</span>
                <span class="sidebar-badge">Phase 8</span>
            </a>

            <a href="#" class="sidebar-link disabled" onclick="return false;" style="border-radius:var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                <span>AI &amp; Analytics</span>
                <span class="sidebar-badge">Phase 9</span>
            </a>
        </nav>

        <div class="sidebar-footer" style="padding:var(--sp-4) var(--sp-5);gap:var(--sp-2);">
            <div class="sidebar-user-info">
                <div class="sidebar-user-avatar">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="sidebar-user-details">
                    <span class="sidebar-user-name"><?= e($userFullName) ?></span>
                    <span class="sidebar-user-role"><?= e($userRoleName) ?></span>
                </div>
            </div>
            <form action="<?= url('logout') ?>" method="POST" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" class="sidebar-link sidebar-logout" style="width:100%;border:none;cursor:pointer;text-align:left;font-size:inherit;border-radius:var(--radius-md);">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="app-main">
        <header class="app-topbar">
            <div class="topbar-left">
                <button class="topbar-toggle" id="sidebar-toggle" type="button" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>

                <?php if (!empty($breadcrumbItems) && count($breadcrumbItems) > 0): ?>
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    <?php foreach ($breadcrumbItems as $i => $item): ?>
                        <?php if ($i === array_key_last($breadcrumbItems)): ?>
                            <span class="breadcrumb-item active" aria-current="page"><?= e($item['label']) ?></span>
                        <?php else: ?>
                            <a href="<?= e($item['url'] ?? '#') ?>" class="breadcrumb-item"><?= e($item['label']) ?></a>
                            <span class="breadcrumb-separator" aria-hidden="true">/</span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
                <?php endif; ?>
            </div>

            <div class="topbar-right">
                <div class="nav-dropdown" id="user-dropdown">
                    <button class="topbar-user" type="button" aria-expanded="false" aria-haspopup="true">
                        <span class="topbar-user-icon" style="width:36px;height:36px;">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="topbar-user-info">
                            <span class="topbar-user-name"><?= e($userFirstName) ?></span>
                            <span class="topbar-user-role"><?= e($userRoleName) ?></span>
                        </span>
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="nav-dropdown-menu" id="user-dropdown-menu">
                        <a href="<?= url('profile') ?>" class="nav-dropdown-item">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            My Profile
                        </a>
                        <div class="nav-dropdown-divider"></div>
                        <form action="<?= url('logout') ?>" method="POST" style="margin:0;">
                            <?= csrf_field() ?>
                            <button type="submit" class="nav-dropdown-item nav-dropdown-item--danger" style="width:100%;border:none;cursor:pointer;text-align:left;font-size:inherit;">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:0.5rem;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="app-content">
            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
