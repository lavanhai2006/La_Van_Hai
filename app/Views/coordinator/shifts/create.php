<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-plus-circle text-success me-2"></i>Thêm Ca Trực Mới</h1>
        <p class="text-muted small mb-0">Hoạt động: <strong><?= htmlspecialchars($activity['title']) ?></strong></p>
    </div>

    <form action="<?= $baseUrl ?>/coordinator/shifts/create" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
        <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Tên ca trực <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="VD: Ca sáng - Tiếp đón & Phân luồng" required autofocus>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="date" class="form-label fw-semibold">Ngày diễn ra <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" required value="<?= date('Y-m-d', strtotime($activity['date_start'])) ?>">
            </div>
            <div class="col-md-4">
                <label for="time_start" class="form-label fw-semibold">Giờ bắt đầu <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="time_start" name="time_start" required value="07:30">
            </div>
            <div class="col-md-4">
                <label for="time_end" class="form-label fw-semibold">Giờ kết thúc <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="time_end" name="time_end" required value="11:30">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="min_volunteers" class="form-label fw-semibold">Số lượng tối thiểu <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="min_volunteers" name="min_volunteers" min="1" value="2" required>
                <small class="text-muted">Ca được tính là thiếu người nếu chưa đạt mức này.</small>
            </div>
            <div class="col-md-6">
                <label for="max_volunteers" class="form-label fw-semibold">Số lượng tối đa <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="max_volunteers" name="max_volunteers" min="1" value="6" required>
                <small class="text-muted">Không được phân công vượt quá số lượng này.</small>
            </div>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label fw-semibold">Địa điểm cụ thể của ca</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($activity['location'] ?? '') ?>" placeholder="Để trống nếu giống địa điểm chung của hoạt động">
        </div>

        <!-- Yêu cầu Kỹ năng -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Kỹ năng yêu cầu cho ca trực</label>
            <div class="border rounded p-3 bg-light">
                <div class="row g-2">
                    <?php foreach ($skills as $sk): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $sk['id'] ?>" id="sk_<?= $sk['id'] ?>">
                                    <label class="form-check-label fw-semibold small" for="sk_<?= $sk['id'] ?>">
                                        <?= htmlspecialchars($sk['name']) ?>
                                    </label>
                                </div>
                                <div class="form-check form-switch m-0" title="Bắt buộc">
                                    <input class="form-check-input" type="checkbox" name="mandatory[<?= $sk['id'] ?>]" value="1" id="man_<?= $sk['id'] ?>" checked>
                                    <label class="form-check-label small text-danger" for="man_<?= $sk['id'] ?>">Bắt buộc</label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <small class="text-muted">Thuật toán sẽ loại bỏ bất kỳ ai thiếu các kỹ năng được đánh dấu "Bắt buộc".</small>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Mô tả công việc của ca</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Ghi chú cụ thể các đầu việc của ca này..."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-save me-1"></i> Lưu ca trực
            </button>
            <a href="<?= $baseUrl ?>/coordinator/activities/<?= $activity['id'] ?>/shifts" class="btn btn-outline-secondary px-3">Hủy</a>
        </div>
    </form>
</div>
