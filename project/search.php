<?php
session_start();
include('../php/config.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['valid'])) {
    header("Location: dangnhap.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woodex - Get Quality Furniture</title>
    <meta name="title" content="Woodex - Get Quality Furniture">
    <meta name="description" content="This is an eCommerce html template made by codewithsadee">
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assest/style/style2.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mr+De+Haviland&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<body id="top">
<header class="header" data-header>
    <div class="container">

      <div class="input-wrapper">
        <input type="search" name="search" placeholder="Search Anything..." class="input-field">

        <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
      </div>

      <a href="home.php" class="logo">Woodex</a>

      <div class="header-action">

      <div class="header-action">

      <button class="header-action-btn" aria-label="user" id="userBtn">
        <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
    </button>

    <!-- Menu hiển thị thông tin người dùng (ẩn ban đầu) -->
    <div id="userMenu" class="user-menu">
        <?php 
            include("../php/config.php");
            
            if(!isset($_SESSION['valid'])){
                header("Location: dangnhap.php");
            }

            $id = $_SESSION['id'];
            $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_Phone = $result['PhoneNumber'];
            }
        ?>
        <p><strong>Tên người dùng:</strong> <?php echo $res_Uname; ?></p>
        <p><strong>Email:</strong> <?php echo $res_Email; ?></p>
        <p><strong>Tuổi:</strong> <?php echo $res_Age; ?></p>
        <p><strong>Số Điện Thoại:</strong> <?php echo $res_Phone; ?></p>
        <button id="logoutBtn" class="logout-button">Đăng Xuất</button>
    </div>
    <form id="logoutForm" action="logout.php" method="post" style="display:none;">
        <input type="hidden" name="logout" value="1">
    </form>   
   

        <button class="header-action-btn" aria-label="cart" id="cart-icon">
          <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
          <span class="btn-badge" id="cart-count">0</span>
        </button>

        <button class="header-action-btn" aria-label="order lookup" id="order-lookup-icon">
                <a href="check.php" style="text-decoration: none; color: inherit;">
                    <ion-icon name="car-outline" alt="Tra cứu đơn hàng"></ion-icon>
                    
                </a>
            </button>
        <button class="header-action-btn" aria-label="open menu" data-nav-toggler>
          <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
        </button>
        <?php


// Kiểm tra nếu giỏ hàng tồn tại

?>

<div id="cart-menu" class="cart-menu">
                <h2>Giỏ hàng</h2>
                <ul id="cart-items">
                    <!-- Sản phẩm sẽ được thêm vào đây -->
                </ul>
                <div class="cart-total">
                    Tổng tiền: <span id="cart-total">0.00</span>
                    <button id="checkout-button" class="checkout-button">Thanh toán</button>
                </div>
            </div>

    </header>
    <div class="sidebar" data-navbar>

    <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
      <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
    </button>

    <div class="wrapper">

      <ul class="sidebar-list">

        <li>
          <p class="sidebar-list-title">Language</p>
        </li>

        <li>
          <a href="#" class="sidebar-link">English</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">French</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">Arabric</a>
        </li>

      </ul>

      <ul class="sidebar-list">

        <li>
          <p class="sidebar-list-title">Currency</p>
        </li>

        <li>
          <a href="#" class="sidebar-link">USD - US Dollar</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">Euro</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">Pound</a>
        </li>

      </ul>

    </div>

    <nav class="navbar">
      <ul class="navbar-list">

        <li class="navbar-item">
          <a href="#home" class="navbar-link" data-nav-link>Home</a>
        </li>

        <li class="navbar-item">
          <a href="#about" class="navbar-link" data-nav-link>About</a>
        </li>


      </ul>
    </nav>

    <ul class="contact-list">

      <li>
        <p class="contact-list-title">Contact Us</p>
      </li>

      <li class="contact-item">
        <address class="address">
          69 Halsey St, Ny 10002, New York, United States
        </address>
      </li>

      <li class="contact-item">
        <a href="mailto:support.center@woodex.co" class="contact-link">support.center@woodex.co</a>
      </li>

      <li class="contact-item">
        <a href="tel:00001235567890" class="contact-link">(0000) 1235 567890</a>
      </li>

    </ul>

    <div class="social-wrapper">

      <p class="social-list-title">Follow US On Socials</p>

      <ul class="social-list">

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-twitter"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-tumblr"></ion-icon>
          </a>
        </li>

      </ul>

    </div>

  </div>

  <div class="overlay" data-overlay data-nav-toggler></div>





  <main>
  <section class="section about" id="about" aria-label="about">
        <div class="container">

       


          <div class="about-card">
            <figure class="card-banner img-holder" style="--width: 1170; --height: 450;">
              <img src="https://codewithsadee.github.io/woodex/assets/images/about-banner.jpg" width="1170" height="450" loading="lazy" alt="Woodex promo"
                class="img-cover">
            </figure>

            <button class="play-btn" aria-label="play video">
              <ion-icon name="play-circle-outline" aria-hidden="true"></ion-icon>
            </button>
          </div>

        </div>
      </section>


<?php
// Include file config.php which contains database connection
include '../php/config.php';

// Get search term if it exists
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query to fetch products from database based on category and search term
$sql = "SELECT MaHang, TenSanPham, GiaNhap, GiamGia, SoLuongNhap, HinhAnh, tendanhmuc FROM products";

// Modify SQL if search term is provided
if (!empty($searchTerm)) {
    $sql .= " WHERE TenSanPham LIKE '%" . $con->real_escape_string($searchTerm) . "%'";
}

$result = $con->query($sql);

if ($result === false) {
    die("Query error: " . $con->error);
}

// Array to store categories and their respective products
$categories = [];

// Fetch all products and categorize them
while ($row = $result->fetch_assoc()) {
    $category = $row['tendanhmuc'];

    // Initialize category array if not already exist
    if (!isset($categories[$category])) {
        $categories[$category] = [];
    }

    // Add product to its category
    $categories[$category][] = $row;
}

// Close connection
$con->close();

  ?>
  <section class="section product" id="product" aria-label="product">
      <div class="container">

          <div class="title-wrapper">
              <h2 class="h2 section-title">Popular Products</h2>
              
              

              <ul class="filter-btn-list">
                  <li class="filter-btn-item">
                      <button class="filter-btn active" data-filter-btn="all">All Products</button>
                  </li>
                  <?php
                  // Loop through categories and generate filter buttons
                  foreach ($categories as $categoryId => $categoryProducts) {
                      echo '<li class="filter-btn-item">';
                      echo '<button class="filter-btn" data-filter-btn="' . $categoryId . '">' . $categoryId . '</button>';
                      echo '</li>';
                  }
                  ?>
              </ul>
          </div>

          <ul class="grid-list product-list" data-filter="all">

          
    <?php
    
    // Loop through categories and products to generate product list
    foreach ($categories as $categoryId => $categoryProducts) {
        foreach ($categoryProducts as $product) {
            $isSoldOut = $product["SoLuongNhap"] <= 0;
            $hasDiscount = isset($product["GiamGia"]) && $product["GiamGia"] > 0;
            $finalPrice = $hasDiscount ? $product["GiamGia"] : $product["GiaNhap"];
            
            echo '<li class="product-card" data-category="' . $categoryId . '">';
            echo '<form action="add_to_cart.php" method="POST">';
            echo '<input type="hidden" name="MaHang" value="' . $product["MaHang"] . '">';
            echo '<input type="hidden" name="TenSanPham" value="' . $product["TenSanPham"] . '">';
            echo '<input type="hidden" name="GiaNhap" value="' . $product["GiaNhap"] . '">';
            echo '<input type="hidden" name="HinhAnh" value="' . $product["HinhAnh"] . '">';
            echo '<input type="hidden" name="GiamGia" value="' . $product["GiamGia"] . '">';
            
            echo '<a href="products_details.php?MaHang=' . $product["MaHang"] . '" class="card-banner img-holder has-before" style="--width: 400; --height: 400;">';
            echo '<img src="' . $product["HinhAnh"] . '" width="400" height="400" loading="lazy" alt="' . $product["TenSanPham"] . '" class="img-cover">';
            echo '<ul class="card-action-list">';
            
            // Moved the add-to-wishlist button inside card-action-list
            echo '<li><button type="button" class="card-action-btn add-to-wishlist" aria-label="Thêm vào danh sách yêu thích" title="Thêm vào danh sách yêu thích"><ion-icon name="heart-outline" aria-hidden="true"></ion-icon></button></li>';
            echo '</ul>';
            echo '</a>';
            
            echo '<div class="card-content">';
            echo '<h3 class="h3"><a href="products_details.php?MaHang=' . $product["MaHang"] . '" class="card-title">' . $product["TenSanPham"] . '</a></h3>';
            echo '<div class="card-price">';
            if ($isSoldOut) {
                echo '<span class="sold-out">Hết hàng</span>';
            } else {
                if ($hasDiscount) {
                    $discountedPrice = $product["GiamGia"];
                    echo '<del class="original-price">' . number_format($product["GiaNhap"], 0, ',', '.') . ' đ</del>';
                    echo ' <strong>' . number_format($discountedPrice, 0, ',', '.') . ' đ</strong>';
                } else {
                    $discountedPrice = $product["GiaNhap"]; // Use original price if no discount
                    echo '<data class="price" value="' . $discountedPrice . '"><strong>' . number_format($product["GiaNhap"], 0, ',', '.') . ' đ</strong></data>';
                }
            }
            echo '</div>'; // Close .card-price
            echo '</div>'; // Close .card-content
            
            // Add buttons for "Thêm vào giỏ hàng" and "Mua ngay"
            if (!$isSoldOut) {
                echo '<div class="product-buttons">';
                echo '<span class="available">✔️ Sẵn hàng</span>';
                echo '<button type="submit" class="card-action-btn add-to-cart" aria-label="Thêm vào giỏ hàng" title="Thêm vào giỏ hàng"><ion-icon name="cart-outline" aria-hidden="true"></ion-icon></button>';
                echo '</div>';
            }
            
            echo '</form>';
            echo '</li>';
        }
    }
    ?>
  </ul>
  
  
          <div class="pagination" id="pagination">
              <!-- Pagination buttons will be inserted here dynamically -->
          </div>
      </div>
  </section>
<script src="../assest/js/script1.js" defer></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
