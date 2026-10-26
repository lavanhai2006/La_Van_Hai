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
        const confirmAction = function (e) {
            const message = this.getAttribute('data-confirm') || 'Bạn có chắc chắn muốn thực hiện hành động này?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        };

        if (element instanceof HTMLFormElement) {
            element.addEventListener('submit', confirmAction);
        } else {
            element.addEventListener('click', confirmAction);
        }
    });

    document.querySelectorAll('[data-select-all]').forEach(function (selectAll) {
        const group = selectAll.getAttribute('data-select-all');
        const items = document.querySelectorAll('[data-select-item="' + group + '"]');
        const count = document.querySelector('[data-selection-count="' + group + '"]');
        const submit = document.querySelector('[data-selection-submit="' + group + '"]');

        function updateSelection() {
            const selectedCount = document.querySelectorAll(
                '[data-select-item="' + group + '"]:checked'
            ).length;
            if (count) count.textContent = selectedCount;
            if (submit) submit.setAttribute('aria-label', 'Xóa ' + selectedCount + ' tài khoản đã chọn');

            selectAll.checked = items.length > 0 && selectedCount === items.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < items.length;
        }

        selectAll.addEventListener('change', function () {
            items.forEach(function (item) {
                item.checked = selectAll.checked;
            });
            updateSelection();
        });

        items.forEach(function (item) {
            item.addEventListener('change', updateSelection);
        });

        document.querySelectorAll('[data-clear-selection="' + group + '"]').forEach(function (clearButton) {
            clearButton.addEventListener('click', function () {
                items.forEach(function (item) {
                    item.checked = false;
                });
                updateSelection();
            });
        });

        updateSelection();
    });

    document.querySelectorAll('[data-selection-submit]').forEach(function (submitButton) {
        submitButton.addEventListener('click', function (event) {
            const group = submitButton.getAttribute('data-selection-submit');
            const selectedCount = document.querySelectorAll(
                '[data-select-item="' + group + '"]:checked'
            ).length;
            if (selectedCount === 0) {
                event.preventDefault();
                alert('Vui lòng chọn ít nhất một tài khoản để xóa.');
            }
        });
    });
});
