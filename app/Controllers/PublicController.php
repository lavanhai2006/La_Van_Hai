<?php
/**
 * app/Controllers/PublicController.php
 * Trang công khai: trang chủ, danh sách hoạt động
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ActivityModel;
use App\Models\ShiftModel;

class PublicController extends Controller
{
    protected string $layout = 'main';
    private ActivityModel $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    public function home(): void
    {
        $activities = $this->activityModel->getOpenActivities(1, 6);
        $this->render('public.home', [
            'title'      => 'VolunteerHub - Tổ Chức Tình Nguyện',
            'activities' => $activities['data'],
            'error'      => Session::getFlash('error'),
            'success'    => Session::getFlash('success'),
        ]);
    }

    public function activities(): void
    {
        $page = max(1, (int)$this->get('page', 1));
        $result = $this->activityModel->getOpenActivities($page, 9);
        $this->render('public.activities', [
            'title'      => 'Hoạt Động Tình Nguyện | VolunteerHub',
            'activities' => $result['data'],
            'pagination' => $result,
            'error'      => Session::getFlash('error'),
        ]);
    }

    public function activityDetail(string $id): void
    {
        $actId = (int)$id;
        $activity = $this->activityModel->findWithCoordinator($actId);

        if (!$activity) {
            http_response_code(404);
            $this->render('errors.404', ['title' => 'Không tìm thấy | VolunteerHub']);
            return;
        }

        $shiftModel = new ShiftModel();
        $shifts     = $shiftModel->getByActivity($actId);
        $userId     = Session::userId();

        $this->render('public.activity_detail', [
            'title'    => $activity['title'] . ' | VolunteerHub',
            'activity' => $activity,
            'shifts'   => $shifts,
            'userId'   => $userId,
            'isLogged' => Session::isLoggedIn(),
            'error'    => Session::getFlash('error'),
            'success'  => Session::getFlash('success'),
        ]);
    }
}
