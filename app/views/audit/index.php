<?php
/**
 * Audit Trail Page
 *
 * Data variables:
 *   $logs        - array of audit log records
 *   $total       - total number of matching logs
 *   $page        - current page number
 *   $totalPages  - total number of pages
 *   $modules     - distinct module values for filter dropdown
 *   $actions     - distinct action values for filter dropdown
 */
?>

<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-between align-center mb-3">
        <div>
            <h1>Audit Trail</h1>
            <p class="text-muted">Review all system activity and actions</p>
        </div>
        <div class="text-muted" style="font-size: 0.9rem;">
            <?= e(number_format($total)) ?> record<?= $total !== 1 ? 's' : '' ?> found
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="<?= url('/audit') ?>" class="card mb-3">
        <div class="card-body">
            <div class="d-flex gap-2" style="flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="module">Module</label>
                    <select id="module" name="module" class="form-select">
                        <option value="" <?= empty($_GET['module'] ?? null) ? 'selected' : '' ?>>All Modules</option>
                        <?php foreach ($modules as $m): ?>
                            <option value="<?= e($m['module']) ?>" <?= ($_GET['module'] ?? '') === $m['module'] ? 'selected' : '' ?>>
                                <?= e($m['module']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="action">Action</label>
                    <select id="action" name="action" class="form-select">
                        <option value="" <?= empty($_GET['action'] ?? null) ? 'selected' : '' ?>>All Actions</option>
                        <?php foreach ($actions as $a): ?>
                            <option value="<?= e($a['action']) ?>" <?= ($_GET['action'] ?? '') === $a['action'] ? 'selected' : '' ?>>
                                <?= e($a['action']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
                    <label class="form-label" for="date_from">Date From</label>
                    <input type="date" id="date_from" name="date_from" class="form-input" value="<?= e($_GET['date_from'] ?? '') ?>">
                </div>
                <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
                    <label class="form-label" for="date_to">Date To</label>
                    <input type="date" id="date_to" name="date_to" class="form-input" value="<?= e($_GET['date_to'] ?? '') ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Apply
                    </button>
                    <?php if (!empty($_GET['module']) || !empty($_GET['action']) || !empty($_GET['date_from']) || !empty($_GET['date_to'])): ?>
                        <a href="<?= url('/audit') ?>" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>

    <!-- Audit Logs Table -->
    <?php if (!empty($logs)): ?>
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <?php
                        $userName = trim(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? ''));
                        if (empty($userName)) $userName = 'System';
                        $timestamp = $log['created_at'] ?? '—';
                    ?>
                    <tr>
                        <td style="white-space: nowrap; font-size: 0.85rem; color: var(--color-gray-600, #4b5563);">
                            <span title="<?= e($timestamp) ?>"><?= e(time_ago($timestamp)) ?></span>
                        </td>
                        <td>
                            <span style="font-weight: 500;"><?= e($userName) ?></span>
                        </td>
                        <td>
                            <?php
                                $action = $log['action'] ?? '';
                                $actionBadge = 'badge-info';
                                if (strpos($action, 'create') !== false || strpos($action, 'login') !== false) $actionBadge = 'badge-success';
                                elseif (strpos($action, 'delete') !== false || strpos($action, 'suspend') !== false) $actionBadge = 'badge-danger';
                                elseif (strpos($action, 'update') !== false || strpos($action, 'edit') !== false) $actionBadge = 'badge-warning';
                            ?>
                            <span class="badge <?= $actionBadge ?>"><?= e(ucfirst(str_replace(['_', '.'], ' ', $action))) ?></span>
                        </td>
                        <td>
                            <span class="badge" style="background: var(--color-gray-100, #f3f4f6); color: var(--color-gray-700, #374151);">
                                <?= e(ucfirst($log['module'] ?? '—')) ?>
                            </span>
                        </td>
                        <td style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--color-gray-600, #4b5563); font-size: 0.9rem;">
                            <?= e($log['description'] ?? '—') ?>
                        </td>
                        <td style="font-family: monospace; font-size: 0.85rem; color: var(--color-gray-500, #6b7280);">
                            <?= e($log['ip_address'] ?? '—') ?>
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
        <?php
            $buildPageUrl = function($p) use ($page) {
                $q = [];
                $q[] = 'page=' . $p;
                if (!empty($_GET['module']))    $q[] = 'module=' . urlencode($_GET['module']);
                if (!empty($_GET['action']))    $q[] = 'action=' . urlencode($_GET['action']);
                if (!empty($_GET['date_from'])) $q[] = 'date_from=' . urlencode($_GET['date_from']);
                if (!empty($_GET['date_to']))   $q[] = 'date_to=' . urlencode($_GET['date_to']);
                return url('/audit?' . implode('&', $q));
            };
        ?>

        <?php if ($page > 1): ?>
            <a href="<?= $buildPageUrl($page - 1) ?>" class="pagination-link">&laquo; Previous</a>
        <?php endif; ?>

        <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            if ($start > 1) {
                echo '<a href="' . $buildPageUrl(1) . '" class="pagination-link">1</a>';
                if ($start > 2) echo '<span class="pagination-link" style="cursor:default;">&hellip;</span>';
            }
            for ($i = $start; $i <= $end; $i++):
        ?>
            <?php if ($i === $page): ?>
                <span class="pagination-link pagination-active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $buildPageUrl($i) ?>" class="pagination-link"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php
            if ($end < $totalPages) {
                if ($end < $totalPages - 1) echo '<span class="pagination-link" style="cursor:default;">&hellip;</span>';
                echo '<a href="' . $buildPageUrl($totalPages) . '" class="pagination-link">' . $totalPages . '</a>';
            }
        ?>

        <?php if ($page < $totalPages): ?>
            <a href="<?= $buildPageUrl($page + 1) ?>" class="pagination-link">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty State -->
    <div class="empty-state mt-3">
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-muted, #9ca3af); margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <h3>No audit logs found</h3>
        <p class="text-muted">
            <?php if (!empty($_GET['module']) || !empty($_GET['action']) || !empty($_GET['date_from']) || !empty($_GET['date_to'])): ?>
                No logs match your current filters. Try adjusting your filter criteria.
            <?php else: ?>
                No audit logs have been recorded yet. Actions performed on the platform will appear here.
            <?php endif; ?>
        </p>
    </div>
    <?php endif; ?>
</div>
