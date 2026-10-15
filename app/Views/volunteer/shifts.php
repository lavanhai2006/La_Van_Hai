<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Ca Trực Đang Mở Đăng Ký</h1>
            <p class="text-muted small mb-0">Lựa chọn các ca phù hợp với thời gian và kỹ năng của bạn để đăng ký tham gia</p>
        </div>
        <a href="<?= $baseUrl ?>/volunteer/my-registrations" class="btn btn-outline-success btn-sm">
            <i class="bi bi-card-checklist me-1"></i> Xem ca đã đăng ký
        </a>
    </div>

    <?php if (empty($shifts)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-calendar-x fs-1"></i>
            <h2 class="h5 mt-2">Hiện tại không có ca trực nào đang mở đăng ký</h2>
            <p class="small">Hãy quay lại sau hoặc theo dõi các thông báo mới từ Điều phối viên.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th>Ca trực & Hoạt động</th>
                        <th>Địa điểm & Ngày</th>
                        <th>Thời gian ca</th>
                        <th>Yêu cầu kỹ năng</th>
                        <th>Tình trạng</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shifts as $s): ?>
                        <?php 
                            $isRegistered = !empty($s['my_reg_id']) && in_array($s['my_reg_status'], ['approved', 'pending'], true);
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($s['name']) ?></div>
                                <small class="text-success fw-semibold"><i class="bi bi-flag me-1"></i><?= htmlspecialchars($s['activity_title']) ?></small>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar3 me-1 text-primary"></i><?= date('d/m/Y', strtotime($s['date'])) ?></div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($s['activity_location'] ?? 'Chưa rõ') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= substr($s['time_start'], 0, 5) ?> - <?= substr($s['time_end'], 0, 5) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($s['required_skills'])): ?>
                                    <span class="badge bg-warning-soft text-dark"><i class="bi bi-star-fill text-warning me-1"></i><?= htmlspecialchars($s['required_skills']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">Mọi tình nguyện viên</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= (int)$s['assigned_count'] ?> / <?= (int)$s['max_volunteers'] ?> người
                                </small>
                            </td>
                            <td class="text-center">
                                <?php if ($isRegistered): ?>
                                    <span class="badge bg-success-soft text-success"><i class="bi bi-check2 me-1"></i> Đã đăng ký</span>
                                <?php else: ?>
                                    <form action="<?= $baseUrl ?>/volunteer/register" method="POST" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="shift_id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm px-3">
                                            <i class="bi bi-plus-lg me-1"></i> Đăng ký
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
