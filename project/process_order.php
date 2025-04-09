<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước khi đặt hàng.']);
    exit();
}

$userId = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["name"];
    $customer_email = $_POST["email"];
    $city = $_POST["city"];
    $district = $_POST["district"];
    $ward = $_POST["ward"];
    $address = $_POST["address"];
    $payment_method = $_POST["payment_method"];

    $full_address = "$ward, $district, $city, $address";

    // Kiểm tra người dùng có tồn tại không
    $userQuery = mysqli_query($con, "SELECT * FROM users WHERE Id = '$userId'");
    if (mysqli_num_rows($userQuery) == 0) {
        // Nếu người dùng không tồn tại, thêm người dùng mới vào bảng users
        $insertUserQuery = "INSERT INTO users (Id, Name, Email) VALUES ('$userId', '$customer_name', '$customer_email')";
        if (!mysqli_query($con, $insertUserQuery)) {
            echo json_encode(['success' => false, 'message' => "Error adding user: " . mysqli_error($con)]);
            exit();
        }
    }

    // Thêm đơn hàng vào bảng orders
    $insertOrderQuery = "INSERT INTO orders (MaKhachHang, TenKhachHang, NgayDatHang, DiaChiDatHang, StatusName, PhuongThucThanhToan) VALUES ('$userId', '$customer_name', CURDATE(), '$full_address', 'Chờ xử lí', '$payment_method')";
    if (mysqli_query($con, $insertOrderQuery)) {
        $order_id = mysqli_insert_id($con);

        // Lấy sản phẩm từ giỏ hàng
        $cartQuery = mysqli_query($con, "SELECT c.MaHang, c.SoLuong, c.Gia, p.TenSanPham 
                                         FROM cartshop c 
                                         INNER JOIN products p ON c.MaHang = p.MaHang 
                                         WHERE c.user_id='$userId'");

        $cartItems = array();

        if (mysqli_num_rows($cartQuery) > 0) {
            while ($item = mysqli_fetch_assoc($cartQuery)) {
                $cartItems[] = $item;
                $product_id = $item["MaHang"];
                $quantity = $item["SoLuong"];
                $price = $item["Gia"];
                $product_name = $item["TenSanPham"];

                // Thêm chi tiết đơn hàng vào bảng orderdetails
                $insertOrderDetailsQuery = "INSERT INTO orderdetails (SoDonHang, MaHang, SoLuong, DonGia, TenSanPham) VALUES ('$order_id', '$product_id', '$quantity', '$price', '$product_name')";
                if (!mysqli_query($con, $insertOrderDetailsQuery)) {
                    echo json_encode(['success' => false, 'message' => "Error inserting into orderdetails: " . mysqli_error($con)]);
                    exit();
                }
            }
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        $deleteCartQuery = "DELETE FROM cartshop WHERE user_id = '$userId'";
        if (mysqli_query($con, $deleteCartQuery)) {
            echo json_encode(['success' => true, 'cartItems' => $cartItems]);
        } else {
            echo json_encode(['success' => false, 'message' => "Error deleting cart: " . mysqli_error($con)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $insertOrderQuery . "<br>" . mysqli_error($con)]);
    }
}
?>
