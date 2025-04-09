<?php
include '../php/config.php';

$ma_hang = $_GET['ma_hang'];

$sql = "DELETE FROM products WHERE MaHang = '$ma_hang'";

if ($con->query($sql) === TRUE) {
    echo "<script>alert('Xoá sản phẩm thành công!'); window.location.href='admin.php?page=shop';</script>";
} else {
    echo "<script>alert('Lỗi: " . $con->error . "'); window.location.href='admin.php?page=shop';</script>";
}

$con->close();
?>

