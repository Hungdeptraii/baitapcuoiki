<?php
$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Kiểm tra nếu là file hình ảnh thật
if(isset($_POST["submit"]) && isset($_FILES["fileToUpload"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File là hình ảnh - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File không phải là hình ảnh.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu file đã tồn tại
    if (file_exists($target_file)) {
        echo "File đã tồn tại.";
        $uploadOk = 0;
    }

    // Giới hạn kích thước file
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "File quá lớn.";
        $uploadOk = 0;
    }

    // Giới hạn định dạng file
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu $uploadOk là 0 do lỗi
    if ($uploadOk == 0) {
        echo "File của bạn không được upload.";
    // Nếu mọi thứ ổn, tiến hành upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "File ". basename( $_FILES["fileToUpload"]["name"]). " đã được upload.";
            
            // Lấy ID sản phẩm từ form
            $maHang = $_POST["MaHang"];

            // Kết nối đến cơ sở dữ liệu và cập nhật đường dẫn hình ảnh
            include '../php/config.php';
            // Kiểm tra kết nối
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            // Cập nhật đường dẫn hình ảnh vào bảng sanpham
            $sql = "UPDATE products SET HinhAnh='$target_file' WHERE MaHang='$maHang'";

            if ($con->query($sql) === TRUE) {
                echo "Cập nhật hình ảnh thành công.";
            } else {
                echo "Lỗi: " . $sql . "<br>" . $con->error;
            }

            $con->close();

        } else {
            echo "Có lỗi xảy ra khi upload file.";
        }
    }
} else {
    echo "Vui lòng chọn một file để upload.";
}
?>
