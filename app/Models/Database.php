<?php
/**
 * app/Models/Database.php
 * Singleton PDO Connection - Kết nối CSDL duy nhất trong toàn ứng dụng
 */

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Lấy kết nối PDO (Singleton pattern)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );
            try {
                self::$instance = new PDO($dsn, $config['user'], $config['pass'], $config['options']);
            } catch (PDOException $e) {
                // Ghi log nhưng không lộ thông tin nhạy cảm ra giao diện
                error_log('[DB ERROR] ' . $e->getMessage());
                http_response_code(500);
                die('<h1>Lỗi hệ thống</h1><p>Không thể kết nối cơ sở dữ liệu. Vui lòng thử lại sau.</p>');
            }
        }
        return self::$instance;
    }

    // Chặn clone và unserialize
    private function __clone() {}
    private function __construct() {}
}
