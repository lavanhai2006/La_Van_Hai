<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 500px;">
    <h1 class="h4 fw-bold mb-1"><i class="bi bi-key text-success me-2"></i>Đổi Mật Khẩu</h1>
    <p class="text-muted small mb-4">Để bảo vệ tài khoản, hãy sử dụng mật khẩu mạnh tối thiểu 8 ký tự</p>

    <form action="<?= $baseUrl ?>/volunteer/change-password" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="mb-3">
            <label for="current_password" class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" required>
            <small class="text-muted">Tối thiểu 8 ký tự</small>
        </div>

        <div class="mb-4">
            <label for="confirm_password" class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2">
            <i class="bi bi-shield-check me-1"></i> Cập nhật mật khẩu
        </button>
    </form>
</div>
