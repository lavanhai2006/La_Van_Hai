<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-people text-danger me-2"></i>Quản Lý Tài Khoản Người Dùng</h1>
        <p class="text-muted small mb-0">Quản lý kích hoạt, khóa tài khoản và phân quyền vai trò người dùng</p>
    </div>

    <form action="<?= $baseUrl ?>/admin/users" method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm tên, email, SĐT...">
            </div>
        </div>
        <div class="col-md-2">
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
        <div class="col-md-2">
            <select class="form-select" name="sort_by" aria-label="Sắp xếp tài khoản theo">
                <option value="created_at" <?= $sortBy === 'created_at' ? 'selected' : '' ?>>Ngày tạo</option>
                <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Họ và tên</option>
                <option value="email" <?= $sortBy === 'email' ? 'selected' : '' ?>>Email</option>
                <option value="status" <?= $sortBy === 'status' ? 'selected' : '' ?>>Trạng thái</option>
                <option value="last_login_at" <?= $sortBy === 'last_login_at' ? 'selected' : '' ?>>Lần đăng nhập</option>
            </select>
        </div>
        <div class="col-md-1">
            <select class="form-select" name="sort_direction" aria-label="Thứ tự sắp xếp">
                <option value="DESC" <?= $sortDirection === 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
                <option value="ASC" <?= $sortDirection === 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm flex-grow-1">Áp dụng</button>
            <a href="<?= $baseUrl ?>/admin/users" class="btn btn-outline-secondary btn-sm">Đặt lại</a>
        </div>
    </form>

    <form id="bulk-delete-users" action="<?= $baseUrl ?>/admin/users/delete" method="POST"
          data-confirm="Các tài khoản đã chọn và dữ liệu liên quan sẽ bị xóa vĩnh viễn. Bạn có chắc chắn muốn tiếp tục?">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    </form>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <span class="small text-muted">
            Đang hiển thị <?= count($users) ?> / <?= (int)$total ?> tài khoản
            · Đã chọn <strong data-selection-count="user-selection">0</strong>
        </span>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-clear-selection="user-selection">
                <i class="bi bi-x-circle me-1"></i>Bỏ chọn
            </button>
            <button type="submit" form="bulk-delete-users" class="btn btn-outline-danger btn-sm"
                    data-selection-submit="user-selection">
                <i class="bi bi-trash me-1"></i>Xóa tài khoản đã chọn
            </button>
            <form action="<?= $baseUrl ?>/admin/users/delete-all" method="POST"
                  data-confirm="Thao tác này sẽ xóa TẤT CẢ tài khoản và dữ liệu liên quan, ngoại trừ tài khoản admin đang đăng nhập. Hành động không thể hoàn tác. Bạn có chắc chắn muốn tiếp tục?">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash3-fill me-1"></i>Xóa tất cả tài khoản
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom mb-0">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" class="form-check-input" aria-label="Chọn tất cả tài khoản trên trang"
                               data-select-all="user-selection">
                    </th>
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
                        <td class="text-center">
                            <?php if ((int)$u['id'] !== $currentAdminId): ?>
                                <input type="checkbox" class="form-check-input" name="user_ids[]"
                                       value="<?= (int)$u['id'] ?>" form="bulk-delete-users" data-delete-user-id="<?= (int)$u['id'] ?>"
                                       aria-label="Chọn tài khoản <?= htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-select-item="user-selection">
                            <?php else: ?>
                                <input type="checkbox" class="form-check-input" disabled
                                       aria-label="Không thể chọn tài khoản quản trị viên đang đăng nhập">
                            <?php endif; ?>
                        </td>
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
                        <a class="page-link" href="<?= $baseUrl ?>/admin/users?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>&sort_by=<?= urlencode($sortBy) ?>&sort_direction=<?= urlencode($sortDirection) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
