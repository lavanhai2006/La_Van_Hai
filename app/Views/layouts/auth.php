<?php
/**
 * Layout cho trang Đăng nhập / Đăng ký
 */
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Xác Thực | VolunteerHub') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            overflow: hidden;
            max-width: 480px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center mb-4">
            <a href="<?= $baseUrl ?>/" class="text-decoration-none">
                <div class="d-inline-flex align-items-center gap-2">
                    <i class="bi bi-heart-pulse-fill text-success fs-1"></i>
                    <span class="fs-2 fw-bold text-success">Volunteer<span class="text-dark">Hub</span></span>
                </div>
            </a>
            <p class="text-muted mt-1 small">Hệ thống Tổ chức Hoạt động & Phân công Tình nguyện viên</p>
        </div>

        <div class="mx-auto auth-card p-4 p-md-5">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i><?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>
