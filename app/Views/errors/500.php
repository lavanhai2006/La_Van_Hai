<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="container text-center py-5 my-5">
    <div class="display-1 fw-bold text-secondary"><i class="bi bi-tools"></i> 500</div>
    <h2 class="h3 fw-bold mt-3">Lỗi Máy Chủ Nội Bộ</h2>
    <p class="text-muted mt-2">Hệ thống gặp sự cố trong quá trình xử lý yêu cầu. Vui lòng thử lại sau.</p>
    <a href="<?= $baseUrl ?>/" class="btn btn-success mt-3"><i class="bi bi-house me-1"></i> Trở về Trang Chủ</a>
</div>
