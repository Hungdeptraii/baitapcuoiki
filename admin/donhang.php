<?php
// Include file kết nối
include '../php/config.php';

// Xác định số lượng người dùng trên mỗi trang
$limit = 25;

// Xác định trang hiện tại, đảm bảo nó là số nguyên hợp lệ
$ticketspage = isset($_GET['ticketspage']) && is_numeric($_GET['ticketspage']) && $_GET['ticketspage'] > 0 ? (int)$_GET['ticketspage'] : 1;
$offset = ($ticketspage - 1) * $limit;

if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];

    // Xóa các bản ghi liên quan trong orderdetails
    $delete_details_sql = "DELETE FROM orderdetails WHERE SoDonHang = $delete_id";
    $delete_details_result = $con->query($delete_details_sql);

    if ($delete_details_result === false) {
        die("Lỗi khi xóa chi tiết đơn hàng: " . $con->error);
    }

    // Xóa đơn hàng trong orders
    $delete_order_sql = "DELETE FROM orders WHERE SoDonHang = $delete_id";
    $delete_order_result = $con->query($delete_order_sql);

    if ($delete_order_result === false) {
        die("Lỗi khi xóa đơn hàng: " . $con->error);
    } else {
        echo "Đơn hàng đã được xóa thành công.";
    }
}

// Tính tổng số trang
$total_sql = "SELECT COUNT(*) FROM orders";
$total_result = $con->query($total_sql);

// Kiểm tra truy vấn
if ($total_result === false) {
    die("Lỗi truy vấn: " . $con->error);
}

$total_row = $total_result->fetch_row();
$total_orders = $total_row[0];
$total_pages = ceil($total_orders / $limit);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đơn hàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Danh sách đơn hàng</h1>
    <table>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Mã khách hàng</th>
            <th>Mã Hàng</th>
            <th>Tên Sản Phẩm</th>
            <th>Ngày đặt hàng</th>
            <th>Địa chỉ đặt hàng</th>
            <th>Số Lượng</th>
            <th>Đơn giá</th>
            <th>Tổng số tiền</th>
            <th>Trạng Thái</th>
            <th>Hành động</th>
        </tr>
        <?php
        // Cập nhật tên sản phẩm trong orderdetails từ bảng products
        $sql_update = "
        UPDATE orderdetails od
        INNER JOIN products p ON od.MaHang = p.MaHang
        SET od.TenSanPham = p.TenSanPham
        ";
        
        if ($con->query($sql_update) !== TRUE) {
            die("Lỗi khi cập nhật: " . $con->error);
        }

        // Truy vấn dữ liệu với phân trang
        $sql_select = "
        SELECT 
            orders.SoDonHang, 
            orders.MaKhachHang, 
            orders.NgayDatHang, 
            orders.DiaChiDatHang,
            orders.StatusName,
            orderdetails.SoLuong, 
            orderdetails.DonGia, 
            (orderdetails.SoLuong * orderdetails.DonGia) AS TongSoTien,
            orderdetails.MaHang,
            orderdetails.TenSanPham
        FROM 
            orders 
        JOIN 
            orderdetails ON orders.SoDonHang = orderdetails.SoDonHang 
        LIMIT 
            $limit 
        OFFSET 
            $offset
        ";

        $result = $con->query($sql_select);

        if ($result === false) {
            die("Lỗi truy vấn: " . $con->error);
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["SoDonHang"] . "</td>";
                echo "<td>" . $row["MaKhachHang"] . "</td>";
                echo "<td>" . $row["MaHang"] . "</td>";
                echo "<td>" . $row["TenSanPham"] . "</td>";
                echo "<td>" . $row["NgayDatHang"] . "</td>";
                echo "<td>" . $row["DiaChiDatHang"] . "</td>";
                echo "<td>" . $row["SoLuong"] . "</td>";
                echo "<td>" . $row["DonGia"] . "</td>";
                echo "<td>" . $row["TongSoTien"] . "</td>";
                echo "<td>" . $row["StatusName"] . "</td>";
                echo "<td>
                        <form method='post' action=''>
                            <input type='hidden' name='delete_id' value='" . $row["SoDonHang"] . "'>
                            <input type='submit' value='DELETE' onclick='return confirm(\"Bạn có chắc chắn muốn xóa đơn hàng này?\")'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>Không có đơn hàng nào.</td></tr>";
        }
        ?>
    </table>
    <div class="pagination">
    <?php if ($ticketspage > 1): ?>
        <a href="?page=tickets&ticketspage=<?php echo $ticketspage - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php if ($ticketspage > 3): ?>
        <a href="?page=tickets&ticketspage=1">1</a>
        <?php if ($ticketspage > 4): ?>
            <span>...</span>
        <?php endif; ?>
    <?php endif; ?>

    <?php for ($i = max(1, $ticketspage - 2); $i <= min($total_pages, $ticketspage + 2); $i++): ?>
        <a href="?page=tickets&ticketspage=<?php echo $i; ?>" class="<?php if ($i == $ticketspage) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($ticketspage < $total_pages - 2): ?>
        <?php if ($ticketspage < $total_pages - 3): ?>
            <span>...</span>
        <?php endif; ?>
        <a href="?page=tickets&ticketspage=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a>
    <?php endif; ?>

    <?php if ($ticketspage < $total_pages): ?>
        <a href="?page=tickets&ticketspage=<?php echo $ticketspage + 1; ?>">Next</a>
    <?php endif; ?>
</div>
</body>
</html>
<?php
$con->close(); // Đóng kết nối đến cơ sở dữ liệu
?>
