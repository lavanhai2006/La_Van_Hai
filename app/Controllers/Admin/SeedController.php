<?php
/**
 * app/Controllers/Admin/SeedController.php
 * Quản lý và tái tạo dữ liệu mẫu (Seed Data)
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;

class SeedController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $this->render('admin.seed.index', [
            'title'     => 'Quản Lý Dữ Liệu Mẫu (Demo Data) | VolunteerHub',
            'csrfToken' => Session::generateCsrf(),
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function run(): void
    {
        $this->verifyCsrf();

        // Chạy file seed.php
        $seedScript = BASE_PATH . '/database/seeders/seed.php';
        if (!file_exists($seedScript)) {
            $this->redirect('/admin/seed', ['error' => 'Không tìm thấy file seed.php']);
            return;
        }

        ob_start();
        include $seedScript;
        $output = ob_get_clean();

        $this->redirect('/admin/seed', [
            'success' => 'Đã tái tạo toàn bộ dữ liệu mẫu thành công! (30 TNV, 12 ca trực, 5 kỹ năng, 3 bộ dữ liệu A/B/C)'
        ]);
    }
}
