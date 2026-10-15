<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Tổng Quan Tình Nguyện Viên</h1>
        <p class="text-muted small mb-0">Chào mừng bạn trở lại, theo dõi lịch phân công và ca trực của mình tại đây</p>
    </div>
    <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Đăng ký ca mới
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Ca đã phân công</div>
                    <div class="h3 fw-bold text-dark mt-1 mb-0"><?= $totalAssigned ?></div>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-calendar2-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Đã xác nhận</div>
                    <div class="h3 fw-bold text-success mt-1 mb-0"><?= $confirmedCount ?></div>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Ca sắp diễn ra</div>
                    <div class="h3 fw-bold text-info mt-1 mb-0"><?= $upcomingCount ?></div>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-hourglass-top"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Kỹ năng khai báo</div>
                    <div class="h3 fw-bold text-warning mt-1 mb-0"><?= count($skills) ?></div>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-award"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Phân công gần đây -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><i class="bi bi-calendar-event text-success me-2"></i>Ca Trực Được Phân Công Gần Nhất</h2>
                <a href="<?= $baseUrl ?>/volunteer/schedule" class="text-success small fw-semibold text-decoration-none">Xem toàn bộ</a>
            </div>

            <?php if (empty($assignments)): ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="small mt-2">Bạn chưa có ca trực nào được phân công.</p>
                    <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-outline-success btn-sm">Khám phá ca trực ngay</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Ca trực & Hoạt động</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
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
                                        <div><i class="bi bi-calendar3 me-1 text-success"></i><?= date('d/m/Y', strtotime($asgn['shift_date'])) ?></div>
                                        <small class="text-muted"><?= substr($asgn['time_start'], 0, 5) ?> - <?= substr($asgn['time_end'], 0, 5) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($asgn['attendance_status'] === 'confirmed'): ?>
                                            <span class="badge bg-success">Đã xác nhận</span>
                                        <?php elseif ($asgn['attendance_status'] === 'absent'): ?>
                                            <span class="badge bg-danger">Báo vắng</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= $baseUrl ?>/volunteer/schedule" class="btn btn-sm btn-light border">Chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cột Kỹ năng & Lối tắt -->
    <div class="col-lg-4">
        <!-- Kỹ năng của tôi -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold mb-0 text-uppercase"><i class="bi bi-award text-success me-1"></i> Kỹ năng</h2>
                <a href="<?= $baseUrl ?>/volunteer/skills" class="small text-success text-decoration-none">Cập nhật</a>
            </div>

            <?php if (empty($skills)): ?>
                <p class="text-muted small mb-0">Chưa khai báo kỹ năng. Hãy cập nhật để được ưu tiên phân công!</p>
            <?php else: ?>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($skills as $sk): ?>
                        <span class="badge bg-light text-dark border p-2">
                            <?= htmlspecialchars($sk['name']) ?>
                            <small class="text-success ms-1">(<?= htmlspecialchars($sk['level']) ?>)</small>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Lối tắt nhanh -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h6 fw-bold mb-3 text-uppercase"><i class="bi bi-lightning-charge text-warning me-1"></i> Thao tác nhanh</h2>
            <div class="d-grid gap-2">
                <a href="<?= $baseUrl ?>/volunteer/availability" class="btn btn-outline-success btn-sm text-start py-2">
                    <i class="bi bi-clock-history me-2"></i> Khai báo lịch rảnh tuần này
                </a>
                <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-outline-primary btn-sm text-start py-2">
                    <i class="bi bi-search me-2"></i> Tìm kiếm ca trực phù hợp
                </a>
                <a href="<?= $baseUrl ?>/volunteer/replacement" class="btn btn-outline-danger btn-sm text-start py-2">
                    <i class="bi bi-person-x me-2"></i> Gửi yêu cầu báo vắng ca
                </a>
            </div>
        </div>
    </div>
</div>
