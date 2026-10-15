<?php
/**
 * app/Core/Router.php
 * Router đơn giản hỗ trợ GET/POST và tham số {id}
 */

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function loadRoutes(string $file): void
    {
        $this->routes = require $file;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Chuẩn hoá URI: loại bỏ /VolunteerHub/public hoặc /public
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($scriptDir && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '') $uri = '/';

        foreach ($this->routes as [$routeMethod, $pattern, $handler, $middleware]) {
            if ($routeMethod !== $method) continue;

            // Chuyển {id} thành regex
            $regex = preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // Bỏ phần tử đầu (full match)

                // Áp dụng middleware
                $this->applyMiddleware($middleware);

                // Gọi controller
                $this->callHandler($handler, $matches);
                return;
            }
        }

        // Không tìm thấy route -> 404
        http_response_code(404);
        $this->callHandler('ErrorController@notFound', []);
    }

    private function applyMiddleware(string $middleware): void
    {
        switch ($middleware) {
            case 'auth':
                if (!Session::isLoggedIn()) {
                    Session::flash('error', 'Vui lòng đăng nhập để tiếp tục.');
                    $this->redirect('/login');
                }
                break;

            case 'guest':
                if (Session::isLoggedIn()) {
                    $this->redirectByRole();
                }
                break;

            case 'volunteer':
                if (!Session::isLoggedIn()) {
                    Session::flash('error', 'Vui lòng đăng nhập.');
                    $this->redirect('/login');
                }
                if (!Session::hasRole('volunteer') && !Session::hasRole('admin')) {
                    $this->redirect('/403');
                }
                break;

            case 'coordinator':
                if (!Session::isLoggedIn()) {
                    Session::flash('error', 'Vui lòng đăng nhập.');
                    $this->redirect('/login');
                }
                if (!Session::hasRole('coordinator') && !Session::hasRole('admin')) {
                    $this->redirect('/403');
                }
                break;

            case 'admin':
                if (!Session::isLoggedIn()) {
                    Session::flash('error', 'Vui lòng đăng nhập với tài khoản quản trị.');
                    $this->redirect('/login');
                }
                if (!Session::hasRole('admin')) {
                    $this->redirect('/403');
                }
                break;

            case 'public':
            default:
                // Không cần kiểm tra
                break;
        }
    }

    private function callHandler(string $handler, array $params): void
    {
        [$class, $method] = explode('@', $handler);

        // Xác định namespace đầy đủ
        if (str_contains($class, '\\')) {
            $fullClass = 'App\\Controllers\\' . str_replace('/', '\\', $class);
        } else {
            $fullClass = 'App\\Controllers\\' . $class;
        }

        if (!class_exists($fullClass)) {
            error_log("[Router] Controller không tồn tại: $fullClass");
            http_response_code(500);
            echo '<h1>Lỗi 500</h1><p>Controller không tìm thấy: ' . htmlspecialchars($fullClass) . '</p>';
            return;
        }

        $controller = new $fullClass();
        if (!method_exists($controller, $method)) {
            error_log("[Router] Method không tồn tại: $fullClass::$method");
            http_response_code(500);
            echo '<h1>Lỗi 500</h1><p>Method không tìm thấy: ' . htmlspecialchars($method) . '</p>';
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }

    private function redirect(string $path): void
    {
        $base = rtrim(getenv('APP_URL') ?: 'http://localhost/VolunteerHub/public', '/');
        header("Location: $base$path");
        exit;
    }

    private function redirectByRole(): void
    {
        if (Session::hasRole('admin'))       $this->redirect('/admin/dashboard');
        elseif (Session::hasRole('coordinator')) $this->redirect('/coordinator/dashboard');
        else                                 $this->redirect('/volunteer/dashboard');
    }
}
