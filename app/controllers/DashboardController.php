<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(array $params = []): void
    {
        $auth = auth();
        $u = $auth->user();

        if ($auth->hasRole('super_admin')) {
            $this->renderSuperAdminDashboard($u);
        } elseif ($auth->hasRole('administrator')) {
            $this->renderAdminDashboard($u);
        } elseif ($auth->hasRole('government_officer')) {
            $this->renderOfficerDashboard($u);
        } else {
            $this->renderCitizenDashboard($u);
        }
    }

    // -------------------------------------------------------
    // Super Admin Dashboard
    // -------------------------------------------------------

    private function renderSuperAdminDashboard(array $u): void
    {
        $totalUsers = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `users` WHERE `deleted_at` IS NULL"
        );

        $activeUsers = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `users` WHERE `status` = 'active' AND `deleted_at` IS NULL"
        );

        $totalDepartments = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `departments`"
        );

        $recentAuditLogs = Database::select(
            "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) AS user_name
             FROM `audit_logs` al
             LEFT JOIN `users` u ON u.`id` = al.`user_id`
             ORDER BY al.`created_at` DESC
             LIMIT 10"
        );

        $recentLoginLogs = Database::select(
            "SELECT ll.*, CONCAT(u.first_name, ' ', u.last_name) AS user_name
             FROM `login_logs` ll
             LEFT JOIN `users` u ON u.`id` = ll.`user_id`
             ORDER BY ll.`created_at` DESC
             LIMIT 10"
        );

        $this->layout('app', 'dashboard/super-admin', [
            'pageTitle'         => 'Super Admin Dashboard',
            'user'              => $u,
            'totalUsers'        => $totalUsers,
            'activeUsers'       => $activeUsers,
            'totalDepartments'  => $totalDepartments,
            'recentAuditLogs'   => $recentAuditLogs,
            'recentLoginLogs'   => $recentLoginLogs,
        ]);
    }

    // -------------------------------------------------------
    // Admin Dashboard
    // -------------------------------------------------------

    private function renderAdminDashboard(array $u): void
    {
        $totalUsers = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `users` WHERE `deleted_at` IS NULL"
        );

        $officersCount = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `users` u
             INNER JOIN `user_roles` ur ON ur.`user_id` = u.`id`
             INNER JOIN `roles` r ON r.`id` = ur.`role_id`
             WHERE r.`slug` = 'government_officer' AND u.`deleted_at` IS NULL"
        );

        $departments = Database::select(
            "SELECT * FROM `departments` ORDER BY `name` ASC"
        );

        $recentActivities = Database::select(
            "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) AS user_name
             FROM `audit_logs` al
             LEFT JOIN `users` u ON u.`id` = al.`user_id`
             ORDER BY al.`created_at` DESC
             LIMIT 10"
        );

        $this->layout('app', 'dashboard/admin', [
            'pageTitle'        => 'Admin Dashboard',
            'user'             => $u,
            'totalUsers'       => $totalUsers,
            'officersCount'    => $officersCount,
            'departments'      => $departments,
            'recentActivities' => $recentActivities,
        ]);
    }

    // -------------------------------------------------------
    // Government Officer Dashboard
    // -------------------------------------------------------

    private function renderOfficerDashboard(array $u): void
    {
        // Placeholder: no workflow/assignment tables exist yet
        $assignedWorkCount = 0;
        $notificationsCount = 0;

        $recentActivities = Database::select(
            "SELECT al.*
             FROM `audit_logs` al
             WHERE al.`user_id` = ?
             ORDER BY al.`created_at` DESC
             LIMIT 10",
            [$u['id']]
        );

        $this->layout('app', 'dashboard/officer', [
            'pageTitle'          => 'Officer Dashboard',
            'user'               => $u,
            'assignedWorkCount'  => $assignedWorkCount,
            'notificationsCount' => $notificationsCount,
            'recentActivities'   => $recentActivities,
        ]);
    }

    // -------------------------------------------------------
    // Citizen Dashboard
    // -------------------------------------------------------

    private function renderCitizenDashboard(array $u): void
    {
        // Calculate profile completeness
        $fields = ['first_name', 'last_name', 'email', 'phone', 'profile_photo'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($u[$field])) {
                $filled++;
            }
        }
        $profileCompleteness = (int) round(($filled / count($fields)) * 100);

        $this->layout('app', 'dashboard/citizen', [
            'pageTitle'            => 'My Dashboard',
            'user'                 => $u,
            'profileCompleteness'  => $profileCompleteness,
            'accountStatus'        => $u['status'],
            'emailVerified'        => $u['email_verified_at'] !== null,
        ]);
    }
}
