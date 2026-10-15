<?php
/**
 * app/Models/UserModel.php
 * Xử lý dữ liệu người dùng
 */

declare(strict_types=1);

namespace App\Models;

class UserModel extends BaseModel
{
    protected string $table = 'users';

    /**
     * Tìm user theo email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.*, GROUP_CONCAT(r.name ORDER BY r.name) as role_names,
                    GROUP_CONCAT(r.id ORDER BY r.name) as role_ids
             FROM users u
             LEFT JOIN user_roles ur ON u.id = ur.user_id
             LEFT JOIN roles r ON ur.role_id = r.id
             WHERE u.email = ?
             GROUP BY u.id
             LIMIT 1"
        );
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Tìm user theo ID kèm vai trò
     */
    public function findWithRoles(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.*, GROUP_CONCAT(r.name ORDER BY r.name) as role_names,
                    GROUP_CONCAT(r.id ORDER BY r.name) as role_ids
             FROM users u
             LEFT JOIN user_roles ur ON u.id = ur.user_id
             LEFT JOIN roles r ON ur.role_id = r.id
             WHERE u.id = ?
             GROUP BY u.id
             LIMIT 1"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Tạo user mới
     */
    public function create(string $email, string $password, string $fullName, string $phone = ''): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            "INSERT INTO users (email, password_hash, full_name, phone, status, email_verified)
             VALUES (?, ?, ?, ?, 'active', 0)"
        );
        $stmt->execute([$email, $hash, $fullName, $phone]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Cập nhật thông tin user
     */
    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET full_name = ?, phone = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$data['full_name'], $data['phone'] ?? '', $id]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$hash, $id]);
    }

    /**
     * Cập nhật lần đăng nhập cuối
     */
    public function updateLastLogin(int $id): void
    {
        $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?")
                 ->execute([$id]);
    }

    /**
     * Gán vai trò cho user
     */
    public function assignRole(int $userId, int $roleId): void
    {
        $this->db->prepare(
            "INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)"
        )->execute([$userId, $roleId]);
    }

    /**
     * Gỡ vai trò
     */
    public function removeRole(int $userId, int $roleId): void
    {
        $this->db->prepare(
            "DELETE FROM user_roles WHERE user_id = ? AND role_id = ?"
        )->execute([$userId, $roleId]);
    }

    /**
     * Cập nhật trạng thái tài khoản
     */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['active', 'inactive', 'locked'];
        if (!in_array($status, $allowed, true)) return false;
        $stmt = $this->db->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Danh sách user với phân trang và tìm kiếm
     */
    public function search(string $keyword = '', string $role = '', string $status = '', int $page = 1, int $perPage = 20): array
    {
        $params = [];
        $wheres = [];

        if ($keyword !== '') {
            $wheres[] = '(u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)';
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($status !== '') {
            $wheres[] = 'u.status = ?';
            $params[] = $status;
        }

        $whereSQL = $wheres ? 'WHERE ' . implode(' AND ', $wheres) : '';

        // Lọc theo vai trò
        $joinSQL = '';
        if ($role !== '') {
            $joinSQL = "INNER JOIN user_roles ur2 ON u.id = ur2.user_id
                        INNER JOIN roles r2 ON ur2.role_id = r2.id AND r2.name = ?";
            array_unshift($params, $role); // prepend
        }

        $countSQL = "SELECT COUNT(DISTINCT u.id) FROM users u $joinSQL $whereSQL";
        $countStmt = $this->db->prepare($countSQL);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT u.id, u.email, u.full_name, u.phone, u.status, u.created_at, u.last_login_at,
                       GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ',') as role_names
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                $joinSQL
                $whereSQL
                GROUP BY u.id
                ORDER BY u.created_at DESC
                LIMIT $perPage OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }

    /**
     * Danh sách tình nguyện viên (dùng cho phân công)
     */
    public function getVolunteers(): array
    {
        return $this->db->query(
            "SELECT u.id, u.full_name, u.email, u.phone, u.status,
                    vp.student_id, vp.faculty, vp.class_name,
                    GROUP_CONCAT(vs.skill_id ORDER BY vs.skill_id) as skill_ids
             FROM users u
             INNER JOIN user_roles ur ON u.id = ur.user_id
             INNER JOIN roles r ON ur.role_id = r.id AND r.name = 'volunteer'
             LEFT JOIN volunteer_profiles vp ON u.id = vp.user_id
             LEFT JOIN volunteer_skills vs ON u.id = vs.user_id
             WHERE u.status = 'active'
             GROUP BY u.id
             ORDER BY u.full_name"
        )->fetchAll();
    }
}
