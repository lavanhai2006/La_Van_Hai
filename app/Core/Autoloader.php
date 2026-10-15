<?php
/**
 * app/Core/Autoloader.php
 * PSR-4 style autoloader cho namespace App\
 */

declare(strict_types=1);

namespace App\Core;

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            // App\Core\Router => app/Core/Router.php
            // App\Models\UserModel => app/Models/UserModel.php
            // App\Controllers\AuthController => app/Controllers/AuthController.php
            if (str_starts_with($class, 'App\\')) {
                $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
                $relative = str_replace('App\\', '', $class);
                $file = $basePath . '/app/' . str_replace('\\', '/', $relative) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });
    }
}
