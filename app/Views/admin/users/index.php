<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-people text-danger me-2"></i>Quản Lý Tài Khoản Người Dùng</h1>
        <p class="text-muted small mb-0">Quản lý kích hoạt, khóa tài khoản và phân quyền vai trò người dùng</p>
    </div>

    <!-- Filter form -->
    <form action="<?= $baseUrl ?>/admin/users" method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm tên, email, SĐT...">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="role">
                <option value="">-- Tất cả vai trò --</option>
                <option value="volunteer" <?= $role === 'volunteer' ? 'selected' : '' ?>>Tình nguyện viên</option>
                <option value="coordinator" <?= $role === 'coordinator' ? 'selected' : '' ?>>Điều phối viên</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" name="status">
                <option value="">-- Trạng thái --</option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="locked" <?= $status === 'locked' ? 'selected' : '' ?>>Đã khóa</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm flex-grow-1">Lọc</button>
            <a href="<?= $baseUrl ?>/admin/users" class="btn btn-outline-secondary btn-sm">Đặt lại</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Quản lý trạng thái</th>
                    <th class="text-center">Phân vai trò</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($u['full_name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                        <td>
                            <?php 
                                $userRoles = explode(',', $u['role_names'] ?? '');
                                foreach ($userRoles as $rName):
                                    $rName = trim($rName);
                                    if (!$rName) continue;
                            ?>
                                <span class="badge <?= $rName === 'admin' ? 'bg-danger' : ($rName === 'coordinator' ? 'bg-primary' : 'bg-success') ?> me-1">
                                    <?= htmlspecialchars($rName) ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'active'): ?>
                                <span class="badge bg-success-soft text-success">Hoạt động</span>
                            <?php elseif ($u['status'] === 'locked'): ?>
                                <span class="badge bg-danger-soft text-danger">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-soft text-secondary"><?= htmlspecialchars($u['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <form action="<?= $baseUrl ?>/admin/users/<?= $u['id'] ?>/status" method="POST" class="d-inline">
                                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                <?php if ($u['status'] === 'active'): ?>
                                    <input type="hidden" name="status" value="locked">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Bạn có chắc chắn muốn khóa tài khoản này?">
                                        <i class="bi bi-lock me-1"></i> Khóa
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-unlock me-1"></i> Mở khóa
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="<?= $baseUrl ?>/admin/users/<?= $u['id'] ?>/role" method="POST" class="d-inline-flex gap-1">
                                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                <select name="role_id" class="form-select form-select-sm" style="width: auto;">
                                    <option value="2">Điều phối viên</option>
                                    <option value="1">Tình nguyện viên</option>
                                </select>
                                <button type="submit" name="action" value="add" class="btn btn-sm btn-outline-primary" title="Thêm vai trò">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <button type="submit" name="action" value="remove" class="btn btn-sm btn-outline-secondary" title="Gỡ vai trò" data-confirm="Gỡ vai trò này khỏi tài khoản?">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($lastPage > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $baseUrl ?>/admin/users?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
