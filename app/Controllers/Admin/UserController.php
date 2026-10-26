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
        $sortBy  = trim($this->get('sort_by', 'created_at'));
        $sortDirection = strtoupper(trim($this->get('sort_direction', 'DESC')));
        if (!in_array($sortBy, ['name', 'email', 'status', 'created_at', 'last_login_at'], true)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDirection, ['ASC', 'DESC'], true)) {
            $sortDirection = 'DESC';
        }

        $result = $this->userModel->search(
            $keyword,
            $role,
            $status,
            $page,
            15,
            $sortBy,
            $sortDirection
        );

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
            'sortBy'    => $sortBy,
            'sortDirection' => $sortDirection,
            'currentAdminId' => (int)Session::userId(),
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

    public function deleteMultiple(): void
    {
        $this->verifyCsrf();

        $userIds = $this->post('user_ids', []);
        if (!is_array($userIds) || !$userIds) {
            $this->redirect('/admin/users', ['error' => 'Vui lòng chọn ít nhất một tài khoản để xóa.']);
        }

        $ids = [];
        foreach ($userIds as $userId) {
            if (!is_string($userId) && !is_int($userId)) {
                $this->redirect('/admin/users', ['error' => 'Danh sách tài khoản cần xóa không hợp lệ.']);
            }
            $validatedId = filter_var($userId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($validatedId === false) {
                $this->redirect('/admin/users', ['error' => 'Danh sách tài khoản cần xóa không hợp lệ.']);
            }
            $ids[] = $validatedId;
        }

        try {
            $deletedCount = $this->userModel->deleteUsers($ids, (int)Session::userId());
        } catch (\DomainException $e) {
            $this->redirect('/admin/users', ['error' => $e->getMessage()]);
        }

        if ($deletedCount === 0) {
            $this->redirect('/admin/users', ['error' => 'Không tìm thấy tài khoản nào để xóa.']);
        }

        $this->redirect('/admin/users', ['success' => "Đã xóa $deletedCount tài khoản và dữ liệu liên quan."]);
    }

    public function deleteAll(): void
    {
        $this->verifyCsrf();

        try {
            $deletedCount = $this->userModel->deleteAllUsers((int)Session::userId());
        } catch (\DomainException $e) {
            $this->redirect('/admin/users', ['error' => $e->getMessage()]);
        }

        if ($deletedCount === 0) {
            $this->redirect('/admin/users', ['error' => 'Không có tài khoản nào khác để xóa.']);
        }

        $this->redirect('/admin/users', ['success' => "Đã xóa tất cả $deletedCount tài khoản (giữ lại tài khoản đang đăng nhập)."]);
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
