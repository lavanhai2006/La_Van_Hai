<?php
/**
 * app/Controllers/Coordinator/RegistrationController.php
 * Quản lý và duyệt đơn đăng ký tham gia ca trực
 */

declare(strict_types=1);

namespace App\Controllers\Coordinator;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Database;
use App\Models\RegistrationModel;

class RegistrationController extends Controller
{
    protected string $layout = 'coordinator';
    private RegistrationModel $regModel;

    public function __construct()
    {
        $this->regModel = new RegistrationModel();
    }

    public function index(): void
    {
        $coordId = (int)Session::userId();
        $status = $this->get('status', '');
        $registrations = $this->regModel->getByCoordinator($coordId, $status);

        $this->render('coordinator.registrations.index', [
            'title'         => 'Quản Lý Đăng Ký | VolunteerHub',
            'registrations' => $registrations,
            'status'        => $status,
            'csrfToken'     => Session::generateCsrf(),
            'success'       => Session::getFlash('success'),
            'error'         => Session::getFlash('error'),
        ]);
    }

    public function approve(): void
    {
        $this->verifyCsrf();
        $regId = (int)$this->post('id', 0);
        Database::getInstance()->prepare("UPDATE registrations SET status = 'approved', updated_at = NOW() WHERE id = ?")
                               ->execute([$regId]);
        $this->redirect('/coordinator/registrations', ['success' => 'Đã duyệt đơn đăng ký.']);
    }

    public function reject(): void
    {
        $this->verifyCsrf();
        $regId = (int)$this->post('id', 0);
        Database::getInstance()->prepare("UPDATE registrations SET status = 'rejected', updated_at = NOW() WHERE id = ?")
                               ->execute([$regId]);
        $this->redirect('/coordinator/registrations', ['success' => 'Đã từ chối đơn đăng ký.']);
    }
}
