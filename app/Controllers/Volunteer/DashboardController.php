<?php
/**
 * app/Controllers/Volunteer/DashboardController.php
 * Dashboard và lịch sử của Tình nguyện viên
 */

declare(strict_types=1);

namespace App\Controllers\Volunteer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\AssignmentModel;
use App\Models\NotificationModel;
use App\Models\RegistrationModel;
use App\Models\ShiftModel;
use App\Models\SkillModel;

class DashboardController extends Controller
{
    protected string $layout = 'volunteer';

    public function index(): void
    {
        $userId = (int)Session::userId();

        $assignmentModel = new AssignmentModel();
        $registrationModel = new RegistrationModel();
        $notificationModel = new NotificationModel();
        $skillModel = new SkillModel();

        $myAssignments = $assignmentModel->getByUser($userId);
        $myRegistrations = $registrationModel->getByUser($userId);
        $mySkills = $skillModel->getByUserId($userId);
        $unreadCount = $notificationModel->getUnreadCount($userId);

        // Thống kê nhanh
        $confirmedCount = 0;
        $upcomingCount = 0;
        $today = date('Y-m-d');

        foreach ($myAssignments as $asgn) {
            if ($asgn['attendance_status'] === 'confirmed') $confirmedCount++;
            if ($asgn['shift_date'] >= $today) $upcomingCount++;
        }

        $this->render('volunteer.dashboard', [
            'title'           => 'Bảng Điều Khiển Tình Nguyện Viên | VolunteerHub',
            'assignments'     => array_slice($myAssignments, 0, 5),
            'registrations'   => array_slice($myRegistrations, 0, 5),
            'skills'          => $mySkills,
            'unreadCount'     => $unreadCount,
            'totalRegistered' => count($myRegistrations),
            'totalAssigned'   => count($myAssignments),
            'confirmedCount'  => $confirmedCount,
            'upcomingCount'   => $upcomingCount,
            'success'         => Session::getFlash('success'),
            'error'           => Session::getFlash('error'),
        ]);
    }

    public function history(): void
    {
        $userId = (int)Session::userId();
        $assignmentModel = new AssignmentModel();
        $assignments = $assignmentModel->getByUser($userId);

        $this->render('volunteer.history', [
            'title'       => 'Lịch Sử Hoạt Động | VolunteerHub',
            'assignments' => $assignments,
        ]);
    }
}
