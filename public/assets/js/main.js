/**
 * VolunteerHub - Custom JS
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Tự động khởi tạo Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 2. Tự động ẩn thông báo flash sau 5 giây
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // 3. Xử lý xác nhận trước các hành động quan trọng
    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        element.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm') || 'Bạn có chắc chắn muốn thực hiện hành động này?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
