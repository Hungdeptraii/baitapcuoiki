<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước khi cập nhật số lượng sản phẩm trong giỏ hàng.']);
    exit();
}

$userId = $_SESSION['id'];
$MaHang = isset($_POST['MaHang']) ? mysqli_real_escape_string($con, $_POST['MaHang']) : '';
$newQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

if ($newQuantity <= 0 || empty($MaHang)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
    exit();
}

$updateQuery = mysqli_query($con, "UPDATE cartshop SET SoLuong='$newQuantity' WHERE user_id='$userId' AND MaHang='$MaHang'");

if ($updateQuery) {
    echo json_encode(['success' => true, 'message' => 'Cập nhật số lượng sản phẩm trong giỏ hàng thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật số lượng sản phẩm trong giỏ hàng: ' . mysqli_error($con)]);
}
?>
