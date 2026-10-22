<?php
// ==============================================================================
// FILE KẾT NỐI THỦ CÔNG BẰNG MYSQLI (CHUẨN THEO ĐÚNG SLIDE BÀI GIẢNG)
// ==============================================================================

// 1. Kết nối cơ sở dữ liệu
// Cú pháp: mysqli_connect(host, username, password, database_name)
$conn = mysqli_connect("localhost", "volunteer_app", "VolHub_2026_Strong!", "volunteerhub");

// Kiểm tra kết nối
if (!$conn) {
    die("<h2 style='color: red;'>Kết nối thất bại: " . mysqli_connect_error() . "</h2>");
}

// Thiết lập tiếng Việt UTF-8 hiển thị không bị lỗi font
mysqli_set_charset($conn, "utf8mb4");

echo "<h2 style='color: green;'>✓ 1. Kết nối cơ sở dữ liệu MySQL thành công!</h2>";
echo "<p>Đang kết nối tới database: <strong>volunteerhub</strong> tại <strong>localhost</strong></p>";
echo "<hr>";

// ------------------------------------------------------------------------------
// 2. Truy vấn dữ liệu: Lấy danh sách Hoạt động (bảng activities)
// ------------------------------------------------------------------------------
echo "<h3>2 & 3. Truy vấn và Hiển thị bảng 'activities' (Hoạt động):</h3>";

$sql = "SELECT id, title, location, date_start, status FROM activities";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial;'>";
    echo "<tr style='background-color: #e8f5e9;'>";
    echo "<th>ID</th><th>Tên Hoạt Động</th><th>Địa Điểm</th><th>Ngày Bắt Đầu</th><th>Trạng Thái</th>";
    echo "</tr>";

    // 3. Hiển thị bằng vòng lặp while fetch_assoc giống hệt slide bài giảng
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($row['location'] ?? 'Chưa rõ') . "</td>";
        echo "<td>" . $row['date_start'] . "</td>";
        echo "<td><span style='color: green; font-weight: bold;'>" . $row['status'] . "</span></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Chưa có dữ liệu hoạt động.</p>";
}

echo "<hr>";

// ------------------------------------------------------------------------------
// Truy vấn dữ liệu: Lấy danh sách Người dùng (bảng users)
// ------------------------------------------------------------------------------
echo "<h3>Truy vấn và Hiển thị bảng 'users' (Tài khoản mẫu):</h3>";

$sql_users = "SELECT id, full_name, email, phone FROM users LIMIT 6";
$result_users = mysqli_query($conn, $sql_users);

echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial;'>";
echo "<tr style='background-color: #f1f1f1;'>";
echo "<th>ID</th><th>Họ và Tên</th><th>Email</th><th>Số Điện Thoại</th>";
echo "</tr>";

while ($user = mysqli_fetch_assoc($result_users)) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
    echo "<td>" . ($user['phone'] ?? 'Chưa cập nhật') . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";

// 4. Đóng kết nối
mysqli_close($conn);
echo "<p style='color: gray;'>✓ 4. Đã đóng kết nối cơ sở dữ liệu an toàn (mysqli_close).</p>";
?>
