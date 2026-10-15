<?php
/**
 * app/Controllers/ErrorController.php
 * Xử lý trang lỗi 403, 404, 500
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    protected string $layout = 'main';

    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('errors.403', [
            'title' => '403 - Không Có Quyền Truy Cập | VolunteerHub'
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('errors.404', [
            'title' => '404 - Không Tìm Thấy Trang | VolunteerHub'
        ]);
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->render('errors.500', [
            'title' => '500 - Lỗi Máy Chủ Nội Bộ | VolunteerHub'
        ]);
    }
}
