<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="row g-4">
    <!-- Form Gửi Yêu Cầu Báo Vắng -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h1 class="h4 fw-bold mb-1"><i class="bi bi-person-x text-danger me-2"></i>Báo Vắng & Xin Thay Người</h1>
            <p class="text-muted small mb-3">
                Nếu bạn bận đột xuất không thể tham gia ca trực đã phân công, hãy gửi lý do để Điều phối viên duyệt và bố trí người thay thế kịp thời.
            </p>

            <form action="<?= $baseUrl ?>/volunteer/replacement" method="POST">
                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

                <div class="mb-3">
                    <label for="assignment_id" class="form-label fw-semibold">Chọn ca trực báo vắng <span class="text-danger">*</span></label>
                    <select class="form-select" id="assignment_id" name="assignment_id" required>
                        <option value="">-- Chọn ca trực --</option>
                        <?php foreach ($myAssignments as $asgn): ?>
                            <?php if (!in_array($asgn['attendance_status'], ['absent', 'replaced'], true)): ?>
                                <option value="<?= $asgn['id'] ?>">
                                    <?= htmlspecialchars($asgn['shift_name']) ?> (<?= date('d/m/Y', strtotime($asgn['shift_date'])) ?>) - <?= htmlspecialchars($asgn['activity_title']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="reason" class="form-label fw-semibold">Lý do vắng mặt <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="VD: Bị sốt, trùng lịch thi học kỳ, có việc gia đình đột xuất..." required></textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-2">
                    <i class="bi bi-send me-1"></i> Gửi yêu cầu báo vắng
                </button>
            </form>
        </div>
    </div>

    <!-- Lịch Sử Các Yêu Cầu Báo Vắng -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h4 fw-bold mb-1"><i class="bi bi-clock-history text-secondary me-2"></i>Lịch Sử Yêu Cầu Báo Vắng</h2>
            <p class="text-muted small mb-3">Theo dõi tiến độ duyệt và người thay thế</p>

            <?php if (empty($requests)): ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-check2-circle fs-1 text-success"></i>
                    <p class="small mt-2 mb-0">Bạn chưa có yêu cầu báo vắng nào.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Ca trực</th>
                                <th>Lý do</th>
                                <th>Trạng thái</th>
                                <th>Người thay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($req['shift_name']) ?></div>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($req['shift_date'])) ?> (<?= substr($req['time_start'], 0, 5) ?>)</small>
                                    </td>
                                    <td class="small text-muted" style="max-width: 200px;">
                                        <?= htmlspecialchars($req['reason']) ?>
                                    </td>
                                    <td>
                                        <?php if ($req['status'] === 'approved'): ?>
                                            <span class="badge bg-success-soft text-success">Đã chấp thuận</span>
                                        <?php elseif ($req['status'] === 'rejected'): ?>
                                            <span class="badge bg-danger-soft text-danger">Bị từ chối</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-soft text-warning">Đang chờ duyệt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small">
                                        <?php if (!empty($req['replacement_name'])): ?>
                                            <span class="fw-bold text-success"><i class="bi bi-person-check me-1"></i><?= htmlspecialchars($req['replacement_name']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
