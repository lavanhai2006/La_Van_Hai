<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="row g-4">
    <!-- Form Khai Báo Lịch Rảnh -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h1 class="h4 fw-bold mb-1"><i class="bi bi-plus-circle text-success me-2"></i>Thêm Lịch Rảnh Mới</h1>
            <p class="text-muted small mb-3">
                Khai báo khung thời gian bạn có thể tham gia hoạt động để thuật toán xếp ca chính xác, không bị trùng lịch học hay việc bận.
            </p>

            <form action="<?= $baseUrl ?>/volunteer/availability" method="POST">
                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

                <div class="mb-3">
                    <label for="date" class="form-label fw-semibold">Ngày rảnh <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="date" name="date" required value="<?= date('Y-m-d') ?>">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="time_start" class="form-label fw-semibold">Từ giờ <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="time_start" name="time_start" required value="08:00">
                    </div>
                    <div class="col-6">
                        <label for="time_end" class="form-label fw-semibold">Đến giờ <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="time_end" name="time_end" required value="17:00">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="note" class="form-label fw-semibold">Ghi chú (tùy chọn)</label>
                    <input type="text" class="form-control" id="note" name="note" placeholder="VD: Rảnh cả ngày thứ 7, rảnh buổi sáng...">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">
                    <i class="bi bi-calendar-plus me-1"></i> Thêm vào danh sách lịch rảnh
                </button>
            </form>
        </div>
    </div>

    <!-- Danh Sách Lịch Rảnh Đã Khai Báo -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h4 fw-bold mb-1"><i class="bi bi-clock-history text-primary me-2"></i>Khung Giờ Rảnh Đã Khai Báo</h2>
            <p class="text-muted small mb-3">Danh sách thời gian bạn đã đăng ký rảnh trên hệ thống</p>

            <?php if (empty($availabilities)): ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1"></i>
                    <p class="small mt-2 mb-0">Bạn chưa khai báo khung giờ rảnh nào.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Khung giờ</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($availabilities as $av): ?>
                                <tr>
                                    <td class="fw-bold text-success">
                                        <i class="bi bi-calendar-check me-1"></i><?= date('d/m/Y', strtotime($av['date'])) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= substr($av['time_start'], 0, 5) ?> - <?= substr($av['time_end'], 0, 5) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?= htmlspecialchars($av['note'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <form action="<?= $baseUrl ?>/volunteer/availability/delete" method="POST" class="d-inline">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                            <input type="hidden" name="id" value="<?= $av['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Bạn có chắc chắn muốn xóa lịch rảnh này?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
