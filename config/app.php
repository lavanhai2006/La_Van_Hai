<?php
/**
 * config/app.php
 * Cấu hình chính của ứng dụng VolunteerHub
 * Đọc từ file .env
 */

declare(strict_types=1);

// Đọc file .env
function loadEnv(string $path): void {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if (!array_key_exists($key, $_ENV) && !array_key_exists($key, $_SERVER)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Load .env từ thư mục gốc dự án
loadEnv(dirname(__DIR__) . '/.env');

// Thiết lập timezone
$tz = getenv('APP_TIMEZONE') ?: 'Asia/Ho_Chi_Minh';
date_default_timezone_set($tz);

// Bật báo lỗi trong development
if (getenv('APP_DEBUG') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Thiết lập encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

return [
    'name'     => getenv('APP_NAME') ?: 'VolunteerHub',
    'url'      => getenv('APP_URL')  ?: 'http://localhost/VolunteerHub/public',
    'env'      => getenv('APP_ENV')  ?: 'development',
    'debug'    => getenv('APP_DEBUG') === 'true',
    'timezone' => $tz,
];
