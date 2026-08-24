<?php
declare(strict_types=1);

class AuditController extends Controller
{
    public function index(array $params = []): void
    {
        $perPage = 20;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        $limit = max(1, min(100, $perPage));
        $offsetSafe = max(0, $offset);
        
        $where = '1=1';
        $queryParams = [];
        
        if (!empty($_GET['module'])) {
            $where .= ' AND al.module = :module';
            $queryParams['module'] = $_GET['module'];
        }
        if (!empty($_GET['action'])) {
            $where .= ' AND al.action = :action';
            $queryParams['action'] = $_GET['action'];
        }
        if (!empty($_GET['date_from'])) {
            $where .= ' AND al.created_at >= :date_from';
            $queryParams['date_from'] = $_GET['date_from'] . ' 00:00:00';
        }
        if (!empty($_GET['date_to'])) {
            $where .= ' AND al.created_at <= :date_to';
            $queryParams['date_to'] = $_GET['date_to'] . ' 23:59:59';
        }
        
        $total = (int)Database::selectScalar("SELECT COUNT(*) FROM audit_logs al WHERE $where", $queryParams);
        $totalPages = (int)ceil($total / $perPage);
        
        $logs = Database::select(
            "SELECT al.*, u.first_name, u.last_name 
             FROM audit_logs al 
             LEFT JOIN users u ON u.id = al.user_id 
             WHERE $where 
             ORDER BY al.created_at DESC 
             LIMIT {$limit} OFFSET {$offsetSafe}",
            $queryParams
        );
        
        $modules = Database::select("SELECT DISTINCT module FROM audit_logs ORDER BY module");
        $actions = Database::select("SELECT DISTINCT action FROM audit_logs ORDER BY action");
        
        $this->layout('app', 'audit/index', [
            'logs' => $logs,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'modules' => $modules,
            'actions' => $actions,
            'pageTitle' => 'Audit Logs',
        ]);
    }
}
