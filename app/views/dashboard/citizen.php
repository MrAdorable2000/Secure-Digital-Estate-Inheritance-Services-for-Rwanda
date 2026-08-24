<?php
$profileCompleteness = (int)($profileCompleteness ?? 0); 
$accountStatus = $accountStatus ?? 'active'; 
$emailVerified = !empty($emailVerified); 
$currentUser = user(); 
$firstName = $currentUser ? e($currentUser['first_name'] ?? 'User') : 'User'; 
$pageTitle = 'Dashboard'; 
$breadcrumbItems = [['label' => 'Home', 'url' => url('dashboard')], ['label' => 'Dashboard']]; 
$statusBadgeClass = 'badge-success'; 
if (strtolower($accountStatus) === 'pending') { $statusBadgeClass = 'badge-warning'; } 
elseif (strtolower($accountStatus) === 'suspended') { $statusBadgeClass = 'badge-danger'; } 
elseif (strtolower($accountStatus) === 'inactive') { $statusBadgeClass = 'badge-warning'; } 

$greeting = 'Welcome';
$hour = (int)date('G');
if ($hour >= 5 && $hour < 12) $greeting = 'Good morning';
elseif ($hour >= 12 && $hour < 17) $greeting = 'Good afternoon';
elseif ($hour >= 17 && $hour < 21) $greeting = 'Good evening';
?>

<style>
.dashboard-citizen .card { transition: box-shadow var(--duration-base), transform var(--duration-base); }
.dashboard-citizen .card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
.dashboard-citizen .card[style*="pointer-events:none"] { pointer-events: none; }
.dashboard-citizen .card[style*="pointer-events:none"]:hover { box-shadow: none; transform: none; }
</style>

<div class="dashboard-citizen">
<div class="dashboard-welcome" style="padding:var(--sp-8) 0 var(--sp-6);margin-bottom:var(--sp-8);">
    <h1 style="font-size:var(--text-2xl);font-weight:700;margin:0 0 var(--sp-2) 0;letter-spacing:-0.025em;"><?= $greeting ?>, <?= $firstName ?></h1>
    <p style="font-size:var(--text-base);color:var(--color-text-secondary);margin:0 0 var(--sp-3) 0;line-height:1.6;">Welcome to your R-DEIP workspace.</p>
    <span class="dashboard-welcome__role">Citizen</span>
</div>

<div class="dashboard-grid">
    <!-- Account Status -->
    <div class="card">
        <div class="card-header">Account Status</div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:var(--sp-5);">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="text-muted" style="font-size:var(--text-sm);">Status</span>
                    <span class="badge <?= $statusBadgeClass ?>"><?= e(ucfirst($accountStatus)) ?></span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="text-muted" style="font-size:var(--text-sm);">Email Verified</span>
                    <?php if ($emailVerified): ?>
                        <span class="badge badge-success">Verified</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Not Verified</span>
                    <?php endif; ?>
                </div>

                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--sp-2);">
                        <span class="text-muted" style="font-size:var(--text-sm);">Profile Completeness</span>
                        <strong style="font-size:var(--text-sm);"><?= $profileCompleteness ?>%</strong>
                    </div>
                    <div style="background:var(--bg-subtle);border-radius:var(--radius-full);height:6px;overflow:hidden;">
                        <div style="background:<?= $profileCompleteness >= 80 ? 'var(--color-accent)' : ($profileCompleteness >= 40 ? '#d97706' : '#c62828') ?>;height:100%;width:<?= $profileCompleteness ?>%;border-radius:var(--radius-full);transition:width 0.3s ease;"></div>
                    </div>
                    <?php if ($profileCompleteness < 100): ?>
                        <a href="<?= url('profile') ?>" class="text-muted" style="font-size:var(--text-xs);display:inline-block;margin-top:var(--sp-2);">Complete your profile &rarr;</a>
                    <?php endif; ?>
                </div>

                <a href="<?= url('profile') ?>" class="btn btn--secondary btn--sm" style="margin-top:var(--sp-2);">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Services Overview -->
    <div class="card" style="grid-column:span 2;">
        <div class="card-header">Upcoming Services</div>
        <div class="card-body">
            <p class="text-muted" style="font-size:var(--text-sm);margin-bottom:var(--sp-5);line-height:1.6;">The following services will become available as R-DEIP progresses through its implementation phases.</p>

            <div class="dashboard-grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:var(--sp-4);">
                <div class="card" style="opacity:0.45;pointer-events:none;filter:saturate(0.3);">
                    <div class="card-body" style="padding:var(--sp-5);text-align:center;">
                        <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-3);color:var(--color-text-light);">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <h3 style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:600;">Estate Management</h3>
                        <p class="text-muted" style="font-size:var(--text-xs);margin:0 0 var(--sp-3) 0;">Manage estate assets</p>
                        <span class="badge badge-warning" style="font-size:var(--text-xs);">Phase 4</span>
                    </div>
                </div>

                <div class="card" style="opacity:0.45;pointer-events:none;filter:saturate(0.3);">
                    <div class="card-body" style="padding:var(--sp-5);text-align:center;">
                        <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-3);color:var(--color-text-light);">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <h3 style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:600;">Digital Will</h3>
                        <p class="text-muted" style="font-size:var(--text-xs);margin:0 0 var(--sp-3) 0;">Create digital wills</p>
                        <span class="badge badge-warning" style="font-size:var(--text-xs);">Phase 6</span>
                    </div>
                </div>

                <div class="card" style="opacity:0.45;pointer-events:none;filter:saturate(0.3);">
                    <div class="card-body" style="padding:var(--sp-5);text-align:center;">
                        <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:var(--bg-subtle);display:inline-flex;align-items:center;justify-content:center;margin-bottom:var(--sp-3);color:var(--color-text-light);">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h3 style="font-size:var(--text-sm);margin:0 0 var(--sp-1) 0;font-weight:600;">Inheritance Cases</h3>
                        <p class="text-muted" style="font-size:var(--text-xs);margin:0 0 var(--sp-3) 0;">Track inheritance cases</p>
                        <span class="badge badge-warning" style="font-size:var(--text-xs);">Phase 7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
