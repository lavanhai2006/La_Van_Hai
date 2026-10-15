<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="container text-center py-5 my-5">
    <div class="display-1 fw-bold text-warning"><i class="bi bi-question-circle"></i> 404</div>
    <h2 class="h3 fw-bold mt-3">Không Tìm Thấy Trang</h2>
    <p class="text-muted mt-2">Trang hoặc tài nguyên bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
    <a href="<?= $baseUrl ?>/" class="btn btn-success mt-3"><i class="bi bi-house me-1"></i> Trở về Trang Chủ</a>
</div>
