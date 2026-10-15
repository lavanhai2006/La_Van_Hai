<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Quản Lý Hoạt Động Của Bạn</h1>
            <p class="text-muted small mb-0">Tạo mới, chỉnh sửa thông tin và quản lý các ca trực thuộc từng hoạt động</p>
        </div>
        <a href="<?= $baseUrl ?>/coordinator/activities/create" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tạo hoạt động mới
        </a>
    </div>

    <!-- Filter by status -->
    <div class="d-flex gap-2 mb-3">
        <a href="<?= $baseUrl ?>/coordinator/activities" class="btn btn-sm <?= empty($status) ? 'btn-dark' : 'btn-outline-secondary' ?>">Tất cả</a>
        <a href="<?= $baseUrl ?>/coordinator/activities?status=open" class="btn btn-sm <?= $status === 'open' ? 'btn-success' : 'btn-outline-secondary' ?>">Đang mở</a>
        <a href="<?= $baseUrl ?>/coordinator/activities?status=draft" class="btn btn-sm <?= $status === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Bản nháp</a>
        <a href="<?= $baseUrl ?>/coordinator/activities?status=closed" class="btn btn-sm <?= $status === 'closed' ? 'btn-danger' : 'btn-outline-secondary' ?>">Đã đóng</a>
    </div>

    <?php if (empty($activities)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-calendar-plus fs-1"></i>
            <h2 class="h5 mt-2">Chưa có hoạt động nào</h2>
            <p class="small">Bấm vào nút "Tạo hoạt động mới" để bắt đầu tổ chức chương trình tình nguyện.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th>Tiêu đề hoạt động</th>
                        <th>Địa điểm</th>
                        <th>Thời gian</th>
                        <th>Hạn đăng ký</th>
                        <th>Số ca trực</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $act): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($act['title']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars(mb_strimwidth($act['description'] ?? '', 0, 80, '...')) ?></small>
                            </td>
                            <td class="small text-muted"><?= htmlspecialchars($act['location'] ?? 'Chưa rõ') ?></td>
                            <td class="small">
                                <div><?= date('d/m/Y', strtotime($act['date_start'])) ?></div>
                                <small class="text-muted">đến <?= date('d/m/Y', strtotime($act['date_end'])) ?></small>
                            </td>
                            <td class="small text-muted">
                                <?= $act['registration_deadline'] ? date('d/m/Y H:i', strtotime($act['registration_deadline'])) : 'Không' ?>
                            </td>
                            <td>
                                <a href="<?= $baseUrl ?>/coordinator/activities/<?= $act['id'] ?>/shifts" class="badge bg-light text-primary border text-decoration-none p-2">
                                    <i class="bi bi-grid me-1"></i> <?= (int)$act['shift_count'] ?> ca trực
                                </a>
                            </td>
                            <td>
                                <?php if ($act['status'] === 'open'): ?>
                                    <span class="badge bg-success">Đang mở</span>
                                <?php elseif ($act['status'] === 'draft'): ?>
                                    <span class="badge bg-secondary">Nháp</span>
                                <?php elseif ($act['status'] === 'closed'): ?>
                                    <span class="badge bg-danger">Đã đóng</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($act['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= $baseUrl ?>/coordinator/activities/<?= $act['id'] ?>/shifts" class="btn btn-outline-primary" title="Xem ca">
                                        <i class="bi bi-list-check"></i>
                                    </a>
                                    <a href="<?= $baseUrl ?>/coordinator/activities/<?= $act['id'] ?>/edit" class="btn btn-outline-secondary" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
