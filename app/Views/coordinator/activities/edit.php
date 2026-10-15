<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Chỉnh Sửa Hoạt Động</h1>
        <p class="text-muted small mb-0">Cập nhật thông tin hoặc thay đổi trạng thái của hoạt động</p>
    </div>

    <form action="<?= $baseUrl ?>/coordinator/activities/<?= $activity['id'] ?>/edit" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Tên hoạt động <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($activity['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label fw-semibold">Địa điểm tổ chức</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($activity['location'] ?? '') ?>">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="date_start" class="form-label fw-semibold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="date_start" name="date_start" value="<?= date('Y-m-d\TH:i', strtotime($activity['date_start'])) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="date_end" class="form-label fw-semibold">Thời gian kết thúc <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="date_end" name="date_end" value="<?= date('Y-m-d\TH:i', strtotime($activity['date_end'])) ?>" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="registration_deadline" class="form-label fw-semibold">Hạn chót đăng ký</label>
                <input type="datetime-local" class="form-control" id="registration_deadline" name="registration_deadline" value="<?= !empty($activity['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($activity['registration_deadline'])) : '' ?>">
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft" <?= $activity['status'] === 'draft' ? 'selected' : '' ?>>Bản nháp (Draft)</option>
                    <option value="open" <?= $activity['status'] === 'open' ? 'selected' : '' ?>>Mở đăng ký (Open)</option>
                    <option value="closed" <?= $activity['status'] === 'closed' ? 'selected' : '' ?>>Đóng đăng ký (Closed)</option>
                    <option value="ongoing" <?= $activity['status'] === 'ongoing' ? 'selected' : '' ?>>Đang diễn ra (Ongoing)</option>
                    <option value="completed" <?= $activity['status'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành (Completed)</option>
                    <option value="cancelled" <?= $activity['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy (Cancelled)</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Mô tả chi tiết</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Lưu thay đổi
            </button>
            <a href="<?= $baseUrl ?>/coordinator/activities" class="btn btn-outline-secondary px-3">Quay lại</a>
        </div>
    </form>
</div>
