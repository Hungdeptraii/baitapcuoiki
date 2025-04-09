<?php
// Include file kết nối
include '../php/config.php';

// Kiểm tra xem id có được cung cấp không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Chuẩn bị câu truy vấn xóa
    $sql = "DELETE FROM users WHERE id = $id";

    // Thực hiện câu truy vấn
    if ($con->query($sql) === TRUE) {
        echo "<script>alert('Xoá Users thành công!'); window.location.href='admin.php?page=users';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $con->error . "'); window.location.href='admin.php?page=users';</script>";
    }
} else {
    echo "Invalid ID.";
}
    
    $con->close();
    ?>
    

