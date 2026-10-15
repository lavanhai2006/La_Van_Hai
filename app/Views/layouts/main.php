<?php
/**
 * Layout công khai (Public)
 */
use App\Core\Session;
$isLoggedIn = Session::isLoggedIn();
$userName   = Session::userName();
$baseUrl    = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'VolunteerHub - Nền Tảng Tình Nguyện') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>
    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $baseUrl ?>/">
                <i class="bi bi-heart-pulse-fill logo-icon fs-3"></i>
                <span>Volunteer<span class="text-dark">Hub</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="<?= $baseUrl ?>/"><i class="bi bi-house me-1"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="<?= $baseUrl ?>/activities"><i class="bi bi-calendar-event me-1"></i> Hoạt động</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($isLoggedIn): ?>
                        <?php if (Session::hasRole('admin')): ?>
                            <a href="<?= $baseUrl ?>/admin/dashboard" class="btn btn-outline-danger btn-sm"><i class="bi bi-shield-lock me-1"></i> Quản trị Admin</a>
                        <?php elseif (Session::hasRole('coordinator')): ?>
                            <a href="<?= $baseUrl ?>/coordinator/dashboard" class="btn btn-outline-primary btn-sm"><i class="bi bi-kanban me-1"></i> Trang Điều phối</a>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>/volunteer/dashboard" class="btn btn-outline-success btn-sm"><i class="bi bi-speedometer2 me-1"></i> Trang Tình nguyện viên</a>
                        <?php endif; ?>
                        
                        <form action="<?= $baseUrl ?>/logout" method="POST" class="d-inline m-0">
                            <input type="hidden" name="_csrf_token" value="<?= Session::generateCsrf() ?>">
                            <button type="submit" class="btn btn-light btn-sm text-secondary" title="Đăng xuất"><i class="bi bi-box-arrow-right"></i></button>
                        </form>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>/login" class="btn btn-outline-success btn-sm px-3">Đăng nhập</a>
                        <a href="<?= $baseUrl ?>/register" class="btn btn-success btn-sm px-3">Đăng ký tham gia</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Body Content -->
    <main class="flex-grow-1">
        <?php if (!empty($success)): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <span class="fw-bold text-success">VolunteerHub</span> &copy; <?= date('Y') ?> - Hệ thống Quản lý và Phân công Tình nguyện viên.
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    Đồ án Phát triển Phần mềm Mã nguồn mở
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>
