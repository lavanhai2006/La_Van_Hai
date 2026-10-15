<?php
/**
 * app/Controllers/Coordinator/AssignmentController.php
 * Quản lý phân công tự động (thuật toán) và điều chỉnh phân công thủ công
 */

declare(strict_types=1);

namespace App\Controllers\Coordinator;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ActivityModel;
use App\Models\AssignmentModel;
use App\Models\Database;
use App\Models\ShiftModel;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    protected string $layout = 'coordinator';
    private AssignmentService $assignmentService;
    private AssignmentModel $assignmentModel;
    private ActivityModel $activityModel;
    private ShiftModel $shiftModel;

    public function __construct()
    {
        $this->assignmentService = new AssignmentService();
        $this->assignmentModel = new AssignmentModel();
        $this->activityModel = new ActivityModel();
        $this->shiftModel = new ShiftModel();
    }

    public function runForm(): void
    {
        $coordId = (int)Session::userId();
        $activities = $this->activityModel->getByCoordinator($coordId);

        $selectedActId = (int)$this->get('activity_id', 0);
        $recentRuns = [];
        if ($selectedActId > 0) {
            $recentRuns = $this->assignmentModel->getRunsByActivity($selectedActId);
        }

        $this->render('coordinator.assignment.run', [
            'title'         => 'Chạy Phân Công Tự Động | VolunteerHub',
            'activities'    => $activities,
            'selectedActId' => $selectedActId,
            'recentRuns'    => $recentRuns,
            'csrfToken'     => Session::generateCsrf(),
            'success'       => Session::getFlash('success'),
            'error'         => Session::getFlash('error'),
        ]);
    }

    public function run(): void
    {
        $this->verifyCsrf();
        $coordId = (int)Session::userId();
        $activityId = (int)$this->post('activity_id', 0);
        $algorithm = $this->post('algorithm', 'improved_greedy');

        if (!in_array($algorithm, ['baseline_greedy', 'improved_greedy'], true)) {
            $algorithm = 'improved_greedy';
        }

        $activity = $this->activityModel->findById($activityId);
        if (!$activity || ((int)$activity['coordinator_id'] !== $coordId && !Session::hasRole('admin'))) {
            $this->redirect('/coordinator/assignment/run', ['error' => 'Hoạt động không tồn tại hoặc bạn không có quyền.']);
            return;
        }

        $runId = $this->assignmentService->runAlgorithm($activityId, $algorithm, $coordId);

        $this->redirect("/coordinator/assignment/preview/{$runId}", [
            'success' => 'Đã chạy thuật toán thành công! Hãy xem trước phương án và kiểm tra các ca thiếu người.'
        ]);
    }

    public function preview(string $runId): void
    {
        $rId = (int)$runId;
        $runDetails = $this->assignmentModel->getRunDetails($rId);

        if (!$runDetails) {
            $this->redirect('/coordinator/assignment/run', ['error' => 'Không tìm thấy phiên phân công.']);
            return;
        }

        $metrics = json_decode($runDetails['result_summary'] ?? '{}', true);

        // Lấy thông tin user cho từng ca để hiển thị chi tiết tên TNV
        $db = Database::getInstance();
        $userNames = [];
        foreach ($runDetails['items'] as &$item) {
            $uids = json_decode($item['assigned_users'] ?? '[]', true) ?: [];
            $item['users_list'] = [];
            if (!empty($uids)) {
                $inSql = implode(',', array_map('intval', $uids));
                $uStmt = $db->query("SELECT id, full_name, email, phone FROM users WHERE id IN ($inSql)");
                $item['users_list'] = $uStmt->fetchAll();
            }
        }
        unset($item);

        $this->render('coordinator.assignment.preview', [
            'title'      => 'Xem Trước Phương Án Phân Công | VolunteerHub',
            'run'        => $runDetails,
            'metrics'    => $metrics,
            'csrfToken'  => Session::generateCsrf(),
            'success'    => Session::getFlash('success'),
            'error'      => Session::getFlash('error'),
        ]);
    }

    public function confirm(string $runId): void
    {
        $this->verifyCsrf();
        $rId = (int)$runId;
        $coordId = (int)Session::userId();

        $res = $this->assignmentService->confirmRun($rId, $coordId);
        if ($res['success']) {
            $this->redirect("/coordinator/assignment/preview/{$rId}", ['success' => $res['message']]);
        } else {
            $this->redirect("/coordinator/assignment/preview/{$rId}", ['error' => $res['message']]);
        }
    }

    public function manualForm(string $shiftId): void
    {
        $sId = (int)$shiftId;
        $coordId = (int)Session::userId();
        $shift = $this->shiftModel->findWithDetails($sId);

        if (!$shift || ((int)$shift['coordinator_id'] !== $coordId && !Session::hasRole('admin'))) {
            $this->redirect('/403');
            return;
        }

        $assignedList = $this->assignmentModel->getByShift($sId);
        $requiredSkills = $this->shiftModel->getRequiredSkills($sId);

        // Lấy danh sách ứng viên có thể thêm vào ca
        $db = Database::getInstance();
        $availableVolunteers = $db->query(
            "SELECT u.id, u.full_name, u.email, u.phone, vp.student_id,
                    (SELECT COUNT(*) FROM assignments asgn WHERE asgn.user_id = u.id AND asgn.status IN ('assigned','confirmed')) as current_shifts,
                    GROUP_CONCAT(DISTINCT sk.name SEPARATOR ', ') as skills
             FROM users u
             INNER JOIN user_roles ur ON u.id = ur.user_id AND ur.role_id = 1
             LEFT JOIN volunteer_profiles vp ON u.id = vp.user_id
             LEFT JOIN volunteer_skills vs ON u.id = vs.user_id
             LEFT JOIN skills sk ON vs.skill_id = sk.id
             WHERE u.status = 'active'
             GROUP BY u.id
             ORDER BY u.full_name ASC"
        )->fetchAll();

        $this->render('coordinator.assignment.manual', [
            'title'               => 'Điều Chỉnh Phân Công: ' . $shift['name'],
            'shift'               => $shift,
            'assignedList'        => $assignedList,
            'requiredSkills'      => $requiredSkills,
            'availableVolunteers' => $availableVolunteers,
            'csrfToken'           => Session::generateCsrf(),
            'success'             => Session::getFlash('success'),
            'error'               => Session::getFlash('error'),
        ]);
    }

    public function manualAdd(): void
    {
        $this->verifyCsrf();
        $coordId = (int)Session::userId();
        $shiftId = (int)$this->post('shift_id', 0);
        $userId  = (int)$this->post('user_id', 0);

        if ($shiftId <= 0 || $userId <= 0) {
            $this->redirect("/coordinator/assignment/manual/{$shiftId}", ['error' => 'Dữ liệu không hợp lệ.']);
            return;
        }

        $res = $this->assignmentModel->manualAdd($shiftId, $userId, $coordId);
        if ($res['success']) {
            $this->redirect("/coordinator/assignment/manual/{$shiftId}", ['success' => $res['message']]);
        } else {
            $this->redirect("/coordinator/assignment/manual/{$shiftId}", ['error' => $res['message']]);
        }
    }

    public function manualRemove(): void
    {
        $this->verifyCsrf();
        $coordId = (int)Session::userId();
        $assignmentId = (int)$this->post('assignment_id', 0);
        $shiftId      = (int)$this->post('shift_id', 0);
        $note         = trim($this->post('note', ''));

        $res = $this->assignmentModel->manualRemove($assignmentId, $coordId, $note);
        if ($res['success']) {
            $this->redirect("/coordinator/assignment/manual/{$shiftId}", ['success' => $res['message']]);
        } else {
            $this->redirect("/coordinator/assignment/manual/{$shiftId}", ['error' => $res['message']]);
        }
    }
}
