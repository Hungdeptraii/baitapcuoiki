<?php
include '../php/config.php';

// Fetch the product details
if (isset($_GET['ma_hang'])) {
    $ma_hang = $_GET['ma_hang'];
    
    $sql = "SELECT * FROM products WHERE MaHang = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $ma_hang);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Product</h1>
    
    <form action="update_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ma_hang" value="<?php echo htmlspecialchars($product['MaHang']); ?>">

        <label for="ten_san_pham">Tên Sản Phẩm:</label>
        <input type="text" id="ten_san_pham" name="ten_san_pham" value="<?php echo htmlspecialchars($product['TenSanPham']); ?>" required><br>

        <label for="loai_hang">Loại Hàng:</label>
        <input type="text" id="loai_hang" name="loai_hang" value="<?php echo htmlspecialchars($product['LoaiHang']); ?>" required><br>

        <label for="ten_danh_muc">Danh Mục:</label>
        <input type="text" id="ten_danh_muc" name="ten_danh_muc" value="<?php echo htmlspecialchars($product['TenDanhMuc']); ?>" required><br>

        <label for="noi_san_xuat">Nơi Sản Xuất:</label>
        <input type="text" id="noi_san_xuat" name="noi_san_xuat" value="<?php echo htmlspecialchars($product['NoiSanXuat']); ?>" required><br>

        <label for="so_luong_nhap">Số Lượng Nhập:</label>
        <input type="number" id="so_luong_nhap" name="so_luong_nhap" value="<?php echo htmlspecialchars($product['SoLuongNhap']); ?>" required><br>

        <label for="gia_nhap">Giá Nhập:</label>
        <input type="number" step="0.01" id="gia_nhap" name="gia_nhap" value="<?php echo htmlspecialchars($product['GiaNhap']); ?>" required><br>

        <label for="ngay_nhap">Ngày Nhập:</label>
        <input type="date" id="ngay_nhap" name="ngay_nhap" value="<?php echo htmlspecialchars($product['NgayNhap']); ?>" required><br>

        <label for="nha_cung_cap">Nhà Cung Cấp:</label>
        <input type="text" id="nha_cung_cap" name="nha_cung_cap" value="<?php echo htmlspecialchars($product['NhaCungCap']); ?>" required><br>

        <label for="hinh_anh">Hình Ảnh:</label>
        <input type="file" id="hinh_anh" name="hinh_anh"><br>
        <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="Product Image" style="width:100px; height:auto;"><br>

        <input type="submit" value="Update Product">
    </form>

    <a href="admin.php?page=shop">Back to Products List</a>
</body>
</html>
