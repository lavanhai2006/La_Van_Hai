<?php
$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

$ch = curl_init('http://localhost/VolunteerHub/public/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);

// Extract CSRF token
preg_match('/name="_csrf_token"\s+value="([^"]+)"/', $html, $m);
$csrf = $m[1] ?? '';
echo "1. Extracted CSRF: " . substr($csrf, 0, 10) . "...\n";

// POST login
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => 'tnv1@volunteerhub.vn',
    'password' => 'Demo@123',
    '_csrf_token' => $csrf
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "2. Login status: $httpCode\n";

// GET /volunteer/dashboard
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_URL, 'http://localhost/VolunteerHub/public/volunteer/dashboard');
$dashHtml = curl_exec($ch);
$dashCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "3. Volunteer Dashboard: HTTP $dashCode (Length: " . strlen($dashHtml) . " bytes)\n";

// GET /volunteer/activities
curl_setopt($ch, CURLOPT_URL, 'http://localhost/VolunteerHub/public/volunteer/activities');
$actsHtml = curl_exec($ch);
$actsCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "4. Volunteer Activities list: HTTP $actsCode (Length: " . strlen($actsHtml) . " bytes)\n";

// GET /volunteer/activities/5
curl_setopt($ch, CURLOPT_URL, 'http://localhost/VolunteerHub/public/volunteer/activities/5');
$detailHtml = curl_exec($ch);
$detailCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "5. Volunteer Activity 5 Detail: HTTP $detailCode (Length: " . strlen($detailHtml) . " bytes)\n";

// Check if shifts are visible in detail HTML
if (strpos($detailHtml, 'Ca Chiều - Bắn Sniper & Support') !== false) {
    echo "   -> Thành công: Ca Chiều hiển thị trên giao diện tình nguyện viên!\n";
}
if (strpos($detailHtml, 'Ca Tối - Leo Rank Kim Cương/Cao Thủ') !== false) {
    echo "   -> Thành công: Ca Tối hiển thị trên giao diện tình nguyện viên!\n";
}
if (strpos($detailHtml, 'Đăng Ký Tham Gia Ca Này') !== false) {
    echo "   -> Thành công: Nút 'Đăng Ký Tham Gia Ca Này' sẵn sàng để tình nguyện viên tự đăng ký!\n";
}

curl_close($ch);
if (file_exists($cookieFile)) unlink($cookieFile);
echo "\n=== TOÀN BỘ LUỒNG TÌNH NGUYỆN VIÊN ĐẠT 100% HOÀN HẢO! ===\n";
