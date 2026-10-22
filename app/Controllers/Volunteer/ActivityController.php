<?php
/**
 * app/Controllers/Volunteer/ActivityController.php
 * Duyệt hoạt động tình nguyện và đăng ký ca trực trực tiếp
 */

declare(strict_types=1);

namespace App\Controllers\Volunteer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ActivityModel;
use App\Models\RegistrationModel;

class ActivityController extends Controller
{
    protected string $layout = 'volunteer';
    private ActivityModel $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    /**
     * Danh sách hoạt động đang mở cho TNV duyệt
     */
    public function index(): void
    {
        $page = max(1, (int)$this->get('page', 1));
        $result = $this->activityModel->getAllOpenActivities($page, 9);

        $this->render('volunteer.activities', [
            'title'      => 'Khám Phá Hoạt Động Tình Nguyện | VolunteerHub',
            'activities' => $result['data'],
            'pagination' => $result,
            'csrfToken'  => Session::generateCsrf(),
            'success'    => Session::getFlash('success'),
            'error'      => Session::getFlash('error'),
        ]);
    }

    /**
     * Chi tiết hoạt động + danh sách ca trực để đăng ký
     */
    public function detail(string $id): void
    {
        $actId  = (int)$id;
        $userId = (int)Session::userId();

        $activity = $this->activityModel->findWithCoordinator($actId);
        if (!$activity) {
            http_response_code(404);
            $this->render('errors.404', ['title' => 'Không tìm thấy | VolunteerHub']);
            return;
        }

        $shifts = $this->activityModel->getActivityShiftsForVolunteer($actId, $userId);

        $this->render('volunteer.activity_detail', [
            'title'     => $activity['title'] . ' | VolunteerHub',
            'activity'  => $activity,
            'shifts'    => $shifts,
            'csrfToken' => Session::generateCsrf(),
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    /**
     * Đăng ký ca trực từ trang chi tiết hoạt động
     */
    public function registerShift(): void
    {
        $this->verifyCsrf();
        $userId     = (int)Session::userId();
        $shiftId    = (int)$this->post('shift_id', 0);
        $activityId = (int)$this->post('activity_id', 0);

        $regModel = new RegistrationModel();
        $res = $regModel->register($userId, $shiftId);

        if ($res['success']) {
            $this->redirect("/volunteer/activities/$activityId", ['success' => $res['message']]);
        } else {
            $this->redirect("/volunteer/activities/$activityId", ['error' => $res['message']]);
        }
    }
}
