<?php
/**
 * app/Controllers/AuthController.php
 * Xử lý đăng nhập, đăng xuất, đăng ký
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\UserModel;
use App\Models\Database;

class AuthController extends Controller
{
    protected string $layout = 'auth';
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginForm(): void
    {
        $error = Session::getFlash('error');
        if ($error === null && $this->get('reason') === 'account_locked') {
            $error = 'Tài khoản đã bị khóa hoặc vô hiệu hóa. Bạn đã được đăng xuất.';
        }

        $this->render('auth.login', [
            'title'     => 'Đăng Nhập | VolunteerHub',
            'csrfToken' => Session::generateCsrf(),
            'error'     => $error,
            'success'   => Session::getFlash('success'),
        ]);
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');
        $errors   = [];

        // Validate
        if (empty($email))    $errors[] = 'Vui lòng nhập email.';
        if (empty($password)) $errors[] = 'Vui lòng nhập mật khẩu.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không đúng định dạng.';

        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            // Không nói rõ lỗi là email hay mật khẩu (bảo mật)
            Session::flash('error', 'Email hoặc mật khẩu không chính xác.');
            $this->redirect('/login');
            return;
        }

        if ($user['status'] !== 'active') {
            Session::flash('error', 'Tài khoản của bạn đã bị khóa hoặc vô hiệu hóa. Vui lòng liên hệ quản trị viên.');
            $this->redirect('/login');
            return;
        }

        // Đăng nhập thành công
        Session::login($user);
        $this->userModel->updateLastLogin((int)$user['id']);

        // Ghi audit log
        $this->logAudit((int)$user['id'], 'login', null, null, ['method' => 'password']);

        // Chuyển hướng theo vai trò
        if (Session::hasRole('admin'))            $this->redirect('/admin/dashboard');
        elseif (Session::hasRole('coordinator'))  $this->redirect('/coordinator/dashboard');
        else                                      $this->redirect('/volunteer/dashboard');
    }

    public function registerForm(): void
    {
        $this->render('auth.register', [
            'title'     => 'Đăng Ký | VolunteerHub',
            'csrfToken' => Session::generateCsrf(),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function register(): void
    {
        $this->verifyCsrf();

        $fullName  = trim($this->post('full_name', ''));
        $email     = trim(strtolower($this->post('email', '')));
        $phone     = trim($this->post('phone', ''));
        $password  = $this->post('password', '');
        $password2 = $this->post('password_confirm', '');
        $errors    = [];

        // Validation
        if (strlen($fullName) < 2)  $errors[] = 'Họ tên phải có ít nhất 2 ký tự.';
        if (strlen($fullName) > 200) $errors[] = 'Họ tên quá dài (tối đa 200 ký tự).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không đúng định dạng.';
        if (strlen($password) < 8)  $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự.';
        if ($password !== $password2) $errors[] = 'Xác nhận mật khẩu không khớp.';

        // Kiểm tra email đã tồn tại chưa
        if (empty($errors) && $this->userModel->findByEmail($email)) {
            $errors[] = 'Email này đã được sử dụng. Vui lòng dùng email khác hoặc đăng nhập.';
        }

        if (!empty($errors)) {
            Session::flash('error', implode('<br>', $errors));
            $this->redirect('/register');
            return;
        }

        $pdo = Database::getInstance();
        try {
            $pdo->beginTransaction();

            $userId = $this->userModel->create($email, $password, $fullName, $phone);

            // Gán vai trò volunteer (mặc định)
            $roleStmt = $pdo->prepare(
                "INSERT INTO user_roles (user_id, role_id)
                 SELECT ?, id FROM roles WHERE name = 'volunteer' LIMIT 1"
            );
            $roleStmt->execute([$userId]);

            // Tạo hồ sơ volunteer trống
            $pdo->prepare(
                "INSERT INTO volunteer_profiles (user_id) VALUES (?)"
            )->execute([$userId]);

            // Gửi thông báo chào mừng
            $pdo->prepare(
                "INSERT INTO notifications (user_id, type, title, message, link)
                 VALUES (?, 'system', ?, ?, '/volunteer/profile')"
            )->execute([
                $userId,
                'Chào mừng đến với VolunteerHub!',
                'Tài khoản của bạn đã được tạo thành công. Hãy cập nhật hồ sơ và khai báo kỹ năng để được phân công phù hợp.'
            ]);

            $pdo->commit();

            $this->logAudit($userId, 'register', 'user', $userId, ['email' => $email]);
            Session::flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            $this->redirect('/login');
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log('[Register Error] ' . $e->getMessage());
            Session::flash('error', 'Đã xảy ra lỗi khi tạo tài khoản. Vui lòng thử lại.');
            $this->redirect('/register');
        }
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        $userId = Session::userId();
        $this->logAudit($userId, 'logout', null, null, []);
        Session::logout();
        Session::flash('success', 'Đã đăng xuất thành công.');
        $this->redirect('/login');
    }

    private function logAudit(?int $userId, string $action, ?string $entityType, ?int $entityId, array $newData): void
    {
        try {
            Database::getInstance()->prepare(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_data, ip_address, user_agent)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            )->execute([
                $userId, $action, $entityType, $entityId,
                json_encode($newData, JSON_UNESCAPED_UNICODE),
                $_SERVER['REMOTE_ADDR'] ?? null,
                substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ]);
        } catch (\Exception $e) {
            error_log('[AuditLog Error] ' . $e->getMessage());
        }
    }
}
