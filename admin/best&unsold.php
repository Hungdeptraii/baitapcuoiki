<!DOCTYPE html>
<html>
<head>
    <title>Tìm Kiếm Sản Phẩm</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<body>
    <h2>List of best-selling and unsold items over time.</h2>
    <form method="post" action="">
        <label for="startDate">Ngày bắt đầu:</label>
        <input type="date" id="startDate" name="startDate" required>
        <br><br>
        <label for="endDate">Ngày kết thúc:</label>
        <input type="date" id="endDate" name="endDate" required>
        <br><br>
        <input type="submit" name="submit" value="Xem kết quả">
    </form>

    <?php
     include '../php/config.php';
    // Kiểm tra kết nối
    if ($con->connect_error) {
        die("Kết nối thất bại: " . $con->connect_error);
    }

    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        // Lấy khoảng thời gian từ form
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];


        // Truy vấn danh sách mặt hàng bán chạy nhất
        $sqlBestSelling = "
            SELECT
                p.MaHang,
                p.TenSanPham,
                SUM(od.SoLuong) AS TongSoLuongBan
            FROM
                orders o
            JOIN
                orderdetails od ON o.SoDonHang = od.SoDonHang
            JOIN
                products p ON od.MaHang = p.MaHang
            WHERE
                o.NgayDatHang BETWEEN '$startDate' AND '$endDate'
            GROUP BY
                p.MaHang,
                p.TenSanPham
            ORDER BY
                TongSoLuongBan DESC
            LIMIT 10;
        ";

        $resultBestSelling = $con->query($sqlBestSelling);

        if ($resultBestSelling) {
            if ($resultBestSelling->num_rows > 0) {
                echo "<h2>Danh sách mặt hàng bán chạy nhất</h2>";
                echo "<table border='1'><tr><th>Mã hàng</th><th>Tên hàng</th><th>Tổng số lượng bán</th></tr>";
                while($row = $resultBestSelling->fetch_assoc()) {
                    echo "<tr><td>" . $row["MaHang"]. "</td><td>" . $row["TenSanPham"]. "</td><td>" . $row["TongSoLuongBan"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "Không có kết quả.";
            }
        } else {
            echo "Lỗi truy vấn SQL: " . $con->error;
        }

    // Truy vấn danh sách mặt hàng không bán được
    $sqlNotSold = "
            SELECT
                p.MaHang,
                p.TenSanPham
            FROM
                products p
            WHERE
                p.MaHang NOT IN (
                    SELECT
                        od.MaHang
                    FROM
                        orders o
                    JOIN
                        orderdetails od ON o.SoDonHang = od.SoDonHang
                    WHERE
                        o.NgayDatHang BETWEEN '$startDate' AND '$endDate'
                );
        ";

        $resultNotSold = $con->query($sqlNotSold);

        if ($resultNotSold) {
            if ($resultNotSold->num_rows > 0) {
                echo "<h2>Danh sách mặt hàng không bán được trong khoảng thời gian từ $startDate đến $endDate</h2>";
                echo "<table border='1'><tr><th>Mã hàng</th><th>Tên hàng</th></tr>";
                while($row = $resultNotSold->fetch_assoc()) {
                    echo "<tr><td>" . $row["MaHang"]. "</td><td>" . $row["TenSanPham"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                // Không hiển thị danh sách mặt hàng không bán được nếu không có kết quả
                echo "<h2>Không có mặt hàng nào không bán được trong khoảng thời gian từ $startDate đến $endDate</h2>";
            }
        } else {
            echo "Lỗi truy vấn SQL: " . $con->error;
        }




    $con->close();
    }
?>

</body>