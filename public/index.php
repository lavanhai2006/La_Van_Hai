<?php
/**
 * public/index.php
 * Front Controller - Điểm vào duy nhất của ứng dụng
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/Views');

// Load autoloader
require_once BASE_PATH . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

// Load cấu hình (cũng load .env)
require_once BASE_PATH . '/config/app.php';

// Khởi động session an toàn
\App\Core\Session::start();

// Xử lý request
$router = new \App\Core\Router();
$router->loadRoutes(BASE_PATH . '/routes/web.php');
$router->dispatch();
