<?php
include '../php/config.php';

$sql = "SELECT ReturnID, NgayTraHang, Reason, SoDonHang, Quantity, MaKhachHang FROM Returns";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Returns Management</title>
    <link rel="stylesheet" href="style.css">
</head>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Returns Management</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Return Date</th>
                        <th>Reason</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Customer ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["ReturnID"] . "</td>";
                            echo "<td>" . $row["NgayTraHang"] . "</td>";
                            echo "<td>" . $row["Reason"] . "</td>";
                            echo "<td>" . $row["SoDonHang"] . "</td>";
                            echo "<td>" . $row["Quantity"] . "</td>";
                            echo "<td>" . $row["MaKhachHang"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No returns found</td></tr>";
                    }
                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
