<?php
/**
 * app/Controllers/Admin/DashboardController.php
 * Dashboard tổng quan của Quản trị viên
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Database;

class DashboardController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $db = Database::getInstance();

        $totalUsers = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalVolunteers = (int)$db->query("SELECT COUNT(*) FROM user_roles WHERE role_id = 1")->fetchColumn();
        $totalActivities = (int)$db->query("SELECT COUNT(*) FROM activities")->fetchColumn();
        $totalShifts = (int)$db->query("SELECT COUNT(*) FROM shifts")->fetchColumn();
        $totalAssignments = (int)$db->query("SELECT COUNT(*) FROM assignments WHERE status NOT IN ('removed','replaced')")->fetchColumn();

        $recentUsers = $db->query(
            "SELECT u.*, GROUP_CONCAT(r.name SEPARATOR ', ') as role_names
             FROM users u
             LEFT JOIN user_roles ur ON u.id = ur.user_id
             LEFT JOIN roles r ON ur.role_id = r.id
             GROUP BY u.id
             ORDER BY u.created_at DESC LIMIT 5"
        )->fetchAll();

        $recentLogs = $db->query(
            "SELECT al.*, u.full_name as user_name
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC LIMIT 6"
        )->fetchAll();

        $this->render('admin.dashboard', [
            'title'            => 'Bảng Điều Khiển Quản Trị | VolunteerHub',
            'totalUsers'       => $totalUsers,
            'totalVolunteers'  => $totalVolunteers,
            'totalActivities'  => $totalActivities,
            'totalShifts'      => $totalShifts,
            'totalAssignments' => $totalAssignments,
            'recentUsers'      => $recentUsers,
            'recentLogs'       => $recentLogs,
        ]);
    }
}
