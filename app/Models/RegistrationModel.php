<?php
/**
 * app/Models/RegistrationModel.php
 * Quản lý đăng ký tham gia ca trực
 */

declare(strict_types=1);

namespace App\Models;

class RegistrationModel extends BaseModel
{
    protected string $table = 'registrations';

    public function register(int $userId, int $shiftId, string $note = ''): array
    {
        // 1. Kiểm tra ca tồn tại & trạng thái
        $shiftModel = new ShiftModel();
        $shift = $shiftModel->findWithDetails($shiftId);
        if (!$shift) {
            return ['success' => false, 'message' => 'Ca trực không tồn tại.'];
        }

        if ($shift['status'] !== 'open') {
            return ['success' => false, 'message' => 'Ca trực hiện không mở đăng ký.'];
        }

        // 2. Kiểm tra hạn đăng ký hoạt động
        if (!empty($shift['registration_deadline'])) {
            $deadline = strtotime($shift['registration_deadline']);
            if (time() > $deadline) {
                return ['success' => false, 'message' => 'Đã hết hạn đăng ký cho hoạt động này.'];
            }
        }

        // 3. Kiểm tra đã đăng ký chưa
        $stmt = $this->db->prepare("SELECT * FROM registrations WHERE user_id = ? AND shift_id = ? LIMIT 1");
        $stmt->execute([$userId, $shiftId]);
        $existing = $stmt->fetch();

        if ($existing) {
            if (in_array($existing['status'], ['pending', 'approved'], true)) {
                return ['success' => false, 'message' => 'Bạn đã đăng ký ca này rồi.'];
            }
            // Nếu đã từng rút/hủy -> cho phép đăng ký lại
            $updateStmt = $this->db->prepare(
                "UPDATE registrations SET status = 'approved', note = ?, registered_at = NOW(), updated_at = NOW() WHERE id = ?"
            );
            $updateStmt->execute([$note, $existing['id']]);
            return ['success' => true, 'message' => 'Đăng ký tham gia ca trực thành công!'];
        }

        // 4. Thêm bản ghi đăng ký mới
        $insertStmt = $this->db->prepare(
            "INSERT INTO registrations (user_id, shift_id, status, note, registered_at) VALUES (?, ?, 'approved', ?, NOW())"
        );
        $insertStmt->execute([$userId, $shiftId, $note]);

        return ['success' => true, 'message' => 'Đăng ký tham gia ca trực thành công!'];
    }

    public function withdraw(int $userId, int $shiftId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM registrations WHERE user_id = ? AND shift_id = ? LIMIT 1");
        $stmt->execute([$userId, $shiftId]);
        $reg = $stmt->fetch();

        if (!$reg || in_array($reg['status'], ['cancelled', 'withdrawn', 'rejected'], true)) {
            return ['success' => false, 'message' => 'Bạn chưa đăng ký hoặc đăng ký đã bị hủy.'];
        }

        // Kiểm tra xem đã bị gán phân công chưa
        $asgnStmt = $this->db->prepare(
            "SELECT * FROM assignments WHERE user_id = ? AND shift_id = ? AND status IN ('assigned', 'confirmed') LIMIT 1"
        );
        $asgnStmt->execute([$userId, $shiftId]);
        if ($asgnStmt->fetch()) {
            return ['success' => false, 'message' => 'Bạn đã được phân công vào ca này, không thể tự rút đăng ký. Hãy gửi yêu cầu báo vắng/thay người!'];
        }

        $this->db->prepare("UPDATE registrations SET status = 'withdrawn', updated_at = NOW() WHERE id = ?")
                 ->execute([$reg['id']]);

        return ['success' => true, 'message' => 'Đã rút đăng ký thành công.'];
    }

    public function getByShift(int $shiftId): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.full_name, u.email, u.phone, vp.student_id, vp.faculty,
                    GROUP_CONCAT(CONCAT(sk.name, ' (', vs.level, ')') SEPARATOR ', ') as user_skills
             FROM registrations r
             JOIN users u ON r.user_id = u.id
             LEFT JOIN volunteer_profiles vp ON u.id = vp.user_id
             LEFT JOIN volunteer_skills vs ON u.id = vs.user_id
             LEFT JOIN skills sk ON vs.skill_id = sk.id
             WHERE r.shift_id = ?
             GROUP BY r.id
             ORDER BY r.registered_at ASC"
        );
        $stmt->execute([$shiftId]);
        return $stmt->fetchAll();
    }

    public function getByCoordinator(int $coordinatorId, string $status = ''): array
    {
        $where = "a.coordinator_id = ?";
        $params = [$coordinatorId];
        if ($status !== '') {
            $where .= " AND r.status = ?";
            $params[] = $status;
        }

        $stmt = $this->db->prepare(
            "SELECT r.*, u.full_name as volunteer_name, u.email as volunteer_email, u.phone,
                    s.name as shift_name, s.date as shift_date, s.time_start, s.time_end,
                    a.title as activity_title, a.id as activity_id
             FROM registrations r
             JOIN users u ON r.user_id = u.id
             JOIN shifts s ON r.shift_id = s.id
             JOIN activities a ON s.activity_id = a.id
             WHERE $where
             ORDER BY r.registered_at DESC"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, s.name as shift_name, s.date as shift_date, s.time_start, s.time_end,
                    a.title as activity_title, a.location, a.id as activity_id,
                    asgn.status as assignment_status, asgn.id as assignment_id
             FROM registrations r
             JOIN shifts s ON r.shift_id = s.id
             JOIN activities a ON s.activity_id = a.id
             LEFT JOIN assignments asgn ON asgn.shift_id = s.id AND asgn.user_id = r.user_id AND asgn.status NOT IN ('removed','replaced')
             WHERE r.user_id = ?
             ORDER BY s.date DESC, s.time_start DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
