<!DOCTYPE html>
<html>
<head>
    <title>Top Khách hàng</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<div class="container">
    <div class="top">
    <div class="table-container">
        <h1>Top 10 customers</h1>
        <?php
        include '../php/config.php';

        $sql = "
        SELECT 
            u.Id AS MaKhachHang,
            u.Username AS TenKhachHang,
            u.Address AS DiaChi,
            u.PhoneNumber AS SoDienThoai,
            o.SoDonHang,
            SUM(od.SoLuong * od.DonGia) AS GiaTriDonHang
        FROM 
            users u
        JOIN 
            orders o ON u.Id = o.MaKhachHang
        JOIN 
            orderdetails od ON o.SoDonHang = od.SoDonHang
        GROUP BY 
            u.Id, o.SoDonHang
        ORDER BY 
            GiaTriDonHang DESC
        LIMIT 10
        ";

        $result = $con->query($sql);

        if ($result === false) {
            echo "Error: " . $con->error;
        } else {
            echo "<table>";
            echo "<tr><th>ID</th><th>UserName</th><th>Address</th><th>PhoneNumber</th><th>OrderID</th><th>Toltal Price($)</th></tr>";
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["MaKhachHang"] . "</td>";
                    echo "<td>" . $row["TenKhachHang"] . "</td>";
                    echo "<td>" . $row["DiaChi"] . "</td>";
                    echo "<td>" . $row["SoDienThoai"] . "</td>";
                    echo "<td>" . $row["SoDonHang"] . "</td>";
                    echo "<td>" . $row["GiaTriDonHang"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có dữ liệu</td></tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
    <div class="info-container">
    <h2>Enter product ID</h2>
    <form method="post">
    <label for="MaHang">Product ID:</label>
    <input type="text" id="MaHang" name="MaHang"> <!-- name="MaHang" phải khớp với $_POST['MaHang'] -->
    <button type="submit">Search</button>
</form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kiểm tra xem biến $_POST['MaHang'] có tồn tại không
        if (isset($_POST['MaHang'])) {
            // Lấy mã hàng từ form
            $maHang = $_POST['MaHang'];
    
            // Tiếp tục xử lý với mã hàng đã lấy được
            // ...
        } else {
            echo "Không có dữ liệu được gửi từ form";
        }
    }
        

     // Câu truy vấn SQL để lấy mức giá trung bình, cao nhất và thấp nhất của mặt hàng
     $sql = "SELECT 
                 AVG(GiaNhap) AS GiaTrungBinh,
                 MAX(GiaNhap) AS GiaCaoNhat,
                 MIN(GiaNhap) AS GiaThapNhat
             FROM products
             WHERE MaHang = '$maHang'";
     $result = $con->query($sql);

     if ($result === false) {
         echo "Error: " . $con->error;
     } else {
         if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();
         $giaTrungBinh = $row['GiaTrungBinh'];
         $giaCaoNhat = $row['GiaCaoNhat'];
         $giaThapNhat = $row['GiaThapNhat'];
         
         // Hiển thị bảng thông tin
         echo "<h3>Product information : $maHang</h3>";
         echo "<table>";
         echo "<tr><th>AVG</th><th>MAX</th><th>MIN</th></tr>";
         echo "<tr><td>$giaTrungBinh</td><td>$giaCaoNhat</td><td>$giaThapNhat</td></tr>";
         echo "</table>";
     } else {
         echo "Không tìm thấy thông tin về mặt hàng có mã $maHang";
     }
       ?> 
    </div>
    </div>
    <div class="specific-table">
    
    <h1>Revenue OF Year And Month</h1>
        
    <?php

    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

echo '<div class="filters">
    <form method="GET" action="">
        <input type="hidden" name="page" value="' . htmlspecialchars($page) . '">
        <label for="year">Year:</label>
        <select id="year" name="year">';
for ($i = 2020; $i <= date('Y'); $i++) {
echo "<option value=\"$i\"" . ($i == $year ? ' selected' : '') . ">$i</option>";
}
echo '    </select>
        <label for="month">Month:</label>
        <select id="month" name="month">';
for ($i = 1; $i <= 12; $i++) {
echo "<option value=\"$i\"" . ($i == $month ? ' selected' : '') . ">$i</option>";
}
echo '    </select>
        <button type="submit">Lọc</button>
    </form>
  </div>';
    $sql = "
    SELECT
    p.LoaiHang AS LoaiMatHang,
    YEAR(o.NgayDatHang) AS Nam,
    MONTH(o.NgayDatHang) AS Thang,
    SUM(od.SoLuong * od.DonGia) AS DoanhThu
FROM
    orders o
JOIN
    orderdetails od ON o.SoDonHang = od.SoDonHang
JOIN
    products p ON od.MaHang = p.MaHang
WHERE
    YEAR(o.NgayDatHang) = $year
    AND MONTH(o.NgayDatHang) = $month
GROUP BY
    p.LoaiHang,
    YEAR(o.NgayDatHang),
    MONTH(o.NgayDatHang)
ORDER BY
    Nam, Thang, LoaiMatHang";

    $result = $con->query($sql);

    if ($result === false) {
        echo "Error: " . $con->error;
    } else {
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Products</th><th>Year</th><th>Month</th><th>Revenue($)</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["LoaiMatHang"] . "</td>";
                echo "<td>" . $row["Nam"] . "</td>";
                echo "<td>" . $row["Thang"] . "</td>";
                echo "<td>" . number_format($row["DoanhThu"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Không có dữ liệu</p>";
        }
    }
     // Đóng kết nối
     $con->close();
 }
 ?>
 </div>
</div>
</body>
</html>
