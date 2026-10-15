<?php
/**
 * app/Controllers/Admin/ReportController.php
 * Báo cáo tổng quan và Màn hình So sánh Thuật toán (Benchmark Kỹ thuật 2)
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Database;
use App\Services\AssignmentService;

class ReportController extends Controller
{
    protected string $layout = 'admin';
    private AssignmentService $assignmentService;

    public function __construct()
    {
        $this->assignmentService = new AssignmentService();
    }

    public function index(): void
    {
        $this->redirect('/admin/reports/algorithm');
    }

    public function algorithmComparison(): void
    {
        $db = Database::getInstance();
        $activities = $db->query("SELECT id, title FROM activities ORDER BY id ASC")->fetchAll();

        // Mặc định chọn hoạt động đầu tiên hoặc theo query string
        $selectedActId = (int)$this->get('activity_id', $activities[0]['id'] ?? 1);

        $comparison = null;
        if ($selectedActId > 0) {
            $comparison = $this->assignmentService->compareAlgorithms($selectedActId);
        }

        $this->render('admin.reports.algorithm', [
            'title'         => 'So Sánh Thực Nghiệm Hai Thuật Toán | VolunteerHub',
            'activities'    => $activities,
            'selectedActId' => $selectedActId,
            'comparison'    => $comparison,
        ]);
    }
}
