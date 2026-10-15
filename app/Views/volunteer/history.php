<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-clock text-success me-2"></i>Lịch Sử Hoạt Động & Tham Gia</h1>
        <p class="text-muted small mb-0">Nhật ký toàn bộ các ca trực bạn đã từng được phân công</p>
    </div>

    <?php if (empty($assignments)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-calendar2-x fs-1"></i>
            <h2 class="h5 mt-2">Chưa có lịch sử hoạt động</h2>
            <p class="small">Lịch sử sẽ được ghi nhận sau khi bạn tham gia các ca trực.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th>Ca trực & Hoạt động</th>
                        <th>Ngày</th>
                        <th>Khung giờ</th>
                        <th>Địa điểm</th>
                        <th>Kết quả tham gia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $asgn): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($asgn['shift_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($asgn['activity_title']) ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($asgn['shift_date'])) ?></td>
                            <td><?= substr($asgn['time_start'], 0, 5) ?> - <?= substr($asgn['time_end'], 0, 5) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($asgn['activity_location'] ?? 'Chưa rõ') ?></td>
                            <td>
                                <?php if ($asgn['attendance_status'] === 'confirmed'): ?>
                                    <span class="badge bg-success-soft text-success"><i class="bi bi-check-circle-fill me-1"></i> Đã tham gia</span>
                                <?php elseif ($asgn['attendance_status'] === 'absent'): ?>
                                    <span class="badge bg-danger-soft text-danger"><i class="bi bi-x-circle-fill me-1"></i> Vắng mặt</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-soft text-secondary">Đã phân công</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
