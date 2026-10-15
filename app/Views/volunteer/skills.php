<?php
$baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
?>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 800px;">
    <h1 class="h4 fw-bold mb-1">Quản Lý Kỹ Năng Của Bạn</h1>
    <p class="text-muted small mb-4">
        Hệ thống sẽ sử dụng kỹ năng bạn khai báo để tự động gợi ý và phân công vào các ca trực yêu cầu chuyên môn tương ứng.
    </p>

    <form action="<?= $baseUrl ?>/volunteer/skills" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">Chọn</th>
                        <th>Kỹ năng</th>
                        <th>Danh mục & Mô tả</th>
                        <th style="width: 180px;">Trình độ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allSkills as $sk): ?>
                        <?php 
                            $skId = (int)$sk['id'];
                            $isChecked = isset($mySkillsMap[$skId]);
                            $currentLevel = $mySkillsMap[$skId] ?? 'beginner';
                        ?>
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $skId ?>" id="sk_<?= $skId ?>" <?= $isChecked ? 'checked' : '' ?>>
                            </td>
                            <td>
                                <label class="form-check-label fw-bold text-dark" for="sk_<?= $skId ?>">
                                    <?= htmlspecialchars($sk['name']) ?>
                                </label>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($sk['category'] ?? 'Chung') ?></span>
                                <div class="small text-muted mt-1"><?= htmlspecialchars($sk['description'] ?? '') ?></div>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="levels[<?= $skId ?>]">
                                    <option value="beginner" <?= $currentLevel === 'beginner' ? 'selected' : '' ?>>Cơ bản (Beginner)</option>
                                    <option value="intermediate" <?= $currentLevel === 'intermediate' ? 'selected' : '' ?>>Trung bình (Intermediate)</option>
                                    <option value="advanced" <?= $currentLevel === 'advanced' ? 'selected' : '' ?>>Thành thạo (Advanced)</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success px-4">
            <i class="bi bi-check2-all me-1"></i> Lưu danh sách kỹ năng
        </button>
    </form>
</div>
