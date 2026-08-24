<?php
/**
 * User Management List Page
 * 
 * Data variables:
 *   $users       - array of user records with role_slug, role_name
 *   $totalUsers  - total number of users
 *   $page        - current page number
 *   $perPage     - items per page
 *   $totalPages  - total number of pages
 *   $search      - current search query
 *   $statusFilter - current status filter
 */

$activeCount = 0;
$suspendedCount = 0;
foreach ($users as $u) {
    if (($u['status'] ?? '') === 'active') $activeCount++;
    if (($u['status'] ?? '') === 'suspended') $suspendedCount++;
}
?>

<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-between align-center mb-3">
        <div>
            <h1>User Management</h1>
            <p class="text-muted">Manage all system users, roles, and access</p>
        </div>
        <?php if (can('users.create')): ?>
        <a href="<?= url('users/create') ?>" class="btn btn-primary">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Create User
        </a>
        <?php endif; ?>
    </div>

    <!-- Flash Messages -->
    <?php if (Flash::has('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 1rem;"><?= e(Flash::get('success')) ?></div>
    <?php endif; ?>
    <?php if (Flash::has('error')): ?>
        <div class="alert alert-error" style="margin-bottom: 1rem;"><?= e(Flash::get('error')) ?></div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="d-flex gap-2 mb-3" style="flex-wrap: wrap;">
        <div class="card" style="flex: 1; min-width: 160px;">
            <div class="card-body text-center">
                <div style="font-size: 1.75rem; font-weight: 700; color: var(--color-primary, #3b82f6);"><?= e(number_format($totalUsers)) ?></div>
                <div class="text-muted">Total Users</div>
            </div>
        </div>
        <div class="card" style="flex: 1; min-width: 160px;">
            <div class="card-body text-center">
                <div style="font-size: 1.75rem; font-weight: 700; color: #10b981;"><?= e(number_format($activeCount)) ?></div>
                <div class="text-muted">Active</div>
            </div>
        </div>
        <div class="card" style="flex: 1; min-width: 160px;">
            <div class="card-body text-center">
                <div style="font-size: 1.75rem; font-weight: 700; color: #ef4444;"><?= e(number_format($suspendedCount)) ?></div>
                <div class="text-muted">Suspended</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <form method="GET" action="<?= url('users') ?>" class="card mb-3">
        <div class="card-body">
            <div class="d-flex gap-2" style="flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label class="form-label" for="search">Search</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-input"
                        placeholder="Search by name or email..."
                        value="<?= e($search ?? '') ?>"
                    />
                </div>
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="" <?= ($statusFilter ?? '') === '' ? 'selected' : '' ?>>All Statuses</option>
                        <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($statusFilter ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="suspended" <?= ($statusFilter ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <?php if (($search ?? '') !== '' || ($statusFilter ?? '') !== ''): ?>
                        <a href="<?= url('users') ?>" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <?php if (!empty($users)): ?>
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <?php
                        $status = $u['status'] ?? 'inactive';
                        $statusBadge = 'badge-info';
                        if ($status === 'active') $statusBadge = 'badge-success';
                        elseif ($status === 'pending') $statusBadge = 'badge-warning';
                        elseif ($status === 'suspended') $statusBadge = 'badge-danger';

                        $firstName = e($u['first_name'] ?? '');
                        $lastName  = e($u['last_name'] ?? '');
                        $fullName  = trim("$firstName $lastName");
                        $initials  = strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
                        $photoUrl  = !empty($u['profile_photo']) ? e($u['profile_photo']) : '';
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <?php if ($photoUrl): ?>
                                    <img src="<?= $photoUrl ?>" alt="<?= $fullName ?>" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
                                    <div style="display:none;width:36px;height:36px;border-radius:50%;background:var(--color-primary,#3b82f6);color:#fff;align-items:center;justify-content:center;font-size:0.8rem;font-weight:600;flex-shrink:0;"><?= $initials ?></div>
                                <?php else: ?>
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--color-primary,#3b82f6);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:600;flex-shrink:0;"><?= $initials ?></div>
                                <?php endif; ?>
                                <span><?= $fullName ?: '—' ?></span>
                            </div>
                        </td>
                        <td><?= e($u['email'] ?? '—') ?></td>
                        <td><?= e($u['phone'] ?? '—') ?></td>
                        <td><span class="badge"><?= e($u['role_name'] ?? $u['role_slug'] ?? '—') ?></span></td>
                        <td>
                            <span class="badge <?= $statusBadge ?>">
                                <?= e(ucfirst($status)) ?>
                            </span>
                        </td>
                        <td class="text-muted">
                            <?= !empty($u['last_login_at']) ? e(time_ago($u['last_login_at'])) : 'Never' ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php if (can('users.edit')): ?>
                                <a href="<?= url('users/' . $u['id'] . '/edit') ?>" class="btn btn-secondary btn-sm" title="Edit User">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <?php endif; ?>

                                <?php if ($status === 'active' && can('users.suspend')): ?>
                                <form method="POST" action="<?= url('users/' . $u['id'] . '/suspend') ?>" onsubmit="return confirm('Are you sure you want to suspend this user?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm" title="Suspend User">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        Suspend
                                    </button>
                                </form>
                                <?php elseif ($status === 'suspended' && can('users.activate')): ?>
                                <form method="POST" action="<?= url('users/' . $u['id'] . '/activate') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-primary btn-sm" title="Activate User">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        Activate
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
    <div class="pagination mt-3" style="justify-content: center;">
        <?php if (($page ?? 1) > 1): ?>
            <a href="<?= url('users?page=' . (($page ?? 1) - 1) . ($search ?? '' ? '&search=' . urlencode($search) : '') . ($statusFilter ?? '' ? '&status=' . urlencode($statusFilter) : '')) ?>" class="pagination-link">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php
                $queryParams = [];
                if (!empty($search))    $queryParams[] = 'search=' . urlencode($search);
                if (!empty($statusFilter)) $queryParams[] = 'status=' . urlencode($statusFilter);
                $href = url('users?page=' . $i . ($queryParams ? '&' . implode('&', $queryParams) : ''));
            ?>
            <?php if ($i === ($page ?? 1)): ?>
                <span class="pagination-link pagination-active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $href ?>" class="pagination-link"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if (($page ?? 1) < $totalPages: ?>
            <a href="<?= url('users?page=' . (($page ?? 1) + 1) . ($search ?? '' ? '&search=' . urlencode($search) : '') . ($statusFilter ?? '' ? '&status=' . urlencode($statusFilter) : '')) ?>" class="pagination-link">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty State -->
    <div class="empty-state mt-3">
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-muted, #9ca3af); margin-bottom: 1rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <h3>No users found</h3>
        <p class="text-muted">
            <?php if (($search ?? '') !== '' || ($statusFilter ?? '') !== ''): ?>
                No users match your current filters. Try adjusting your search or status filter.
            <?php else: ?>
                There are no users in the system yet.
                <?php if (can('users.create')): ?>
                    <a href="<?= url('users/create') ?>">Create the first user</a> to get started.
                <?php endif; ?>
            <?php endif; ?>
        </p>
    </div>
    <?php endif; ?>
</div>