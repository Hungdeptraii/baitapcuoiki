<?php
include '../php/config.php';

// Pagination settings
$limit = 10; // Number of entries to show in a page.   
if (isset($_GET["shoppage"])) {  
    $shoppage = $_GET["shoppage"];  
} else {  
    $shoppage = 1;  
};  
$start_from = ($shoppage - 1) * $limit;  

$sql = "SELECT * FROM products LIMIT $start_from, $limit";
$result = $con->query($sql);

// Count total records
$sql_total = "SELECT COUNT(*) FROM products";
$total_result = $con->query($sql_total);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm</title>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manage Products</h1>
    
    <h2>Add Products</h2>
    <form action="add_products.php" method="post" enctype="multipart/form-data">
        <label for="ma_hang">ID:</label>
        <input type="text" id="ma_hang" name="ma_hang" required><br>
        
        <label for="ten_san_pham">Tên Sản Phẩm:</label>
        <input type="text" id="ten_san_pham" name="ten_san_pham" required><br>
        
        <label for="loai_hang">Loại Hàng:</label>
        <input type="text" id="loai_hang" name="loai_hang" required><br>

        <label for="loai_hang">Danh Mục:</label>
        <input type="text" id="ten_danh_muc" name="ten_danh_muc" required><br>

        <label for="noi_san_xuat">Nơi Sản Xuất:</label>
        <input type="text" id="noi_san_xuat" name="noi_san_xuat" required><br>
        
        <label for="so_luong_nhap">Số Lượng Nhập:</label>
        <input type="number" id="so_luong_nhap" name="so_luong_nhap" required><br>
        
        <label for="gia_nhap">Giá Nhập:</label>
        <input type="number" step="0.01" id="gia_nhap" name="gia_nhap" required><br>
        
        <label for="ngay_nhap">Ngày Nhập:</label>
        <input type="date" id="ngay_nhap" name="ngay_nhap" required><br>
        
        <label for="nha_cung_cap">Nhà Cung Cấp:</label>
        <input type="text" id="nha_cung_cap" name="nha_cung_cap" required><br>

        <label for="mo_ta">Thông Số:</label>
        <textarea id="mo_ta" name="mo_ta" required></textarea><br>

        <label for="hinh_anh">Hình Ảnh:</label>
        <input type="file" id="hinh_anh" name="hinh_anh" required><br>
        
        <input type="submit" value="Thêm Sản Phẩm">
    </form>

    <h2>Products List</h2>
    <table border="1">
        <tr>
            <th>Danh Mục</th>
            <th>Mã Hàng</th>
            <th>Tên Sản Phẩm</th>
            <th>Loại Hàng</th>
            <th>Nơi Sản Xuất</th>
            <th>Số Lượng Nhập</th>
            <th>Giá Nhập($)</th>
            <th>Giảm Giá($)</th>
            <th>Ngày Nhập</th>
            <th>Nhà Cung Cấp</th>
            <th>Thông số</th>
            <th>Hình Ảnh</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["TenDanhMuc"]; ?></td>
                    <td><?php echo $row["MaHang"]; ?></td>
                    <td><?php echo $row["TenSanPham"]; ?></td>
                    <td><?php echo $row["LoaiHang"]; ?></td>
                    <td><?php echo $row["NoiSanXuat"]; ?></td>
                    <td><?php echo $row["SoLuongNhap"]; ?></td>
                    <td><?php echo $row["GiaNhap"]; ?></td>
                    <td><?php echo $row["GiamGia"]; ?></td>
                    <td><?php echo $row["NgayNhap"]; ?></td>
                    <td><?php echo $row["NhaCungCap"]; ?></td>
                    <td><?php echo nl2br($row["MoTa"]); ?></td>
                    <td>
                    <img src="<?php echo $row["HinhAnh"]; ?>" alt="Product Image" style="width:100px; height:auto;">
                    <td>
    <button class='edit-button'>
        <a href='edit_product.php?ma_hang=<?php echo $row["MaHang"]; ?>'>
            <ion-icon name="create-outline"></ion-icon>
        </a>
    </button>
    <button class='delete-button' onclick='confirmDelete("<?php echo $row["MaHang"]; ?>")'>
        <ion-icon name="trash-outline"></ion-icon>
    </button>
</td>

                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Không có sản phẩm nào.</td>
            </tr>
        <?php endif; ?>
        <div class="pagination">
    <?php if ($shoppage > 1): ?>
        <a href="?page=shop&shoppage=<?php echo $shoppage - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php if ($shoppage > 3): ?>
        <a href="?page=shop&shoppage=1">1</a>
        <?php if ($shoppage > 4): ?>
            <span>...</span>
        <?php endif; ?>
    <?php endif; ?>

    <?php for ($i = max(1, $shoppage - 2); $i <= min($total_pages, $shoppage + 2); $i++): ?>
        <a href="?page=shop&shoppage=<?php echo $i; ?>" class="<?php if ($i == $shoppage) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($shoppage < $total_pages - 2): ?>
        <?php if ($ushoppage < $total_pages - 3): ?>
            <span>...</span>
        <?php endif; ?>
        <a href="?page=shop&shoppage=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a>
    <?php endif; ?>

    <?php if ($shoppage < $total_pages): ?>
        <a href="?page=shop&shoppage=<?php echo $shoppage + 1; ?>">Next</a>
    <?php endif; ?>
</div>
    </table>

    <?php $con->close(); ?>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        function confirmDelete(maHang) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                window.location.href = 'delete_products.php?ma_hang=' + maHang;
            }
        }
    </script>
    
</body>
 
</html>
