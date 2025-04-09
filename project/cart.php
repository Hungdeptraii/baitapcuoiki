<?php
session_start();

if (!isset($_SESSION['cart'])) {
    echo "Giỏ hàng của bạn đang trống.";
    exit();
}

foreach ($_SESSION['cart'] as $item) {
    echo "Mã hàng: " . $item['MaHang'] . "<br>";
    echo "Tên sản phẩm: " . $item['TenSanPham'] . "<br>";
    echo "Hình ảnh: <img src='" . $item['HinhAnh'] . "' alt='Hình ảnh sản phẩm'><br>";
    echo "Giá: " . $item['GiaNhap'] . "<br>";
    echo "Số lượng: " . $item['quantity'] . "<br><br>";
}
?>

