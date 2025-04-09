<?php
include("../php/config.php");
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['valid'])) {
    header("Location: dangnhap.php");
    exit;
}

$id = $_SESSION['id'];

// Truy vấn đơn hàng từ bảng orders
$order_query = mysqli_query($con, "SELECT * FROM orders WHERE MaKhachHang='$id'");

$orders_by_status = [
    'tatca' => [],
    'choxuli' => [],
    'vanchuyen' => [],
    'chogiaohang' => [],
    'dagiao' => [],
    'dahuy' => [],
    'trahang' => [],
    'thanhtoankhinhanhang' => [],
    'thanhtoanbangthenh' => []
];

$order_counts = [
    'tatca' => 0,
    'choxuli' => 0,
    'vanchuyen' => 0,
    'chogiaohang' => 0,
    'dagiao' => 0,
    'dahuy' => 0,
    'trahang' => 0,
    'thanhtoankhinhanhang' => 0,
    'thanhtoanbangthenh' => 0
];

while ($order = mysqli_fetch_assoc($order_query)) {
    $orders_by_status['tatca'][] = $order;
    $order_counts['tatca']++;
    $status = $order['StatusName'];
    switch ($status) {
        case 'Chờ xử lí':
            $orders_by_status['choxuli'][] = $order;
            $order_counts['choxuli']++;
            break;
        case 'Vận chuyển':
            $orders_by_status['vanchuyen'][] = $order;
            $order_counts['vanchuyen']++;
            break;
        case 'Đang giao':
        case 'Chờ giao hàng':
            $orders_by_status['chogiaohang'][] = $order;
            $order_counts['chogiaohang']++;
            break;
        case 'Đã giao':
        case 'Hoàn thành':
            $orders_by_status['dagiao'][] = $order;
            $order_counts['dagiao']++;
            break;
        case 'Đã hủy':
            $orders_by_status['dahuy'][] = $order;
            $order_counts['dahuy']++;
            break;
        case 'Trả hàng/Hoàn tiền':
            $orders_by_status['trahang'][] = $order;
            $order_counts['trahang']++;
            break;
    }
}
?>





<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu đơn hàng</title>
   <!-- Bao gồm file CSS của bạn ở đây -->

    <link rel="shortcut icon" href="svg-2.svg" type="image/svg+xml">
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .table-btn.active {
            background-color: #ddd; /* Màu nền của tab được chọn */
        }
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #fff;
    background-image: url('CMC.jpg'); /* Replace with your image path */
    background-size: cover; /* Adjust to 'contain' if you prefer */
    background-repeat: no-repeat;
    background-position: center center;
    min-height: 100vh; /* Ensures the background covers at least the viewport height */
}


.header .container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #000;
    text-decoration: none; /* Remove underline */
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.order-tabs {
    display: flex;
    justify-content: space-around;
    margin-bottom: 20px;
}



.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.order-details {
    border: 1px solid #ccc;
    padding: 15px;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
}

th {
    background-color: #c58f63;
}

.header {
    display: flex;
    justify-content: center;
    padding: 10px 0;
}
/* Style for table buttons */
.table-btn {
    background-color:#c58f63;
  border: 1px solid #ddd;
  color: #333;
  padding: 8px 12px;
  margin: 0 5px;
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s;
}

.table-btn:hover {
    background-color: #ddd;
    color: #000;
}

/* Style for active table button */
.table-btn.active {
    background-color: var(--tan-crayola);
  color: var(--white); /* White text color */
}

#table {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
    </style>
</head>
<body>
<header class="header" data-header>
        <div class="container">
            <a href="home.php" class="logo">Ngoc Hung</a>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>Tra cứu đơn hàng</h1>
            <div class="order-tabs">
            <button class="table-btn active" onclick="openTab(event, 'tatca')">Tất cả <span class="count">(<?php echo $order_counts['tatca']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'choxuli')">Chờ xử lí <span class="count">(<?php echo $order_counts['choxuli']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'vanchuyen')">Vận chuyển <span class="count">(<?php echo $order_counts['vanchuyen']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'chogiaohang')">Chờ giao hàng <span class="count">(<?php echo $order_counts['chogiaohang']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'dagiao')">Đã giao <span class="count">(<?php echo $order_counts['dagiao']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'dahuy')">Đã hủy <span class="count">(<?php echo $order_counts['dahuy']; ?>)</span></button>
                <button class="table-btn" onclick="openTab(event, 'trahang')">Trả hàng/Hoàn tiền <span class="count">(<?php echo $order_counts['trahang']; ?>)</span></button>
            </div>

            <?php foreach ($orders_by_status as $status => $orders): ?>
                <div id="<?php echo $status; ?>" class="tab-content <?php echo $status == 'tatca' ? 'active' : ''; ?>">
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order-details">
                                <h2>Đơn hàng số: <?php echo $order['SoDonHang']; ?></h2>
                                <p><strong>Ngày đặt hàng:</strong> <?php echo $order['NgayDatHang']; ?></p>
                                <p><strong>Địa chỉ giao hàng:</strong> <?php echo $order['DiaChiDatHang']; ?></p>
                                <p><strong>Phương thức thanh toán:</strong> <?php echo $order['PhuongThucThanhToan']; ?></p>
                              
                                <h3>Chi tiết đơn hàng:</h3>
                                <?php
                                $order_id = $order['SoDonHang'];
                                $details_query = mysqli_query($con, "SELECT * FROM orderdetails WHERE SoDonHang='$order_id'");
                                if (mysqli_num_rows($details_query) > 0):
                                ?>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Đơn giá</th>
                                                <th>Tổng số tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($detail = mysqli_fetch_assoc($details_query)): ?>
                                                <tr>
                                                    <td><?php echo $detail['TenSanPham']; ?></td>
                                                    <td><?php echo $detail['SoLuong']; ?></td>
                                                    <td><?php echo number_format($detail['DonGia'], 2, '.', ','); ?></td>
                                                    <td><?php echo number_format($detail['TongSoTien'], 2, '.', ','); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>Không có chi tiết đơn hàng.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có đơn hàng trong trạng thái này.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        function openTab(evt, statusName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("table-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(statusName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        document.getElementById("tatca").classList.add("active");
    </script>
</body>
</html>
