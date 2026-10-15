<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Chỉnh Sửa Ca Trực</h1>
        <p class="text-muted small mb-0">Ca: <strong><?= htmlspecialchars($shift['name']) ?></strong> - Hoạt động: <?= htmlspecialchars($shift['activity_title']) ?></p>
    </div>

    <form action="<?= $baseUrl ?>/coordinator/shifts/<?= $shift['id'] ?>/edit" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Tên ca trực <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($shift['name']) ?>" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="date" class="form-label fw-semibold">Ngày diễn ra <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" required value="<?= htmlspecialchars($shift['date']) ?>">
            </div>
            <div class="col-md-4">
                <label for="time_start" class="form-label fw-semibold">Giờ bắt đầu <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="time_start" name="time_start" required value="<?= htmlspecialchars(substr($shift['time_start'], 0, 5)) ?>">
            </div>
            <div class="col-md-4">
                <label for="time_end" class="form-label fw-semibold">Giờ kết thúc <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="time_end" name="time_end" required value="<?= htmlspecialchars(substr($shift['time_end'], 0, 5)) ?>">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="min_volunteers" class="form-label fw-semibold">Số lượng tối thiểu <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="min_volunteers" name="min_volunteers" min="1" value="<?= (int)$shift['min_volunteers'] ?>" required>
            </div>
            <div class="col-md-4">
                <label for="max_volunteers" class="form-label fw-semibold">Số lượng tối đa <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="max_volunteers" name="max_volunteers" min="1" value="<?= (int)$shift['max_volunteers'] ?>" required>
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label fw-semibold">Trạng thái ca</label>
                <select class="form-select" id="status" name="status">
                    <option value="open" <?= $shift['status'] === 'open' ? 'selected' : '' ?>>Mở (Open)</option>
                    <option value="closed" <?= $shift['status'] === 'closed' ? 'selected' : '' ?>>Đóng (Closed)</option>
                    <option value="full" <?= $shift['status'] === 'full' ? 'selected' : '' ?>>Đã đủ (Full)</option>
                    <option value="cancelled" <?= $shift['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy (Cancelled)</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label fw-semibold">Địa điểm cụ thể</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($shift['location'] ?? '') ?>">
        </div>

        <!-- Yêu cầu Kỹ năng -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Kỹ năng yêu cầu cho ca trực</label>
            <div class="border rounded p-3 bg-light">
                <div class="row g-2">
                    <?php foreach ($skills as $sk): ?>
                        <?php 
                            $skId = (int)$sk['id'];
                            $isChecked = isset($currentSkillMap[$skId]);
                            $isMandatory = $isChecked ? ($currentSkillMap[$skId] === 1) : true;
                        ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $skId ?>" id="sk_<?= $skId ?>" <?= $isChecked ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold small" for="sk_<?= $skId ?>">
                                        <?= htmlspecialchars($sk['name']) ?>
                                    </label>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="mandatory[<?= $skId ?>]" value="1" id="man_<?= $skId ?>" <?= $isMandatory ? 'checked' : '' ?>>
                                    <label class="form-check-label small text-danger" for="man_<?= $skId ?>">Bắt buộc</label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Mô tả công việc của ca</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($shift['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Lưu thay đổi
            </button>
            <a href="<?= $baseUrl ?>/coordinator/activities/<?= $shift['activity_id'] ?>/shifts" class="btn btn-outline-secondary px-3">Quay lại</a>
        </div>
    </form>
</div>
