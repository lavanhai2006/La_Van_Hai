<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Bảng Điều Khiển Quản Trị Viên (Admin)</h1>
        <p class="text-muted small mb-0">Giám sát toàn bộ hệ thống, quản lý tài khoản, kỹ năng và dữ liệu thực nghiệm</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $baseUrl ?>/admin/reports/algorithm" class="btn btn-primary btn-sm">
            <i class="bi bi-graph-up-arrow me-1"></i> So sánh thuật toán
        </a>
        <a href="<?= $baseUrl ?>/admin/seed" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-arrow-repeat me-1"></i> Tái tạo dữ liệu mẫu
        </a>
    </div>
</div>

<!-- System Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Tổng tài khoản</div>
                    <div class="h3 fw-bold text-dark mt-1 mb-0"><?= $totalUsers ?></div>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Tình nguyện viên</div>
                    <div class="h3 fw-bold text-success mt-1 mb-0"><?= $totalVolunteers ?></div>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-person-heart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Tổng số ca trực</div>
                    <div class="h3 fw-bold text-info mt-1 mb-0"><?= $totalShifts ?></div>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-grid"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Tổng phân công</div>
                    <div class="h3 fw-bold text-warning mt-1 mb-0"><?= $totalAssignments ?></div>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tài khoản mới nhất -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><i class="bi bi-person-badge text-primary me-2"></i>Tài Khoản Đăng Ký Gần Nhất</h2>
                <a href="<?= $baseUrl ?>/admin/users" class="small text-primary text-decoration-none fw-semibold">Xem tất cả</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Họ và tên</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $u): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($u['full_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($u['role_names'] ?? 'volunteer') ?></span>
                                </td>
                                <td>
                                    <?php if ($u['status'] === 'active'): ?>
                                        <span class="badge bg-success-soft text-success">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-soft text-danger"><?= htmlspecialchars($u['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Nhật ký thao tác hệ thống -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><i class="bi bi-journal-text text-danger me-2"></i>Nhật Ký Thao Tác Gần Đây</h2>
                <a href="<?= $baseUrl ?>/admin/logs" class="small text-danger text-decoration-none fw-semibold">Xem toàn bộ</a>
            </div>

            <div class="list-group list-group-flush">
                <?php foreach ($recentLogs as $log): ?>
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-light text-dark border me-1"><?= htmlspecialchars($log['action']) ?></span>
                            <span class="small fw-semibold text-dark"><?= htmlspecialchars($log['user_name'] ?? 'Hệ thống') ?></span>
                            <div class="small text-muted">Thực thể: <?= htmlspecialchars($log['entity_type'] ?? '-') ?></div>
                        </div>
                        <small class="text-muted"><?= date('H:i d/m', strtotime($log['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
