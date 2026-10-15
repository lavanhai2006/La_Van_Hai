<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1"><i class="bi bi-bell text-success me-2"></i>Thông Báo Của Bạn</h1>
            <p class="text-muted small mb-0">Cập nhật tin tức phân công, thay đổi ca trực và thông báo hệ thống</p>
        </div>
        <form action="<?= $baseUrl ?>/volunteer/notifications/read" method="POST" class="m-0">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="id" value="0">
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-check2-all me-1"></i> Đánh dấu đã đọc tất cả
            </button>
        </form>
    </div>

    <?php if (empty($notifications)): ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-bell-slash fs-1"></i>
            <h2 class="h5 mt-2">Không có thông báo mới</h2>
            <p class="small">Các cập nhật quan trọng về ca trực sẽ hiển thị tại đây.</p>
        </div>
    <?php else: ?>
        <div class="list-group list-group-flush">
            <?php foreach ($notifications as $n): ?>
                <div class="list-group-item px-0 py-3 <?= !$n['is_read'] ? 'bg-light rounded-3 px-3 my-1' : '' ?>">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                        <h2 class="h6 mb-0 fw-bold <?= !$n['is_read'] ? 'text-success' : 'text-dark' ?>">
                            <?php if (!$n['is_read']): ?>
                                <span class="badge bg-success me-1">Mới</span>
                            <?php endif; ?>
                            <?= htmlspecialchars($n['title']) ?>
                        </h2>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></small>
                    </div>
                    <p class="mb-2 text-muted small"><?= htmlspecialchars($n['message']) ?></p>
                    
                    <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($n['link'])): ?>
                            <a href="<?= $baseUrl . $n['link'] ?>" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.8rem;">
                                Xem chi tiết <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!$n['is_read']): ?>
                            <form action="<?= $baseUrl ?>/volunteer/notifications/read" method="POST" class="d-inline m-0">
                                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" style="font-size: 0.8rem;">
                                    Đánh dấu đã đọc
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
