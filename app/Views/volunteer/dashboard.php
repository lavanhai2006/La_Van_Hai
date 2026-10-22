<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Tổng Quan Tình Nguyện Viên</h1>
        <p class="text-muted small mb-0">Chào mừng bạn trở lại, theo dõi lịch phân công và ca trực của mình tại đây</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $baseUrl ?>/volunteer/activities" class="btn btn-outline-success btn-sm">
            <i class="bi bi-compass me-1"></i> Khám phá hoạt động
        </a>
        <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Đăng ký ca trực
        </a>
    </div>
</div>

<!-- Gamification & Level Widget -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #0f5132 0%, #198754 60%, #20c997 100%); color: white;">
    <div class="row align-items-center g-3">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center shadow flex-shrink-0" style="width: 60px; height: 60px; font-size: 1.8rem;">
                    <i class="bi bi-shield-shaded"></i>
                </div>
                <div>
                    <div class="badge bg-warning text-dark mb-1 fw-bold">Cấp độ <?= $level ?> • <?= htmlspecialchars($levelName) ?></div>
                    <h2 class="h5 fw-bold mb-0 text-white">Điểm Cống Hiến: <?= number_format($impactPoints) ?> pts</h2>
                    <small class="text-white-50">Tích lũy thêm điểm bằng cách đăng ký ca trực và hoàn thành nhiệm vụ</small>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="text-muted small mb-1 text-white-50 d-flex justify-content-between">
                <span>Huy hiệu thành tích</span>
                <span><?= count(array_filter($badges, fn($b) => $b['unlocked'])) ?>/<?= count($badges) ?> mở khóa</span>
            </div>
            <div class="d-flex gap-2">
                <?php foreach ($badges as $badge): ?>
                    <div class="p-2 rounded-3 text-center flex-fill <?= $badge['unlocked'] ? 'bg-white bg-opacity-25 text-white' : 'bg-black bg-opacity-20 text-white-50' ?>" 
                         data-bs-toggle="tooltip" title="<?= htmlspecialchars($badge['desc']) ?>">
                        <i class="bi <?= $badge['icon'] ?> fs-5 <?= $badge['unlocked'] ? 'text-warning' : '' ?>"></i>
                        <div style="font-size: 0.65rem;" class="text-truncate"><?= htmlspecialchars($badge['name']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
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
                    <a href="<?= $baseUrl ?>/volunteer/activities" class="btn btn-outline-success btn-sm">Khám phá hoạt động ngay</a>
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
                                        <small class="text-muted"><i class="bi bi-flag me-1"></i><?= htmlspecialchars($asgn['activity_title']) ?></small>
                                    </td>
                                    <td>
                                        <div><?= date('d/m/Y', strtotime($asgn['shift_date'])) ?></div>
                                        <small class="text-muted"><?= substr($asgn['time_start'], 0, 5) ?> - <?= substr($asgn['time_end'], 0, 5) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($asgn['attendance_status'] === 'confirmed'): ?>
                                            <span class="badge bg-success-soft text-success"><i class="bi bi-check-circle me-1"></i> Đã xác nhận</span>
                                        <?php elseif ($asgn['attendance_status'] === 'absent'): ?>
                                            <span class="badge bg-danger-soft text-danger"><i class="bi bi-x-circle me-1"></i> Báo vắng</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-soft text-warning"><i class="bi bi-clock me-1"></i> Chờ xác nhận</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= $baseUrl ?>/volunteer/schedule" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
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
                <a href="<?= $baseUrl ?>/volunteer/activities" class="btn btn-outline-success btn-sm text-start py-2">
                    <i class="bi bi-compass me-2"></i> Khám phá các hoạt động mở
                </a>
                <a href="<?= $baseUrl ?>/volunteer/availability" class="btn btn-outline-primary btn-sm text-start py-2">
                    <i class="bi bi-clock-history me-2"></i> Khai báo lịch rảnh tuần này
                </a>
                <a href="<?= $baseUrl ?>/volunteer/replacement" class="btn btn-outline-danger btn-sm text-start py-2">
                    <i class="bi bi-person-x me-2"></i> Gửi yêu cầu báo vắng ca
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Hoạt Động Đang Mở Tuyển Tình Nguyện Viên -->
<div class="mt-4 pt-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1"><i class="bi bi-compass text-success me-2"></i>Hoạt Động Mới Nhất Đang Tuyển Tình Nguyện Viên</h2>
            <p class="text-muted small mb-0">Khám phá và đăng ký ngay các hoạt động bạn quan tâm</p>
        </div>
        <a href="<?= $baseUrl ?>/volunteer/activities" class="btn btn-outline-success btn-sm">
            Xem tất cả hoạt động <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <?php if (empty($featuredActivities)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted">
            <p class="small mb-0">Hiện tại chưa có hoạt động nào đang mở tuyển.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php 
            $colors = ['#198754', '#0d6efd', '#fd7e14'];
            foreach ($featuredActivities as $idx => $act): 
                $cardColor = $colors[$idx % count($colors)];
            ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden activity-browse-card">
                        <div class="p-3 text-white" style="background: linear-gradient(135deg, <?= $cardColor ?> 0%, #20c997 100%);">
                            <span class="badge bg-white text-dark small fw-semibold float-end">Đang tuyển</span>
                            <h3 class="h6 fw-bold mb-0 text-white text-truncate"><?= htmlspecialchars($act['title']) ?></h3>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <p class="text-muted small mb-2 flex-grow-1">
                                <?= htmlspecialchars(mb_strimwidth($act['description'] ?? 'Chưa có mô tả chi tiết.', 0, 90, '...')) ?>
                            </p>
                            <div class="small text-muted mb-2">
                                <div><i class="bi bi-geo-alt text-danger me-1"></i> <?= htmlspecialchars($act['location'] ?? 'Chưa xác định') ?></div>
                                <div><i class="bi bi-calendar3 text-primary me-1"></i> <?= date('d/m/Y', strtotime($act['date_start'])) ?></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="badge bg-light text-dark border"><?= (int)$act['shift_count'] ?> ca trực</span>
                                <a href="<?= $baseUrl ?>/volunteer/activities/<?= $act['id'] ?>" class="btn btn-success btn-sm">
                                    Đăng ký ngay <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
