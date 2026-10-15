<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Ca Trực Đã Đăng Ký</h1>
            <p class="text-muted small mb-0">Theo dõi trạng thái đơn đăng ký và kết quả phân công</p>
        </div>
        <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-outline-success btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Đăng ký thêm ca
        </a>
    </div>

    <?php if (empty($registrations)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <h2 class="h5 mt-2">Bạn chưa đăng ký ca trực nào</h2>
            <p class="small">Hãy duyệt danh sách các ca trực đang mở để đăng ký tham gia ngay!</p>
            <a href="<?= $baseUrl ?>/volunteer/shifts" class="btn btn-success btn-sm">Xem danh sách ca</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th>Ca & Hoạt động</th>
                        <th>Ngày & Thời gian</th>
                        <th>Địa điểm</th>
                        <th>Trạng thái đăng ký</th>
                        <th>Kết quả phân công</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $r): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($r['shift_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($r['activity_title']) ?></small>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar3 me-1 text-success"></i><?= date('d/m/Y', strtotime($r['shift_date'])) ?></div>
                                <small class="text-muted"><?= substr($r['time_start'], 0, 5) ?> - <?= substr($r['time_end'], 0, 5) ?></small>
                            </td>
                            <td class="small text-muted"><?= htmlspecialchars($r['location'] ?? 'Chưa rõ') ?></td>
                            <td>
                                <?php if ($r['status'] === 'approved'): ?>
                                    <span class="badge bg-success-soft text-success">Đã duyệt</span>
                                <?php elseif ($r['status'] === 'pending'): ?>
                                    <span class="badge bg-warning-soft text-warning">Chờ duyệt</span>
                                <?php elseif ($r['status'] === 'withdrawn'): ?>
                                    <span class="badge bg-secondary">Đã rút đơn</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-soft text-danger"><?= htmlspecialchars($r['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($r['assignment_status']) && $r['assignment_status'] !== 'removed'): ?>
                                    <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Đã được phân công</span>
                                <?php else: ?>
                                    <span class="text-muted small">Đang xếp ca</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (in_array($r['status'], ['approved', 'pending'], true) && empty($r['assignment_status'])): ?>
                                    <form action="<?= $baseUrl ?>/volunteer/unregister" method="POST" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="shift_id" value="<?= $r['shift_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Bạn có chắc chắn muốn rút đăng ký ca này?">
                                            Rút đơn
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
