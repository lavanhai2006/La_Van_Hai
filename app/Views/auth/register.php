<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<h2 class="h4 fw-bold text-center mb-4 text-dark">Đăng Ký Tình Nguyện Viên</h2>

<form action="<?= $baseUrl ?>/register" method="POST">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

    <div class="mb-3">
        <label for="full_name" class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nguyễn Văn A" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="0901234567">
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Tối thiểu 8 ký tự" minlength="8" required>
        </div>
    </div>

    <div class="mb-4">
        <label for="password_confirm" class="form-label fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Nhập lại mật khẩu" required>
        </div>
    </div>

    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
        <i class="bi bi-person-check-fill me-1"></i> Hoàn tất đăng ký
    </button>
</form>

<div class="text-center mt-4 pt-3 border-top">
    <p class="text-muted small mb-0">
        Đã có tài khoản? <a href="<?= $baseUrl ?>/login" class="text-success fw-bold text-decoration-none">Đăng nhập</a>
    </p>
    <a href="<?= $baseUrl ?>/" class="text-secondary small d-inline-block mt-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Về trang chủ
    </a>
</div>
