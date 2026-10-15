<?php
/**
 * app/Controllers/Volunteer/ShiftController.php
 * Quản lý ca trực: xem ca có thể đăng ký, đăng ký, hủy đăng ký, xem lịch cá nhân, xác nhận tham gia
 */

declare(strict_types=1);

namespace App\Controllers\Volunteer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\AssignmentModel;
use App\Models\RegistrationModel;
use App\Models\ShiftModel;

class ShiftController extends Controller
{
    protected string $layout = 'volunteer';
    private ShiftModel $shiftModel;
    private RegistrationModel $regModel;
    private AssignmentModel $asgnModel;

    public function __construct()
    {
        $this->shiftModel = new ShiftModel();
        $this->regModel = new RegistrationModel();
        $this->asgnModel = new AssignmentModel();
    }

    public function available(): void
    {
        $userId = (int)Session::userId();
        $shifts = $this->shiftModel->getAvailableForVolunteer($userId);

        $this->render('volunteer.shifts', [
            'title'     => 'Ca Trực Có Thể Đăng Ký | VolunteerHub',
            'shifts'    => $shifts,
            'csrfToken' => Session::generateCsrf(),
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function register(): void
    {
        $this->verifyCsrf();
        $userId = (int)Session::userId();
        $shiftId = (int)$this->post('shift_id', 0);
        $note = trim($this->post('note', ''));

        $res = $this->regModel->register($userId, $shiftId, $note);
        if ($res['success']) {
            $this->redirect('/volunteer/shifts', ['success' => $res['message']]);
        } else {
            $this->redirect('/volunteer/shifts', ['error' => $res['message']]);
        }
    }

    public function unregister(): void
    {
        $this->verifyCsrf();
        $userId = (int)Session::userId();
        $shiftId = (int)$this->post('shift_id', 0);

        $res = $this->regModel->withdraw($userId, $shiftId);
        if ($res['success']) {
            $this->redirect('/volunteer/my-registrations', ['success' => $res['message']]);
        } else {
            $this->redirect('/volunteer/my-registrations', ['error' => $res['message']]);
        }
    }

    public function myRegistrations(): void
    {
        $userId = (int)Session::userId();
        $registrations = $this->regModel->getByUser($userId);

        $this->render('volunteer.my_registrations', [
            'title'         => 'Ca Đã Đăng Ký | VolunteerHub',
            'registrations' => $registrations,
            'csrfToken'     => Session::generateCsrf(),
            'success'       => Session::getFlash('success'),
            'error'         => Session::getFlash('error'),
        ]);
    }

    public function schedule(): void
    {
        $userId = (int)Session::userId();
        $assignments = $this->asgnModel->getByUser($userId);

        $this->render('volunteer.schedule', [
            'title'       => 'Lịch Phân Công Cá Nhân | VolunteerHub',
            'assignments' => $assignments,
            'csrfToken'   => Session::generateCsrf(),
            'success'     => Session::getFlash('success'),
            'error'       => Session::getFlash('error'),
        ]);
    }

    public function confirm(): void
    {
        $this->verifyCsrf();
        $userId = (int)Session::userId();
        $asgnId = (int)$this->post('assignment_id', 0);

        $res = $this->asgnModel->confirmAttendance($asgnId, $userId);
        if ($res['success']) {
            $this->redirect('/volunteer/schedule', ['success' => $res['message']]);
        } else {
            $this->redirect('/volunteer/schedule', ['error' => $res['message']]);
        }
    }
}
