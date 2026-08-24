<?php
$total_users = (int)($total_users ?? 0); 
$active_users = (int)($active_users ?? 0); 
$total_departments = (int)($total_departments ?? 0); 
$recent_audit_logs = $recent_audit_logs ?? []; 
$recent_login_logs = $recent_login_logs ?? []; 
$security_events = 0; 
foreach ($recent_audit_logs as $log) { if (isset($log['action']) && stripos($log['action'], 'login') !== false) { $security_events++; } } 
$currentUser = user(); 
$firstName = $currentUser ? e($currentUser['first_name'] ?? 'User') : 'User'; 
$pageTitle = 'Dashboard'; 
$breadcrumbItems = [['label' => 'Home', 'url' => url('dashboard')], ['label' => 'Dashboard']]; 

$greeting = 'Welcome back';
$hour = (int)date('G');
if ($hour >= 5 && $hour < 12) $greeting = 'Good morning';
elseif ($hour >= 12 && $hour < 17) $greeting = 'Good afternoon';
elseif ($hour >= 17 && $hour < 21) $greeting = 'Good evening';
?>

<style>
.dashboard-superadmin .card,
.dashboard-superadmin .stat-card { transition: box-shadow var(--duration-base), transform var(--duration-base); }
.dashboard-superadmin .card:hover,
.dashboard-superadmin .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
</style>

<div class="dashboard-superadmin">
<div class="dashboard-welcome" style="padding:var(--sp-8) 0 var(--sp-6);margin-bottom:var(--sp-8);">
    <h1 style="font-size:var(--text-2xl);font-weight:700;margin:0 0 var(--sp-2) 0;letter-spacing:-0.025em;"><?= $greeting ?>, <?= $firstName ?></h1>
    <p style="font-size:var(--text-base);color:var(--color-text-secondary);margin:0 0 var(--sp-3) 0;line-height:1.6;">Here is your system overview.</p>
    <span class="dashboard-welcome__role">Super Administrator</span>
</div>

<div class="dashboard-grid">
    <div class="stat-card blue">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $total_users ?></span>
            <span class="stat-card-label">Total Users</span>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $active_users ?></span>
            <span class="stat-card-label">Active Users</span>
        </div>
    </div>

    <div class="stat-card amber">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $total_departments ?></span>
            <span class="stat-card-label">Departments</span>
        </div>
    </div>

    <div class="stat-card red">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $security_events ?></span>
            <span class="stat-card-label">Security Events</span>
        </div>
    </div>
</div>

<div class="dashboard-grid mt-4">
    <div class="card" style="grid-column:span 2;">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <span>Recent Audit Activity</span>
            <?php if (can('audit.view')): ?>
            <a href="<?= url('audit') ?>" class="btn btn--ghost btn--sm">View All</a>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($recent_audit_logs)): ?>
                <div class="empty-state" style="padding:var(--sp-12) var(--sp-6);">
                    <div style="width:56px;height:56px;border-radius:var(--radius-xl);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-5);color:var(--color-text-light);opacity:0.5;">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <p class="text-muted" style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:500;">No audit data available yet</p>
                    <p style="font-size:var(--text-xs);color:var(--color-text-light);margin:0;">Audit trails will be recorded as actions are performed across the system</p>
                </div>
            <?php else: ?>
                <div class="table-container" style="border:none;border-radius:0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_audit_logs, 0, 10) as $log): ?>
                            <tr>
                                <td style="white-space:nowrap;"><?= e(format_datetime($log['created_at'] ?? '')) ?></td>
                                <td><?= e($log['user_name'] ?? 'System') ?></td>
                                <td><span class="badge badge-info"><?= e($log['action'] ?? 'N/A') ?></span></td>
                                <td><?= e($log['module'] ?? 'N/A') ?></td>
                                <td class="text-muted"><?= e(truncate($log['description'] ?? '', 60)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <span>Login Activity</span>
            <?php if (can('audit.view')): ?>
            <a href="<?= url('audit') ?>" class="btn btn--ghost btn--sm">View All</a>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($recent_login_logs)): ?>
                <div class="empty-state" style="padding:var(--sp-12) var(--sp-6);">
                    <div style="width:56px;height:56px;border-radius:var(--radius-xl);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-5);color:var(--color-text-light);opacity:0.5;">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    </div>
                    <p class="text-muted" style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:500;">No login data available yet</p>
                    <p style="font-size:var(--text-xs);color:var(--color-text-light);margin:0;">Login events will be tracked as users access the platform</p>
                </div>
            <?php else: ?>
                <div class="table-container" style="border:none;border-radius:0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_login_logs, 0, 8) as $log): ?>
                            <?php $status = strtolower($log['status'] ?? ''); $isSuccess = ($status === 'success'); ?>
                            <tr>
                                <td class="text-muted" style="white-space:nowrap;"><?= e(time_ago($log['created_at'] ?? '')) ?></td>
                                <td><?= e($log['user_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($isSuccess): ?>
                                        <span class="badge badge-success"><?= e(ucfirst($status)) ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><?= e(ucfirst($status)) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>