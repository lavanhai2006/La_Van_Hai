<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="container text-center py-5 my-5">
    <div class="display-1 fw-bold text-danger"><i class="bi bi-shield-lock"></i> 403</div>
    <h2 class="h3 fw-bold mt-3">Truy Cập Bị Từ Chối</h2>
    <p class="text-muted mt-2">Bạn không có quyền truy cập vào tài nguyên hoặc trang này.</p>
    <a href="<?= $baseUrl ?>/" class="btn btn-success mt-3"><i class="bi bi-house me-1"></i> Trở về Trang Chủ</a>
</div>
