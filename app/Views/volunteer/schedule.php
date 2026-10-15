<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Lịch Phân Công Cá Nhân</h1>
            <p class="text-muted small mb-0">Các ca trực bạn đã được Điều phối viên hoặc thuật toán phân công chính thức</p>
        </div>
        <a href="<?= $baseUrl ?>/volunteer/replacement" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-person-x me-1"></i> Báo vắng ca trực
        </a>
    </div>

    <?php if (empty($assignments)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-calendar2-x fs-1"></i>
            <h2 class="h5 mt-2">Bạn chưa có ca trực nào trong lịch phân công</h2>
            <p class="small">Hãy đăng ký tham gia các ca trực đang mở để được xếp lịch.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th>Ca trực & Hoạt động</th>
                        <th>Ngày & Giờ</th>
                        <th>Địa điểm</th>
                        <th>Trạng thái tham gia</th>
                        <th class="text-center">Xác nhận / Báo vắng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $asgn): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($asgn['shift_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($asgn['activity_title']) ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold text-success"><i class="bi bi-calendar-event me-1"></i><?= date('d/m/Y', strtotime($asgn['shift_date'])) ?></div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?= substr($asgn['time_start'], 0, 5) ?> - <?= substr($asgn['time_end'], 0, 5) ?></small>
                            </td>
                            <td class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($asgn['activity_location'] ?? 'Chưa rõ') ?></td>
                            <td>
                                <?php if ($asgn['attendance_status'] === 'confirmed'): ?>
                                    <span class="badge bg-success-soft text-success"><i class="bi bi-check-circle-fill me-1"></i> Đã xác nhận tham gia</span>
                                <?php elseif ($asgn['attendance_status'] === 'absent'): ?>
                                    <span class="badge bg-danger-soft text-danger"><i class="bi bi-x-circle-fill me-1"></i> Vắng mặt / Đã xin nghỉ</span>
                                <?php elseif (!empty($asgn['replacement_request_id'])): ?>
                                    <span class="badge bg-warning-soft text-warning"><i class="bi bi-hourglass-split me-1"></i> Đang chờ duyệt báo vắng</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-soft text-secondary">Chờ bạn xác nhận</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($asgn['attendance_status'] !== 'confirmed' && empty($asgn['replacement_request_id'])): ?>
                                    <div class="d-inline-flex gap-2">
                                        <form action="<?= $baseUrl ?>/volunteer/confirm" method="POST" class="d-inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="assignment_id" value="<?= $asgn['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <i class="bi bi-check-lg me-1"></i> Xác nhận
                                            </button>
                                        </form>
                                        <a href="<?= $baseUrl ?>/volunteer/replacement" class="btn btn-outline-danger btn-sm">
                                            Báo vắng
                                        </a>
                                    </div>
                                <?php elseif ($asgn['attendance_status'] === 'confirmed' && empty($asgn['replacement_request_id'])): ?>
                                    <a href="<?= $baseUrl ?>/volunteer/replacement" class="btn btn-outline-secondary btn-sm">
                                        Báo bận đột xuất
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Đã ghi nhận</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
