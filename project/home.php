<?php
// Bắt đầu phiên làm việc
session_start();


if (!isset($_SESSION['valid'])) {
    header("Location: dangnhap.php");
    exit();
}


$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';



// Rest of your HTML and PHP code
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 
    - primary meta tag
  -->
  <title>Woodex - Get Quality Furniture</title>
  <meta name="title" content="Woodex - Get Quality Furniture">
  <meta name="description" content="This is an eCommerce html template made by codewithsadee">

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="svg-2.svg" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="../assest/style/style2.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mr+De+Haviland&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet">

  <!-- 
    - preload images
  -->


</head>


<body id="top">
<div class="preloader">
    <div class="loading"></div>
  </div>

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
  
    <div class="container">

    <form action="search.php" method="GET">
    <div class="input-wrapper">
        <input type="search" id="search-input" name="search" placeholder="Search Anything..." class="input-field">
        <div id="suggestions" class="suggestions"></div>
        <button type="submit">Search</button>
    </div>
</form>


        <a href="home.php" class="logo">Ngoc Hung</a>
    

        <div class="header-action">

            <button class="header-action-btn" aria-label="user" id="userBtn">
                <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
            </button>

            <!-- Menu hiển thị thông tin người dùng (ẩn ban đầu) -->
            <div id="userMenu" class="user-menu" style="display: none;">
                <?php 
                    include("../php/config.php");
                    if(!isset($_SESSION['valid'])){
                        header("Location: dangnhap.php");
                    }

                    $id = $_SESSION['id'];
                    $query = mysqli_query($con,"SELECT * FROM users WHERE Id=$id");

                    while($result = mysqli_fetch_assoc($query)){
                        $res_Uname = $result['Username'];
                        $res_Email = $result['Email'];
                        $res_Age = $result['Age'];
                        $res_id = $result['Id'];
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

            <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>

        </div>

    </div>
</header>







  <!-- 
    - #SIDEBAR
  -->

  <div class="sidebar" data-navbar>

    <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
      <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
    </button>

    <div class="wrapper">

      <ul class="sidebar-list">

        <li>
          <p class="sidebar-list-title">Ngôn Ngữ</p>
        </li>

        <li>
          <a href="#" class="sidebar-link">Tiếng Việt</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">Tiếng Anh</a>
        </li>

       
      </ul>

      <ul class="sidebar-list">

        <li>
          <p class="sidebar-list-title">Tiền tệ</p>
        </li>

        <li>
          <a href="#" class="sidebar-link">USD - US Dollar</a>
        </li>

        <li>
          <a href="#" class="sidebar-link">VND</a>
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
        <p class="contact-list-title">Liên hệ</p>
      </li>

      <li class="contact-item">
        <address class="address">
        Số 84C, Đường Nguyễn Thanh Bình, Vạn Phúc, Hà Đông, Hà Nội
        </address>
      </li>

      <li class="contact-item">
        <a href="mailto:support.center@woodex.co" class="contact-link">ngochung@gmail.com</a>
      </li>

      <li class="contact-item">
        <a href="098999999" class="contact-link">098999999</a>
      </li>

    </ul>

    <div class="social-wrapper">

      <p class="social-list-title">Mạng Xã Hội</p>

      <ul class="social-list">

        <li>
          <a href="https://www.facebook.com/hungsobad/" class="social-link">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
        </li>

        <li>
          <a href="https://x.com/alexnammk" class="social-link">
            <ion-icon name="logo-twitter"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-github"></ion-icon>
          </a>
        </li>

      </ul>

    </div>

  </div>

  <div class="overlay" data-overlay data-nav-toggler></div>





  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <?php
// Include file config.php which contains database connection
include '../php/config.php';

// SQL query to fetch products from the database based on category
$sql = "SELECT MaHang, TenSanPham, GiaNhap, GiamGia, SoLuongNhap, HinhAnh, tendanhmuc FROM products ORDER BY RAND() LIMIT 14";
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
    <h2 class="section-title1">SẢN PHẨM NỔI BẬT</h2>
    <section class="section hero" id="home" aria-label="home">
      
        <div class="slider">
        <h2 class="section-title1">SẢN PHẨM NỔI BẬT</h2>
            <div class="slider-track">
                <?php foreach ($categories as $categoryId => $categoryProducts): ?>
                    <?php foreach ($categoryProducts as $product): ?>
                        <div class="slider-item">
                            <a href="products_details.php?MaHang=<?php echo htmlspecialchars($product['MaHang']); ?>" class="slider-content">
                                <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>" class="product-image1">
                                <h3 class="product-name1"><?php echo htmlspecialchars($product['TenSanPham']); ?></h3>
                                <div class="product-price1">
                                    <?php if ($product['SoLuongNhap'] <= 0): ?>
                                        <span class="sold-out1">Hết hàng</span>
                                    <?php else: ?>
                                        <?php if (isset($product['GiamGia']) && $product['GiamGia'] > 0): ?>
                                            <del class="original-price1"><?php echo number_format($product['GiaNhap'], 0, ',', '.'); ?> đ</del>
                                            <strong><?php echo number_format($product['GiamGia'], 0, ',', '.'); ?> đ</strong>
                                        <?php else: ?>
                                            <strong><?php echo number_format($product['GiaNhap'], 0, ',', '.'); ?> đ</strong>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control prev">&#8249;</button>
            <button class="carousel-control next">&#8250;</button>
        </div>
    </section>

  








      <!-- 
        - #ABOUT
      -->

      <section class="section about" id="about" aria-label="about">
        <div class="container">

          <h2 class="section-title">23IT2 Computer</h2>

          <p class="section-text">
            Chào mừng các bạn đến với 23IT2 Computer ! 
            Chuyên cung cấp các loại laptop, phụ kiện máy tính chính hãng.
          </p>

          <div class="about-card">
            <figure class="card-banner img-holder" style="--width: 1100; --height: 350;">
              <img src="../uploads/hi1.jpg" width="1170" height="450" loading="lazy" alt="Woodex promo"
                class="img-cover">
            </figure>

          </div>

        </div>
      </section>





      <!-- 
        - #PRODUCTS
      -->
        <?php
  // Include file config.php which contains database connection
  include '../php/config.php';

  // SQL query to fetch products from database based on category
  $sql = "SELECT MaHang, TenSanPham, GiaNhap, GiamGia, SoLuongNhap, HinhAnh, tendanhmuc FROM products";
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
              <h2 class="h2 section-title">Danh sách sản phẩm</h2>

              <ul class="filter-btn-list">
                  <li class="filter-btn-item">
                      <button class="filter-btn active" data-filter-btn="all">Tất cả</button>
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
              echo '<span class="available">&#10004; Sẵn hàng</span>';

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







      <!-- 
        - #BLOG
      -->

      <section class="section blog" id="blog" aria-label="blog">
        <div class="container">

          <div class="title-wrapper">
            <h2 class="h2 section-title">Explore our blog</h2>

            <a href="#" class="btn-link">
              <span class="span">View All</span>

              <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
          </div>

          <ul class="grid-list">

            <li>
              <div class="blog-card">

                <div class="card-banner img-holder" style="--width: 374; --height: 243;">
                  <img src="../uploads/cmcu.jpg" width="374" height="243" loading="lazy"
                    alt="Unique products that will impress your home in 2022." class="img-cover">

                  <a href="#" class="card-btn">
                  <span class="span">Đọc Thêm</span>

                    <ion-icon name="add-outline" aria-hidden="true"></ion-icon>
                  </a>
                </div>

                <div class="card-content">

                  <h3 class="h3">
                    <a href="#" class="card-title">Trường Đại Học CMC.</a>
                  </h3>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                    <time class="card-meta-text" datetime="2024-08-28">Tháng Tám 28, 2024</time>
                    </li>

                    <li class="card-meta-item">
                      <a href="#" class="card-meta-text">CMC</a>
                    </li>

                   

                  </ul>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <div class="card-banner img-holder" style="--width: 374; --height: 243;">
                <img src="../uploads/CMC1.jpg" width="374" height="243" loading="lazy"
                    alt="Navy Blue & White Striped Area Rugs" class="img-cover">

                  <a href="#" class="card-btn">
                    <span class="span">Đọc Thêm</span>

                    <ion-icon name="add-outline" aria-hidden="true"></ion-icon>
                  </a>
                </div>

                <div class="card-content">

                  <h3 class="h3">
                    <a href="#" class="card-title">Trường Đại Học CMC.</a>
                  </h3>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <time class="card-meta-text" datetime="2024-08-28">Tháng Tám 28, 2024</time>
                    </li>

                    <li class="card-meta-item">
                      <a href="#" class="card-meta-text">CMC</a>
                    </li>

                   

                  </ul>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <div class="card-banner img-holder" style="--width: 374; --height: 243;">
                  <img src="https://codewithsadee.github.io/woodex/assets/images/blog-3.jpg" width="374" height="243" loading="lazy"
                    alt="Woodex White Coated Staircase Floating" class="img-cover">

                  <a href="#" class="card-btn">
                    <span class="span">Read more</span>

                    <ion-icon name="add-outline" aria-hidden="true"></ion-icon>
                  </a>
                </div>

                <div class="card-content">

                  <h3 class="h3">
                    <a href="#" class="card-title">Trường Đại Học CMC.</a>
                  </h3>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                    <time class="card-meta-text" datetime="2024-08-28">Tháng Tám 28, 2024</time>
                    </li>

                    <li class="card-meta-item">
                      <a href="#" class="card-meta-text">CMC</a>
                    </li>

                   

                  </ul>

                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>









  <!-- 
    - #FOOTER
  -->

  <footer class="footer">
    <div class="container">

      <div class="footer-top section">

        <div class="footer-brand">

          <a href="#" class="logo">Woodex</a>

          <ul>

            <li class="footer-list-item">
              <ion-icon name="location-sharp" aria-hidden="true"></ion-icon>

              <address class="address">
              Số 84C, Đường Nguyễn Thanh Bình, Vạn Phúc, Hà Đông, Hà Nội
              </address>
            </li>

            <li class="footer-list-item">
              <ion-icon name="call-sharp" aria-hidden="true"></ion-icon>

              <a href="tel:+1234567890" class="footer-link">098999999</a>
            </li>

            <li>
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
            </li>

          </ul>

        </div>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">Trợ Giúp & Thông Tin</p>
          </li>

          <li>
            <a href="#" class="footer-link">Trợ Giúp & Liên Hệ</a>
          </li>

          <li>
            <a href="#" class="footer-link">Hoàn Trả & Hoàn Tiền</a>
          </li>

          <li>
            <a href="#" class="footer-link">Cửa Hàng Trực Tuyến</a>
          </li>

          <li>
            <a href="#" class="footer-link"> Điều Khoản & Điều Kiện</a>
          </li>

        </ul>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">Về Chúng Tôi</p>
          </li>

          <li>
            <a href="#" class="footer-link">Chúng Tôi Làm Gì</a>
          </li>

          <li>
            <a href="#" class="footer-link">Câu Hỏi Thường Gặp</a>
          </li>

          <li>
            <a href="#" class="footer-link">Liên Hệ Chúng Tôi</a>
          </li>

          <li>
            <a href="#" class="footer-link">Liên Hệ Chúng Tôi</a>
          </li>

        </ul>

        
     

    </div>
  </footer>





  <!-- 
    - #BACK TO TOP
  -->

  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="../assest/js/script1.js" defer></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 


</body>

</html>