<!DOCTYPE html>
<html>
<head>
    <title>Search Customers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Search for customers who have purchased products by type and time..</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <label for="type">Product Type:</label>
        <input type="text" id="type" name="type" value="<?php echo isset($_GET['type']) ? $_GET['type'] : ''; ?>" required><br><br>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required><br><br>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required><br><br>
        <input type="hidden" name="page" value="1">
        <input type="submit" value="Search">
    </form>

    <?php
// Xử lý tìm kiếm khi có dữ liệu được submit từ form
if (isset($_GET['type']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {
    include '../php/config.php';

    $type = $_GET['type'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : "dashboard";
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT kh.Id, kh.Username, kh.Email, dh.NgayDatHang, sp.LoaiHang
            FROM Users kh
            JOIN Orders dh ON kh.Id = dh.MaKhachHang
            JOIN OrderDetails ctdh ON dh.SoDonHang = ctdh.SoDonHang
            JOIN Products sp ON ctdh.MaHang = sp.MaHang
            WHERE sp.LoaiHang = ?
            AND dh.NgayDatHang BETWEEN ? AND ?
            LIMIT ? OFFSET ?";

    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssii", $type, $startDate, $endDate, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Id</th><th>Username</th><th>Email</th><th>NgayDatHang</th><th>LoaiHang</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Id"] . "</td>";
                echo "<td>" . $row["Username"] . "</td>";
                echo "<td>" . $row["Email"] . "</td>";
                echo "<td>" . $row["NgayDatHang"] . "</td>";
                echo "<td>" . $row["LoaiHang"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Display pagination
            $count_sql = "SELECT COUNT(*) as total FROM Users kh
                          JOIN Orders dh ON kh.Id = dh.MaKhachHang
                          JOIN OrderDetails ctdh ON dh.SoDonHang = ctdh.SoDonHang
                          JOIN Products sp ON ctdh.MaHang = sp.MaHang
                          WHERE sp.LoaiHang = ?
                          AND dh.NgayDatHang BETWEEN ? AND ?";
            $count_stmt = $con->prepare($count_sql);
            $count_stmt->bind_param("sss", $type, $startDate, $endDate);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $total_records = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_records / $limit);

            echo "<div class='pagination'>";
            if ($page > 1) {
                echo "<a href='?type=$type&start_date=$startDate&end_date=$endDate&page=" . ($page - 1) . "'>Previous</a>";
            }
            
            // Display first page
            if ($page > 3) {
                echo "<a href='?type=$type&start_date=$startDate&end_date=$endDate&page=1'>1</a>";
                if ($page > 4) {
                    echo "<span>...</span>";
                }
            }
            
            // Display middle pages
            for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++) {
                if ($i == $page) {
                    echo "<span class='current'>$i</span>";
                } else {
                    echo "<a href='?type=$type&start_date=$startDate&end_date=$endDate&page=$i'>$i</a>";
                }
            }
            
            // Display last page
            if ($page < $total_pages - 2) {
                if ($page < $total_pages - 3) {
                    echo "<span>...</span>";
                }
                echo "<a href='?type=$type&start_date=$startDate&end_date=$endDate&page=$total_pages'>$total_pages</a>";
            }
            
            if ($page < $total_pages) {
                echo "<a href='?type=$type&start_date=$startDate&end_date=$endDate&page=" . ($page + 1) . "'>Next</a>";
            }
            echo "</div>";
        } else {
            echo "No results found.";
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }

    $con->close();
}
?>

</body>
</html>
