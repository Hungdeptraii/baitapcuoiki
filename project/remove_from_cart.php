<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước khi xóa sản phẩm khỏi giỏ hàng.']);
    exit();
}

$userId = $_SESSION['id'];
$MaHang = $_POST['MaHang'];

$deleteQuery = mysqli_query($con, "DELETE FROM cartshop WHERE user_id='$userId' AND MaHang='$MaHang'");

if ($deleteQuery) {
    echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.']);
}
?>
