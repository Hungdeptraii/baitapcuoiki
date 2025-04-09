<?php
include '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $ma_hang = $_POST['ma_hang'];
    $ten_san_pham = $_POST['ten_san_pham'];
    $loai_hang = $_POST['loai_hang'];
    $ten_danh_muc = $_POST['ten_danh_muc'];
    $noi_san_xuat = $_POST['noi_san_xuat'];
    $so_luong_nhap = $_POST['so_luong_nhap'];
    $gia_nhap = $_POST['gia_nhap'];
    $ngay_nhap = $_POST['ngay_nhap'];
    $nha_cung_cap = $_POST['nha_cung_cap'];
    $mo_ta = $_POST['mo_ta']; // Thêm phần mô tả

    // Handle file upload
    $target_dir = "../uploads/"; // Make sure this directory exists and has the correct permissions
    $target_file = $target_dir . basename($_FILES["hinh_anh"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES["hinh_anh"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["hinh_anh"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
            // Insert data into database
            $sql = "INSERT INTO products (MaHang, TenSanPham, LoaiHang, TenDanhMuc, NoiSanXuat, SoLuongNhap, GiaNhap, NgayNhap, NhaCungCap, MoTa, HinhAnh) 
                    VALUES ('$ma_hang', '$ten_san_pham', '$loai_hang', '$ten_danh_muc', '$noi_san_xuat', '$so_luong_nhap', '$gia_nhap', '$ngay_nhap', '$nha_cung_cap', '$mo_ta', '$target_file')";

            if ($con->query($sql) === TRUE) {
                echo "<script>alert('New record created successfully'); window.location.href='admin.php?page=shop';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    $con->close();
}
?>
