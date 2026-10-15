<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<h2 class="h4 fw-bold text-center mb-4 text-dark">Đăng Nhập Tài Khoản</h2>

<form action="<?= $baseUrl ?>/login" method="POST">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required autofocus>
        </div>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
    </button>
</form>

<!-- Quick Fill Demo Buttons -->
<div class="mt-4 pt-3 border-top">
    <div class="small fw-bold text-muted mb-2 text-center text-uppercase">Tài khoản demo nhanh:</div>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="fillLogin('admin@volunteerhub.vn', 'Admin@2024')">
            Admin
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillLogin('coord1@volunteerhub.vn', 'Coord@123')">
            Điều phối viên
        </button>
        <button type="button" class="btn btn-sm btn-outline-success" onclick="fillLogin('tnv1@volunteerhub.vn', 'Demo@123')">
            Tình nguyện viên
        </button>
    </div>
</div>

<div class="text-center mt-4">
    <p class="text-muted small mb-0">
        Chưa có tài khoản? <a href="<?= $baseUrl ?>/register" class="text-success fw-bold text-decoration-none">Đăng ký ngay</a>
    </p>
    <a href="<?= $baseUrl ?>/" class="text-secondary small d-inline-block mt-2 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Về trang chủ
    </a>
</div>

<script>
function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
