<?php
/**
 * app/Controllers/Volunteer/NotificationController.php
 * Xem và đánh dấu đã đọc thông báo
 */

declare(strict_types=1);

namespace App\Controllers\Volunteer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\NotificationModel;

class NotificationController extends Controller
{
    protected string $layout = 'volunteer';
    private NotificationModel $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotificationModel();
    }

    public function index(): void
    {
        $userId = (int)Session::userId();
        $notifications = $this->notifModel->getByUser($userId, 50);

        $this->render('volunteer.notifications', [
            'title'         => 'Thông Báo Hệ Thống | VolunteerHub',
            'notifications' => $notifications,
            'csrfToken'     => Session::generateCsrf(),
            'success'       => Session::getFlash('success'),
        ]);
    }

    public function markRead(): void
    {
        $this->verifyCsrf();
        $userId = (int)Session::userId();
        $notifId = (int)$this->post('id', 0);

        if ($notifId > 0) {
            $this->notifModel->markAsRead($notifId, $userId);
        } else {
            $this->notifModel->markAllAsRead($userId);
        }

        $this->redirect('/volunteer/notifications', ['success' => 'Đã cập nhật trạng thái thông báo.']);
    }
}
