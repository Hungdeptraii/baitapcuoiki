<?php
// Kết nối cơ sở dữ liệu
include("../php/config.php");

// Kiểm tra kết nối
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Nhận giá trị tìm kiếm từ yêu cầu AJAX
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Bảo vệ chống SQL Injection
$search = $con->real_escape_string($search);

// Tạo truy vấn tìm kiếm gợi ý
$sql = "SELECT MaHang, TenSanPham, GiaNhap, HinhAnh FROM products WHERE TenSanPham LIKE '%$search%' LIMIT 10";
$result = $con->query($sql);

// Kiểm tra kết quả truy vấn
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggestion-item' data-mahang='" . htmlspecialchars($row['MaHang']) . "'>";

            echo "<img src='" . htmlspecialchars($row['HinhAnh']) . "' alt='" . htmlspecialchars($row['TenSanPham']) . "' class='product-image' />";
            echo "<span class='product-name'>" . htmlspecialchars($row['TenSanPham']) . "</span>";
            echo "<span class='product-price'>" . number_format($row['GiaNhap'], 0, ',', '.') . " đ</span>";
            echo "</div>";
        }
    } else {
        echo "<div class='suggestion-item'>No suggestions found</div>";
    }
} else {
    // In ra lỗi truy vấn SQL nếu có
    echo "Error: " . $con->error;
}

// Đóng kết nối
$con->close();
?>
