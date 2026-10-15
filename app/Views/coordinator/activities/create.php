<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-calendar-plus text-success me-2"></i>Tạo Hoạt Động Tình Nguyện Mới</h1>
        <p class="text-muted small mb-0">Điền thông tin chương trình trước khi thêm các ca trực chi tiết</p>
    </div>

    <form action="<?= $baseUrl ?>/coordinator/activities/create" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Tên hoạt động <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" placeholder="VD: Chiến Dịch Mùa Hè Xanh 2024" required autofocus>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label fw-semibold">Địa điểm tổ chức</label>
            <input type="text" class="form-control" id="location" name="location" placeholder="VD: Sân trường ĐH, Nhà văn hóa Thanh Niên...">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="date_start" class="form-label fw-semibold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="date_start" name="date_start" required>
            </div>
            <div class="col-md-6">
                <label for="date_end" class="form-label fw-semibold">Thời gian kết thúc <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="date_end" name="date_end" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="registration_deadline" class="form-label fw-semibold">Hạn chót đăng ký</label>
                <input type="datetime-local" class="form-control" id="registration_deadline" name="registration_deadline">
                <small class="text-muted">Sau thời gian này, TNV sẽ không thể tự đăng ký.</small>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold">Trạng thái ban đầu</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft">Bản nháp (Draft - Chưa công khai)</option>
                    <option value="open" selected>Mở đăng ký ngay (Open)</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Mô tả chi tiết mục tiêu & nội dung hoạt động</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Giới thiệu về mục đích, đối tượng thụ hưởng và yêu cầu chung..."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-arrow-right-circle me-1"></i> Tạo hoạt động & Tiếp tục thêm ca trực
            </button>
            <a href="<?= $baseUrl ?>/coordinator/activities" class="btn btn-outline-secondary px-3">Hủy</a>
        </div>
    </form>
</div>
