<?php
/**
 * app/Controllers/Admin/UserController.php
 * Quản lý người dùng, vai trò và trạng thái tài khoản
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Database;
use App\Models\UserModel;

class UserController extends Controller
{
    protected string $layout = 'admin';
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $keyword = trim($this->get('keyword', ''));
        $role    = trim($this->get('role', ''));
        $status  = trim($this->get('status', ''));
        $page    = max(1, (int)$this->get('page', 1));

        $result = $this->userModel->search($keyword, $role, $status, $page, 15);

        $db = Database::getInstance();
        $roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

        $this->render('admin.users.index', [
            'title'     => 'Quản Lý Tài Khoản | VolunteerHub',
            'users'     => $result['data'],
            'total'     => $result['total'],
            'page'      => $page,
            'lastPage'  => $result['last_page'],
            'keyword'   => $keyword,
            'role'      => $role,
            'status'    => $status,
            'roles'     => $roles,
            'csrfToken' => Session::generateCsrf(),
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function updateStatus(string $id): void
    {
        $this->verifyCsrf();
        $userId = (int)$id;
        $currentAdminId = (int)Session::userId();

        if ($userId === $currentAdminId) {
            $this->redirect('/admin/users', ['error' => 'Bạn không thể tự khóa tài khoản của chính mình.']);
            return;
        }

        $status = $this->post('status', 'active');
        $this->userModel->updateStatus($userId, $status);

        $this->redirect('/admin/users', ['success' => "Đã cập nhật trạng thái tài khoản sang: $status"]);
    }

    public function updateRole(string $id): void
    {
        $this->verifyCsrf();
        $userId = (int)$id;
        $roleId = (int)$this->post('role_id', 0);
        $action = $this->post('action', 'add'); // add | remove

        if ($roleId <= 0) {
            $this->redirect('/admin/users', ['error' => 'Vai trò không hợp lệ.']);
            return;
        }

        if ($action === 'add') {
            $this->userModel->assignRole($userId, $roleId);
            $msg = 'Đã gán vai trò mới cho người dùng.';
        } else {
            $this->userModel->removeRole($userId, $roleId);
            $msg = 'Đã gỡ vai trò khỏi người dùng.';
        }

        $this->redirect('/admin/users', ['success' => $msg]);
    }
}
