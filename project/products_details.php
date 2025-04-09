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

$MaHang = isset($_GET['MaHang']) ? $_GET['MaHang'] : null;
$product = null;

if ($MaHang) {
    $stmt = $con->prepare("SELECT * FROM products WHERE MaHang = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("s", $MaHang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "No product ID provided.";
    exit();
}

$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");

if ($query) {
    $result = mysqli_fetch_assoc($query);
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_Age = $result['Age'];
    $res_Phone = $result['PhoneNumber'];
} else {
    echo "User not found.";
    exit();
}

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
    <link rel="shortcut icon" href="svg-2.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assest/style/style3.css">
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
        <a href="#" class="contact-link">098999999</a>
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
  <article></article>
    <main>
        <div class="product-container">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" width="300" height="300" loading="lazy" alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>">
            </div>
            <div class="product-details">
                <h2>t</h2>
                <div class="flash-sale">
                <h2><?php echo htmlspecialchars($product['TenSanPham']); ?></h2>
                <?php
                    if ($product["SoLuongNhap"] <= 0) {
                        echo '<span class="sold-out">Sold out</span>';
                    } else {
                        if (isset($product["GiamGia"]) && $product["GiamGia"] > 0) {
                            $discountedPrice = $product["GiamGia"];
                            echo '<del class="original-price">' . number_format($product["GiaNhap"], 0, '.', '.') . ' đ</del>';
                            echo ' ' . number_format($discountedPrice, 0, '.', '.') . ' đ';
                        } else {
                            echo '<data class="price" value="' . number_format($product["GiaNhap"], 0, '.', '.') . '">' . number_format($product["GiaNhap"], 0, '.', '.') . ' đ</data>';
                        }
                    }
                    ?>


            </div>
            
            <div class="product-detail">
    <h2>Thông số sản phẩm</h2>
    <p><?php echo nl2br(htmlspecialchars($product["MoTa"])); ?></p>
</div>

            <div class="shop-coupons">
                <h3>Voucher</h3>
                <button>Mua để nhận Voucher giảm 10%</button>
            </div>
            <div class="shipping-policy">
                <h3>Shipping Policy</h3>
                <p>Free shipping toàn quốc</p>
            </div>
                <h3>Số lượng</h3>
                <div class="quantity">
                    <button id="minus-btn">-</button>
                    <input type="text" id="quantity" value="1">
                    <button id="plus-btn">+</button>
                </div>
            
            <div class="purchase-options">
    <!-- Thay đổi nút thêm vào giỏ hàng -->
    <button class="add-to-cart" 
        data-id="<?php echo htmlspecialchars($product['MaHang']); ?>" 
        data-name="<?php echo htmlspecialchars($product['TenSanPham']); ?>" 
        data-price="<?php
            if ($product['SoLuongNhap'] <= 0) {
                echo 0; 
            } else {
                if (isset($product['GiamGia']) && $product['GiamGia'] > 0) {
                    echo htmlspecialchars($product['GiamGia']); 
                } else {
                    echo htmlspecialchars($product['GiaNhap']); 
                }
            }
        ?>" 
        data-image="<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
        data-quantity="1">
        Thêm vào giỏ hàng
    </button>



    <button class="buy-now" data-id="<?php echo htmlspecialchars($product['MaHang']); ?>">Mua ngay</button>



            </div>
        </div>
    </div>
</main>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../vendor/autoload.php';

use MongoDB\Client as MongoDBClient;
use MongoDB\BSON\UTCDateTime;

$client = new MongoDBClient("mongodb://localhost:27017");
$commentsCollection = $client->quanlibanhang->comments;
$usersCollection = $client->quanlibanhang->users;

$commentAdded = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'], $_POST['MaHang'])) {
    $comment = $_POST['comment'];
    $productID = $_POST['MaHang'];
    $userID = $_SESSION['id']; // Lấy ID người dùng từ session

    if (!empty($comment) && !empty($productID) && !empty($userID)) {
        $commentsCollection->insertOne([
            'comment' => htmlspecialchars($comment),
            'MaHang' => htmlspecialchars($productID),
            'user_id' => htmlspecialchars($userID), // Lưu ID người dùng như là chuỗi
            'created_at' => new UTCDateTime()
        ]);

        $commentAdded = true;
    } else {
        $error = "ID người dùng, bình luận hoặc mã hàng không được để trống.";
    }
}
?>
<div class="comments-section">
    <h2>Bình luận</h2>
    <form method="post" action="">
        <textarea name="comment" rows="4" cols="50" required></textarea><br>
        <input type="hidden" name="MaHang" value="<?php echo htmlspecialchars($product['MaHang']); ?>">
        <input type="submit" value="Gửi bình luận">
    </form>
    <ul>
        <?php
        if (!empty($error)) {
            echo '<p style="color:red;">' . $error . '</p>';
        }

        $comments = $commentsCollection->find(
            ['MaHang' => htmlspecialchars($product['MaHang'])],
            ['sort' => ['created_at' => -1]]
        );

        foreach ($comments as $comment) {
            $user = $usersCollection->findOne(['Id' => (int)$comment['user_id']]);
            $username = $user ? $user['Username'] : 'Unknown User';

            echo '<li>' . htmlspecialchars($username) . ': ' . htmlspecialchars($comment['comment']) . ' (vào lúc ' . $comment['created_at']->toDateTime()->format('Y-m-d H:i:s') . ')</li>';
        }
        ?>
    </ul>
</div>

<?php if ($commentAdded): ?>
    <script>
        window.location.href = window.location.href;
    </script>
<?php endif; ?>

    </main>
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
                    echo '<button class="filter-btn" data-filter-btn="' . htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8') . '</button>';
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

        <script>

'use strict';

/**
 * add event on element
 */
const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else if (elem) {
    elem.addEventListener(type, callback);
  } else {
    console.error('Element not found for event:', type);
  }
}

/**
 * navbar toggle
 */
const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const overlay = document.querySelector("[data-overlay]");

const toggleNavbar = function () {
  navbar.classList.toggle("active");
  overlay.classList.toggle("active");
  document.body.classList.toggle("active");
}

addEventOnElem(navTogglers, "click", toggleNavbar);

const closeNavbar = function () {
  navbar.classList.remove("active");
  overlay.classList.remove("active");
  document.body.classList.remove("active");
}

addEventOnElem(navbarLinks, "click", closeNavbar);

/**
 * header & back top btn active when window scroll down to 100px
 */
const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const showElemOnScroll = function () {
  if (window.scrollY > 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
}

addEventOnElem(window, "scroll", showElemOnScroll);

/**
 * product filter
 */
const filterBtns = document.querySelectorAll("[data-filter-btn]");
const filterBox = document.querySelector("[data-filter]");

let lastClickedFilterBtn = filterBtns[0];

const filter = function () {
  lastClickedFilterBtn.classList.remove("active");
  this.classList.add("active");
  lastClickedFilterBtn = this;

  filterBox.setAttribute("data-filter", this.dataset.filterBtn);
}

addEventOnElem(filterBtns, "click", filter);
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const cartMenu = document.getElementById('cart-menu');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const addToCartBtns = document.querySelectorAll('.add-to-cart'); 
    const buyNowBtn = document.querySelector('.buy-now');
    const quantityInput = document.getElementById('quantity');
    const minusBtn = document.getElementById('minus-btn');
    const plusBtn = document.getElementById('plus-btn');
    const cartCount = document.getElementById('cart-count');
    const checkoutBtn = document.getElementById('checkout-button');

    
    if (minusBtn && plusBtn && quantityInput) {
        minusBtn.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value, 10);
            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;
            }
        });

        plusBtn.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value, 10);
            quantity++;
            quantityInput.value = quantity;
        });
    } else {
        console.error('One or more elements are not found.');
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const toggleCartMenu = () => {
        if (cartMenu) {
            cartMenu.style.display = cartMenu.style.display === 'none' || cartMenu.style.display === '' ? 'block' : 'none';
        }
    };

    const closeCartMenu = (event) => {
        if (cartMenu && !cartMenu.contains(event.target) && event.target !== cartIcon && !cartIcon.contains(event.target)) {
            cartMenu.style.display = 'none';
        }
    };

    if (cartIcon) {
        cartIcon.addEventListener('click', toggleCartMenu);
    }

    document.addEventListener('click', closeCartMenu);

    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    }

    const updateCartCount = () => {
        if (cartCount) {
            cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
        }
    };

    const formatter = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0, 
    });

    const renderCart = () => {
        if (cartItemsContainer && cartTotal) {
            cartItemsContainer.innerHTML = '';
            let total = 0;
            cart.forEach(item => {
                const listItem = document.createElement('li');
                listItem.className = 'cart-item';
                listItem.innerHTML = `
                    <div class="item-details">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                        <span class="cart-item-name">${item.name}</span>
                        <span class="cart-item-price">${formatter.format(item.price)}</span>
                        <span class="cart-item-quantity">x ${item.quantity}</span>
                    </div>
                    <div class="quantity-controls">
                        <button class="decrease-quantity" data-id="${item.id}">-</button>
                        <span class="quantity">${item.quantity}</span>
                        <button class="increase-quantity" data-id="${item.id}">+</button>
                    </div>
                    <button class="remove-from-cart" data-id="${item.id}">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                `;
                cartItemsContainer.appendChild(listItem);
                total += item.quantity * item.price;
            });
            cartTotal.innerHTML = `<strong>${formatter.format(total)}</strong>`;
            updateCartCount();
        }
    };

    const addToCart = (event) => {
        const button = event.target;
        const product = {
            id: button.dataset.id,
            name: button.dataset.name,
            price: parseFloat(button.dataset.price),
            quantity: parseInt(button.dataset.quantity, 10),
            image: button.dataset.image
        };

        if (product.price <= 0) {
            alert('Sản phẩm đã hết hàng hoặc không có sẵn để mua.');
            return;
        }

        const existingProduct = cart.find(item => item.id === product.id);
        if (existingProduct) {
            existingProduct.quantity += product.quantity;
        } else {
            cart.push(product);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                MaHang: product.id,
                GiamGia: product.price,
                GiaNhap: product.price,
                quantity: product.quantity,
                image: product.image
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    };

    addToCartBtns.forEach(button => button.addEventListener('click', addToCart));

    const changeQuantity = (productId, change) => {
        const product = cart.find(item => item.id === productId);
        if (product) {
            const newQuantity = product.quantity + change;
            if (newQuantity > 0) {
                fetch('update_cart_quantity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        MaHang: productId,
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        product.quantity = newQuantity;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        renderCart();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật số lượng sản phẩm.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật số lượng sản phẩm.');
                });
            } else {
                removeFromCart(productId);
            }
        }
    };

    const removeFromCart = (productId) => {
        cart = cart.filter(item => item.id !== productId);

        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                MaHang: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm.');
        });
    };

    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-from-cart')) {
                const id = event.target.dataset.id;
                removeFromCart(id);
            } else if (event.target.classList.contains('increase-quantity')) {
                const id = event.target.dataset.id;
                changeQuantity(id, 1);
            } else if (event.target.classList.contains('decrease-quantity')) {
                const id = event.target.dataset.id;
                changeQuantity(id, -1);
            }
        });
    }

    const checkout = () => {
        if (cart.length === 0) {
            alert('Giỏ hàng trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        } else {
            window.location.href = 'checkout.php';
        }
    };

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', checkout);
    }

    if (minusBtn) {
        minusBtn.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value, 10);
            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;
            }
        });
    }

    if (plusBtn) {
        plusBtn.addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value, 10);
            quantity++;
            quantityInput.value = quantity;
        });
    }

    renderCart();
});








document.addEventListener('DOMContentLoaded', (event) => {
            const userBtn = document.getElementById('userBtn');
            const userMenu = document.getElementById('userMenu');
            const logoutBtn = document.getElementById('logoutBtn'); 

            userBtn.addEventListener('click', () => {
                if (userMenu.style.display === 'none' || userMenu.style.display === '') {
                    userMenu.style.display = 'block';
                } else {
                    userMenu.style.display = 'none';
                }
            });

            // Close the menu if clicking outside of it
            document.addEventListener('click', (event) => {
                if (!userMenu.contains(event.target) && event.target !== userBtn) {
                    userMenu.style.display = 'none';
                }
            });

            // Ensure that logoutBtn is not null
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    document.getElementById('logoutForm').submit();
                });
            } else {
                console.error("Logout button not found in the DOM");
            }
        });

        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-filter-btn');
                document.querySelectorAll('.product-card').forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        function redirectAfterComment(productID) {
            window.location.href = "products_details.php?MaHang=" + encodeURIComponent(productID);
        }

        document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = 8;
    let currentPage = 1;
    const productCards = document.querySelectorAll('.product-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const paginationContainer = document.getElementById('pagination');
    const productList = document.querySelector('.product-list');
    const sectionProduct = document.getElementById('product');

    let currentCategory = 'all';
    let filteredProducts = Array.from(productCards);

    function scrollToTop() {
        sectionProduct.scrollIntoView({ behavior: 'smooth' });
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        currentPage = Math.min(currentPage, totalPages);
        
        // Clear previous pagination buttons
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('pagination-btn');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', function () {
                currentPage = i;
                updatePagination();
                scrollToTop();
            });
            paginationContainer.appendChild(pageButton);
        }

        filteredProducts.forEach((product, index) => {
            if (index >= (currentPage - 1) * itemsPerPage && index < currentPage * itemsPerPage) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            currentCategory = this.getAttribute('data-filter-btn');

            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Filter products based on category
            filteredProducts = Array.from(productCards).filter(product => {
                return currentCategory === 'all' || product.getAttribute('data-category') === currentCategory;
            });

            // Reset to first page
            currentPage = 1;
            updatePagination();
            scrollToTop();
        });
    });

    updatePagination();
});

document.querySelectorAll('.buy-now').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        window.location.href = `checkout.php?product_id=${productId}`;
    });
});

$(document).ready(function() {
    $('#search-input').on('input', function() {
        var query = $(this).val().trim(); // Loại bỏ khoảng trắng thừa
        if (query.length > 2) { // Chỉ gửi yêu cầu khi có ít nhất 3 ký tự
            $.ajax({
                url: 'search_suggestions.php',
                type: 'GET',
                data: { search: query },
                success: function(data) {
                    $('#suggestions').html(data).show(); // Hiển thị gợi ý
                },
                error: function() {
                    $('#suggestions').html('<p>Error retrieving suggestions.</p>').show(); // Thông báo lỗi
                }
            });
        } else {
            $('#suggestions').empty().hide(); // Xóa và ẩn gợi ý khi không có ký tự
        }
    });

    $(document).on('click', '.suggestion-item', function() {
        var productId = $(this).data('mahang'); // Lấy MaHang từ thuộc tính data
        if (productId) {
            window.location.href = 'products_details.php?MaHang=' + encodeURIComponent(productId); // Redirect đến trang chi tiết sản phẩm
        }
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#search-input, #suggestions').length) {
            $('#suggestions').empty().hide(); // Ẩn gợi ý khi nhấp ra ngoài
        }
    });
});





    </script>

<script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core@5.9.0/dist/ionic/ionic.esm.js"></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core@5.9.0/dist/ionic/ionic.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
