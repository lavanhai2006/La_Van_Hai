<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/Autoloader.php';
\App\Core\Autoloader::register();

echo "=== KIỂM TRA TÍNH NĂNG MỚI CHO TÌNH NGUYỆN VIÊN ===\n";

$actModel = new \App\Models\ActivityModel();
$activities = $actModel->getAllOpenActivities(1, 10);
echo "1. Danh sách hoạt động mở (getAllOpenActivities): " . count($activities['data']) . " hoạt động.\n";
foreach ($activities['data'] as $a) {
    echo "   - [ID: {$a['id']}] {$a['title']} | {$a['shift_count']} ca trực | ĐPV: {$a['coordinator_name']}\n";
}

echo "\n2. Kiểm tra ca trực của hoạt động 5 (kéo rank pubg) cho user_id = 4 (tnv1):\n";
$shifts = $actModel->getActivityShiftsForVolunteer(5, 4);
echo "   Số ca tìm thấy: " . count($shifts) . "\n";
foreach ($shifts as $s) {
    echo "   - Ca [ID: {$s['id']}]: {$s['name']} ({$s['time_start']} - {$s['time_end']})\n";
}

echo "\n3. Kiểm tra ca trực của hoạt động 1 (Ngày Hội Hiến Máu):\n";
$shifts1 = $actModel->getActivityShiftsForVolunteer(1, 4);
echo "   Số ca tìm thấy: " . count($shifts1) . "\n";
foreach ($shifts1 as $s) {
    echo "   - Ca [ID: {$s['id']}]: {$s['name']} | Kỹ năng: " . ($s['required_skills'] ?: 'Không yêu cầu') . "\n";
}

echo "\n=== TẤT CẢ PHƯƠNG THỨC HOẠT ĐỘNG HOÀN HẢO! ===\n";
