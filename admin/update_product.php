<?php
include '../php/config.php';

// Directory where files will be uploaded
$target_dir = __DIR__ . "../uploads/";

// Ensure the uploads directory exists
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
        die("Không thể tạo thư mục uploads.");
    }
}

$ma_hang = $_POST['ma_hang'];
$ten_san_pham = $_POST['ten_san_pham'];
$loai_hang = $_POST['loai_hang'];
$ten_danh_muc = $_POST['ten_danh_muc'];
$noi_san_xuat = $_POST['noi_san_xuat'];
$so_luong_nhap = $_POST['so_luong_nhap'];
$gia_nhap = $_POST['gia_nhap'];
$ngay_nhap = $_POST['ngay_nhap'];
$nha_cung_cap = $_POST['nha_cung_cap'];

// Handle file upload
// Use a default value if 'existing_image' is not set
$hinh_anh = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';

if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == UPLOAD_ERR_OK) {
    $target_file = $target_dir . basename($_FILES["hinh_anh"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["hinh_anh"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File không phải là hình ảnh.'); window.location.href='admin.php?page=shop';</script>";
        exit();
    }

    // Check file size
    if ($_FILES["hinh_anh"]["size"] > 5000000) {
        echo "<script>alert('File quá lớn.'); window.location.href='admin.php?page=shop';</script>";
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Chỉ cho phép JPG, JPEG, PNG và GIF.'); window.location.href='admin.php?page=shop';</script>";
        exit();
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
            $hinh_anh = basename($_FILES["hinh_anh"]["name"]);
        } else {
            echo "<script>alert('Có lỗi khi tải lên tệp tin.'); window.location.href='admin.php?page=shop';</script>";
            exit();
        }
    }
}

// Begin transaction
$con->begin_transaction();

try {
    // Update products table
    $sql_products = "UPDATE products SET TenSanPham = ?, LoaiHang = ?, TenDanhMuc = ?, NoiSanXuat = ?, SoLuongNhap = ?, GiaNhap = ?, NgayNhap = ?, NhaCungCap = ?, HinhAnh = ? WHERE MaHang = ?";
    $stmt_products = $con->prepare($sql_products);
    
    if (!$stmt_products) {
        throw new Exception("Lỗi chuẩn bị câu lệnh SQL: " . $con->error);
    }

    // Bind parameters
    $stmt_products->bind_param('ssssidsiss', $ten_san_pham, $loai_hang, $ten_danh_muc, $noi_san_xuat, $so_luong_nhap, $gia_nhap, $ngay_nhap, $nha_cung_cap, $hinh_anh, $ma_hang);

    if (!$stmt_products->execute()) {
        throw new Exception("Lỗi cập nhật sản phẩm: " . $stmt_products->error);
    }

    // Update productsmanager table
    $sql_productsmanager = "UPDATE productsmanager SET SoLuongNhap = ?, GiaNhap = ?, NgayNhap = ?, NhaCungCap = ?, HinhAnh = ? WHERE MaHang = ?";
    $stmt_productsmanager = $con->prepare($sql_productsmanager);
    
    if (!$stmt_productsmanager) {
        throw new Exception("Lỗi chuẩn bị câu lệnh SQL cho bảng quản lý nhập hàng: " . $con->error);
    }

    // Bind parameters
    $stmt_productsmanager->bind_param('idsiss', $so_luong_nhap, $gia_nhap, $ngay_nhap, $nha_cung_cap, $hinh_anh, $ma_hang);

    if (!$stmt_productsmanager->execute()) {
        throw new Exception("Lỗi cập nhật bảng quản lý nhập hàng: " . $stmt_productsmanager->error);
    }

    // Commit transaction
    $con->commit();

    // Redirect to shop page
    header("Location: admin.php?page=shop");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $con->rollback();
    echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.location.href='admin.php?page=shop';</script>";
}

// Close connection
$con->close();
?>
