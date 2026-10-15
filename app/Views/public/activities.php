<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="container py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold">Danh Sách Hoạt Động Tình Nguyện</h1>
        <p class="text-muted small">Khám phá và đăng ký tham gia các hoạt động cộng đồng ý nghĩa</p>
    </div>

    <?php if (empty($activities)): ?>
        <div class="card p-5 text-center text-muted">
            <i class="bi bi-calendar-x fs-1 mb-2"></i>
            <h5>Chưa có hoạt động nào đang mở đăng ký</h5>
            <p class="small">Vui lòng quay lại sau hoặc liên hệ với Điều phối viên.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($activities as $act): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 activity-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-success badge-status">Mở đăng ký</span>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($act['date_start'])) ?></small>
                            </div>
                            <h3 class="h5 card-title fw-bold text-dark mb-2"><?= htmlspecialchars($act['title']) ?></h3>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars(mb_strimwidth($act['description'] ?? '', 0, 120, '...')) ?>
                            </p>
                            <div class="border-top pt-3 mt-2 text-muted small">
                                <div class="mb-1"><i class="bi bi-geo-alt text-danger me-1"></i> <?= htmlspecialchars($act['location'] ?? 'Chưa cập nhật') ?></div>
                                <div class="mb-1"><i class="bi bi-person text-secondary me-1"></i> Điều phối: <?= htmlspecialchars($act['coordinator_name'] ?? 'BQL') ?></div>
                                <div class="mb-2"><i class="bi bi-grid text-primary me-1"></i> <?= (int)$act['shift_count'] ?> ca trực</div>
                                <a href="<?= $baseUrl ?>/activities/<?= $act['id'] ?>" class="btn btn-outline-success btn-sm w-100 mt-2">
                                    Xem chi tiết ca & Đăng ký <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pagination) && $pagination['last_page'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>/activities?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
