<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <h1 class="h4 fw-bold mb-1">Hồ Sơ Cá Nhân</h1>
    <p class="text-muted small mb-4">Cập nhật thông tin chi tiết để ban tổ chức dễ dàng liên lạc và quản lý</p>

    <form action="<?= $baseUrl ?>/volunteer/profile" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label for="full_name" class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                <input type="email" class="form-control bg-light" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>
                <small class="text-muted">Email là định danh tài khoản, không thể thay đổi.</small>
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="student_id" class="form-label fw-semibold">Mã số sinh viên / Định danh</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($profile['student_id'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label for="faculty" class="form-label fw-semibold">Khoa / Đơn vị</label>
                <input type="text" class="form-control" id="faculty" name="faculty" value="<?= htmlspecialchars($profile['faculty'] ?? '') ?>" placeholder="VD: Công nghệ thông tin">
            </div>
            <div class="col-md-6">
                <label for="class_name" class="form-label fw-semibold">Lớp / Chi đoàn</label>
                <input type="text" class="form-control" id="class_name" name="class_name" value="<?= htmlspecialchars($profile['class_name'] ?? '') ?>" placeholder="VD: CNTT2022">
            </div>

            <div class="col-md-6">
                <label for="gender" class="form-label fw-semibold">Giới tính</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Nam</option>
                    <option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Nữ</option>
                    <option value="other" <?= ($profile['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="date_of_birth" class="form-label fw-semibold">Ngày sinh</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($profile['date_of_birth'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label for="address" class="form-label fw-semibold">Địa chỉ liên hệ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($profile['address'] ?? '') ?>" placeholder="Địa chỉ thường trú hoặc tạm trú">
            </div>

            <div class="col-12">
                <label for="bio" class="form-label fw-semibold">Giới thiệu bản thân / Kinh nghiệm hoạt động</label>
                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Chia sẻ đôi nét về bạn..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Lưu thông tin hồ sơ
                </button>
            </div>
        </div>
    </form>
</div>
