<?php
// Include file kết nối
include '../php/config.php';

// Xác định số lượng người dùng trên mỗi trang
$limit = 25;

// Xác định trang hiện tại, đảm bảo nó là số nguyên hợp lệ
$userpage = isset($_GET['userpage']) && is_numeric($_GET['userpage']) && $_GET['userpage'] > 0 ? (int)$_GET['userpage'] : 1;
$offset = ($userpage - 1) * $limit;

// Truy vấn dữ liệu với phân trang
$sql = "SELECT id, username, age, address, email, phonenumber,SoThe FROM users LIMIT $limit OFFSET $offset";
$result = $con->query($sql);

// Kiểm tra truy vấn
if ($result === false) {
    die("Lỗi truy vấn: " . $con->error);
}

// Tính tổng số trang
$total_sql = "SELECT COUNT(*) FROM users";
$total_result = $con->query($total_sql);

// Kiểm tra truy vấn
if ($total_result === false) {
    die("Lỗi truy vấn: " . $con->error);
}

$total_row = $total_result->fetch_row();
$total_customers = $total_row[0];
$total_pages = ceil($total_customers / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap">
    <style>
        :root {
            --light: #f6f6f9;
            --primary: #1976D2;
            --light-primary: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #363949;
            --danger: #D32F2F;
            --light-danger: #FECDD3;
            --warning: #FBC02D;
            --light-warning: #FFF2C6;
            --success: #388E3C;
            --light-success: #BBF7D0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
            color: var(--dark);
            padding: 20px;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 12px;
            text-align: center ;
            border-bottom: 1px solid var(--dark-grey);
        }

        table th {
            background: var(--light-primary);
            color: var(--dark);
        }

        table td {
            background: var(--light);
        }

        table tr:hover td {
            background: var(--light-primary);
        }

        table tr td button {
            background: var(--danger);
            color: var(--light);
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        table tr td button:hover {
            background: var(--dark-grey);
        }

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            color: var(--primary);
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid var(--dark-grey);
            margin: 0 4px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .pagination a.active {
            background: var(--primary);
            color: var(--light);
            border: none;
        }

        .pagination a:hover:not(.active) {
            background: var(--light-primary);
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 576px) {
            table {
                font-size: 12px;
            }
        }
    </style>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script></script>
    <script>
        function confirmDelete(userId) {
            if (confirm('Bạn có muốn xóa ursers này  không?')) {
                window.location.href = 'delete_users.php?id=' + userId;
            }
        }
    </script>
</head>
<body>

<h2>User List</h2>
<div class="pagination">
    <?php if ($userpage > 1): ?>
        <a href="?page=users&userpage=<?php echo $userpage - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php if ($userpage > 3): ?>
        <a href="?page=users&userpage=1">1</a>
        <?php if ($userpage > 4): ?>
            <span>...</span>
        <?php endif; ?>
    <?php endif; ?>

    <?php for ($i = max(1, $userpage - 2); $i <= min($total_pages, $userpage + 2); $i++): ?>
        <a href="?page=users&userpage=<?php echo $i; ?>" class="<?php if ($i == $userpage) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($userpage < $total_pages - 2): ?>
        <?php if ($userpage < $total_pages - 3): ?>
            <span>...</span>
        <?php endif; ?>
        <a href="?page=users&userpage=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a>
    <?php endif; ?>

    <?php if ($userpage < $total_pages): ?>
        <a href="?page=users&userpage=<?php echo $userpage + 1; ?>">Next</a>
    <?php endif; ?>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Cart</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Hiển thị dữ liệu từ mỗi dòng
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["age"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phonenumber"] . "</td>";
            echo "<td>" . $row["SoThe"] . "</td>";
            echo "<td><button onclick='confirmDelete(" . $row["id"] . ")'><ion-icon name='trash-outline'></ion-icon></button></td>";
echo "</tr>";

        }
    } else {
        echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
    }
    ?>
</table>


</body>
</html>

<?php
$con->close();
?>
