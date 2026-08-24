<?php
$assignedWorkCount = (int)($assignedWorkCount ?? 0); 
$notificationsCount = (int)($notificationsCount ?? 0); 
$recent_activities = $recent_activities ?? []; 
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
.dashboard-officer .card,
.dashboard-officer .stat-card { transition: box-shadow var(--duration-base), transform var(--duration-base); }
.dashboard-officer .card:hover,
.dashboard-officer .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
</style>

<div class="dashboard-officer">
<div class="dashboard-welcome" style="padding:var(--sp-8) 0 var(--sp-6);margin-bottom:var(--sp-8);">
    <h1 style="font-size:var(--text-2xl);font-weight:700;margin:0 0 var(--sp-2) 0;letter-spacing:-0.025em;"><?= $greeting ?>, <?= $firstName ?></h1>
    <p style="font-size:var(--text-base);color:var(--color-text-secondary);margin:0 0 var(--sp-3) 0;line-height:1.6;">This is your workspace overview.</p>
    <span class="dashboard-welcome__role">Government Officer</span>
</div>

<div class="dashboard-grid">
    <div class="stat-card blue">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $assignedWorkCount ?></span>
            <span class="stat-card-label">Assigned Work</span>
        </div>
    </div>

    <div class="stat-card amber">
        <div class="stat-card-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="stat-card-info">
            <span class="stat-card-value"><?= $notificationsCount ?></span>
            <span class="stat-card-label">Notifications</span>
        </div>
    </div>
</div>

<div class="dashboard-grid mt-4">
    <div class="card" style="grid-column:span 2;">
        <div class="card-header">Upcoming Features</div>
        <div class="card-body">
            <div class="alert alert-info">
                <div style="display:flex;align-items:flex-start;gap:var(--sp-4);">
                    <div style="width:40px;height:40px;border-radius:var(--radius-lg);background:rgba(59,130,246,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--color-info, #3b82f6);"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <div style="min-width:0;">
                        <strong style="display:block;margin-bottom:var(--sp-1);font-size:var(--text-sm);font-weight:600;">Phase 2 Coming Soon</strong>
                        <p class="text-muted" style="font-size:var(--text-sm);margin:0;line-height:1.6;">Death Registration and Citizen Management modules will provide your workflow tools. These features are currently under development.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Your Recent Activity</div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($recent_activities)): ?>
                <div class="empty-state" style="padding:var(--sp-12) var(--sp-6);">
                    <div style="width:56px;height:56px;border-radius:var(--radius-xl);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-5);color:var(--color-text-light);opacity:0.5;">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <p class="text-muted" style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:500;">No recent activity</p>
                    <p style="font-size:var(--text-xs);color:var(--color-text-light);margin:0;">Your actions will be logged here as you use the platform</p>
                </div>
            <?php else: ?>
                <div class="table-container" style="border:none;border-radius:0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_activities, 0, 10) as $activity): ?>
                            <tr>
                                <td class="text-muted" style="white-space:nowrap;"><?= e(time_ago($activity['created_at'] ?? '')) ?></td>
                                <td><span class="badge badge-info"><?= e($activity['action'] ?? 'N/A') ?></span></td>
                                <td class="text-muted"><?= e(truncate($activity['description'] ?? '', 60)) ?></td>
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