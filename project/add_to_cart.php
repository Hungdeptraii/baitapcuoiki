<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước khi thêm sản phẩm vào giỏ hàng.']);
    exit();
}

$userId = $_SESSION['id'];

// Kiểm tra các khóa trong mảng $_POST
$requiredFields = ['MaHang', 'GiamGia', 'GiaNhap'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu sản phẩm.']);
        exit();
    }
}

$MaHang = $_POST['MaHang'];
$SoLuong = 1; // Mặc định số lượng là 1 khi thêm sản phẩm vào giỏ hàng
$Gia = $_POST['GiamGia'] > 0 ? $_POST['GiamGia'] : $_POST['GiaNhap'];
$TongTien = $SoLuong * $Gia;

// Lấy tên sản phẩm từ bảng products
$productQuery = mysqli_query($con, "SELECT TenSanPham FROM products WHERE MaHang='$MaHang'");
if (mysqli_num_rows($productQuery) == 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
    exit();
}

$productRow = mysqli_fetch_assoc($productQuery);
$TenSanPham = $productRow['TenSanPham'];

// Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
$checkQuery = mysqli_query($con, "SELECT * FROM cartshop WHERE user_id='$userId' AND MaHang='$MaHang'");
if (mysqli_num_rows($checkQuery) > 0) {
    // Nếu sản phẩm đã tồn tại, cập nhật số lượng và tổng tiền
    $updateQuery = mysqli_query($con, "UPDATE cartshop SET SoLuong=SoLuong+1, TongTien=SoLuong*Gia WHERE user_id='$userId' AND MaHang='$MaHang'");
    if ($updateQuery) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật giỏ hàng.']);
    }
} else {
    // Nếu sản phẩm chưa tồn tại, thêm sản phẩm vào giỏ hàng
    $insertQuery = mysqli_query($con, "INSERT INTO cartshop (user_id, MaHang, TenSanPham, SoLuong, Gia, TongTien) VALUES ('$userId', '$MaHang', '$TenSanPham', '$SoLuong', '$Gia', '$TongTien')");
    if ($insertQuery) {
        echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.']);
    }
}
?>
