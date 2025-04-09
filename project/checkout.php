<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: dangnhap.php");
    exit();
}

// Lấy mã sản phẩm từ URL (nếu có)
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$userId = $_SESSION['id'];

// Khởi tạo danh sách giỏ hàng
$cartItems = array();
$totalAmount = 0;

// Truy vấn giỏ hàng của người dùng
$cartQuery = mysqli_query($con, "SELECT c.MaHang, c.SoLuong, c.Gia, p.TenSanPham, p.HinhAnh 
                                 FROM cartshop c 
                                 INNER JOIN products p ON c.MaHang = p.MaHang 
                                 WHERE c.user_id='$userId'");

if (mysqli_num_rows($cartQuery) > 0) {
    while ($item = mysqli_fetch_assoc($cartQuery)) {
        $cartItems[] = $item;
        $totalAmount += $item['Gia'] * $item['SoLuong'];
    }
}

// Nếu người dùng bấm "Mua ngay"
if ($product_id) {
    // Truy vấn thông tin sản phẩm từ bảng products
    $productQuery = mysqli_query($con, "SELECT MaHang, TenSanPham, GiaNhap AS Gia, HinhAnh 
                                        FROM products 
                                        WHERE MaHang='$product_id'");

    if (mysqli_num_rows($productQuery) > 0) {
        $product = mysqli_fetch_assoc($productQuery);
        $product['SoLuong'] = 1; // Đặt số lượng mặc định là 1 cho nút "Mua ngay"
        $cartItems[] = $product; // Thêm sản phẩm vào danh sách giỏ hàng tạm thời
        $totalAmount += $product['Gia'] * $product['SoLuong'];
    } else {
        echo 'Sản phẩm không tồn tại.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
  <link rel="stylesheet" href="../assest/style/style4.css">

<!-- Kết nối file CSS thứ hai -->

  <!-- 
    - custom css link
  -->
 

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mr+De+Haviland&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet">

    <link rel="shortcut icon" href="svg-2.svg" type="image/svg+xml">  

</head>

<body id="top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

        <div class="input-wrapper">
            <input type="search" name="search" placeholder="Search Anything..." class="input-field">
            <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
        </div>

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

<body>
  
    <div class="container">
    <h2>2. Địa chỉ giao hàng</h2>
  <h2>2. Địa chỉ giao hàng</h2>
  <form id="orderForm" action="process_order.php" method="POST">
    <label for="name">Họ và tên</label>
    <input type="text" id="name" name="name" required>
    
    <label for="phone">Số điện thoại</label>
    <input type="text" id="phone" name="phone" required>
    
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    
    <label for="city">Tỉnh/Thành phố</label>
    <select id="city" name="city" required>
    <option value="">Chọn Tỉnh/Thành phố</option>
    <option value="Hà Nội">Hà Nội</option>
    <option value="Hồ Chí Minh">Hồ Chí Minh</option>
    <option value="Đà Nẵng">Đà Nẵng</option>
    <option value="Hải Phòng">Hải Phòng</option>
    <option value="Cần Thơ">Cần Thơ</option>
    <option value="An Giang">An Giang</option>
    <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
    <option value="Bắc Giang">Bắc Giang</option>
    <option value="Bắc Kạn">Bắc Kạn</option>
    <option value="Bạc Liêu">Bạc Liêu</option>
    <option value="Bắc Ninh">Bắc Ninh</option>
    <option value="Bến Tre">Bến Tre</option>
    <option value="Bình Định">Bình Định</option>
    <option value="Bình Dương">Bình Dương</option>
    <option value="Bình Phước">Bình Phước</option>
    <option value="Bình Thuận">Bình Thuận</option>
    <option value="Cà Mau">Cà Mau</option>
    <option value="Cao Bằng">Cao Bằng</option>
    <option value="Đắk Lắk">Đắk Lắk</option>
    <option value="Đắk Nông">Đắk Nông</option>
    <option value="Điện Biên">Điện Biên</option>
    <option value="Đồng Nai">Đồng Nai</option>
    <option value="Đồng Tháp">Đồng Tháp</option>
    <option value="Gia Lai">Gia Lai</option>
    <option value="Hà Giang">Hà Giang</option>
    <option value="Hà Nam">Hà Nam</option>
    <option value="Hà Tĩnh">Hà Tĩnh</option>
    <option value="Hải Dương">Hải Dương</option>
    <option value="Hậu Giang">Hậu Giang</option>
    <option value="Hòa Bình">Hòa Bình</option>
    <option value="Hưng Yên">Hưng Yên</option>
    <option value="Khánh Hòa">Khánh Hòa</option>
    <option value="Kiên Giang">Kiên Giang</option>
    <option value="Kon Tum">Kon Tum</option>
    <option value="Lai Châu">Lai Châu</option>
    <option value="Lâm Đồng">Lâm Đồng</option>
    <option value="Lạng Sơn">Lạng Sơn</option>
    <option value="Lào Cai">Lào Cai</option>
    <option value="Long An">Long An</option>
    <option value="Nam Định">Nam Định</option>
    <option value="Nghệ An">Nghệ An</option>
    <option value="Ninh Bình">Ninh Bình</option>
    <option value="Ninh Thuận">Ninh Thuận</option>
    <option value="Phú Thọ">Phú Thọ</option>
    <option value="Phú Yên">Phú Yên</option>
    <option value="Quảng Bình">Quảng Bình</option>
    <option value="Quảng Nam">Quảng Nam</option>
    <option value="Quảng Ngãi">Quảng Ngãi</option>
    <option value="Quảng Ninh">Quảng Ninh</option>
    <option value="Quảng Trị">Quảng Trị</option>
    <option value="Sóc Trăng">Sóc Trăng</option>
    <option value="Sơn La">Sơn La</option>
    <option value="Tây Ninh">Tây Ninh</option>
    <option value="Thái Bình">Thái Bình</option>
    <option value="Thái Nguyên">Thái Nguyên</option>
    <option value="Thanh Hóa">Thanh Hóa</option>
    <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
    <option value="Tiền Giang">Tiền Giang</option>
    <option value="Trà Vinh">Trà Vinh</option>
    <option value="Tuyên Quang">Tuyên Quang</option>
    <option value="Vĩnh Long">Vĩnh Long</option>
    <option value="Vĩnh Phúc">Vĩnh Phúc</option>
    <option value="Yên Bái">Yên Bái</option>
</select>

    
    <label for="district">Quận/Huyện</label>
    <select id="district" name="district" required>
      <option value="">Chọn Quận/Huyện</option>
      <!-- Districts will be populated based on selected city -->
    </select>
    
    <label for="ward">Phường/Xã</label>
    <input type="text" id="ward" name="ward" required>
    
    <label for="address">Địa chỉ</label>
    <input type="text" id="address" name="address" required>
    
    <h2>Order Summary</h2>
<ul style="list-style-type: none; padding: 0;">
    <?php foreach ($cartItems as $item): ?>
        <li style="display: flex; align-items: center; margin-bottom: 10px;">
            <img src="<?php echo $item['HinhAnh']; ?>" alt="<?php echo $item['TenSanPham']; ?>" style="max-width: 100px; max-height: 100px; width: auto; height: auto; margin-right: 10px;">
            <span><?php echo $item['TenSanPham'] . ' - ' . $item['SoLuong'] . ' x ₫' . number_format($item['Gia'], 0, ',', '.'); ?></span>
        </li>
    <?php endforeach; ?>
</ul>
<p>Total: ₫<?php echo number_format($totalAmount, 0, ',', '.'); ?></p>



    
        <div class="payment-methods">
  <input type="radio" id="Thanh toán khi nhận hàng" name="payment_method" value="Thanh toán khi nhận hàng" checked>
  <label for="Thanh toán khi nhận hàng">Thanh toán khi nhận hàng</label><br>
  <input type="radio" id="bank" name="payment_method" value="bank">
  <label for="Thanh toán bằng chuyển khoản">Thanh toán bằng chuyển khoản</label><br>
  <input type="radio" id="credit" name="payment_method" value="credit">
</div>

    
    <h3>3. Chọn hình thức giao hàng</h3>
    <label>
      <input type="radio" name="shipping" checked> Giao hàng tiêu chuẩn
    </label>
    
    <input type="text" placeholder="Ghi chú" name="note">
    
    <input type="hidden" name="cartItems" value='<?php echo json_encode($cartItems); ?>'>
  <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
  <button type="submit" class="btn submit-btn">ĐẶT HÀNG</button>
<a href="home.php" class="btn yellow-btn">CHỌN THÊM SẢN PHẨM</a>

    </div>
  </form>
</div>



       <script>

'use strict'

/**
 * add event on element
 */
const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else {
    elem.addEventListener(type, callback);
  }
};

/**
 * navbar toggle
 */
const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const overlay = document.querySelector("[data-overlay]");

const toggleNavbar = function () {
  if (navbar && overlay) {
    navbar.classList.toggle("active");
    overlay.classList.toggle("active");
    document.body.classList.toggle("active");
  }
};

addEventOnElem(navTogglers, "click", toggleNavbar);

const closeNavbar = function () {
  if (navbar && overlay) {
    navbar.classList.remove("active");
    overlay.classList.remove("active");
    document.body.classList.remove("active");
  }
};

addEventOnElem(navbarLinks, "click", closeNavbar);

/**
 * header & back top btn active when window scroll down to 100px
 */
const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const showElemOnScroll = function () {
  if (header && backTopBtn) {
    if (window.scrollY > 100) {
      header.classList.add("active");
      backTopBtn.classList.add("active");
    } else {
      header.classList.remove("active");
      backTopBtn.classList.remove("active");
    }
  }
};


addEventOnElem(window, "scroll", showElemOnScroll);

        
        document.addEventListener('DOMContentLoaded', () => {
            
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartCountElement = document.getElementById('cart-count');
    const cartMenu = document.getElementById('cart-menu');
    const cartItemsElement = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const cartIcon = document.getElementById('cart-icon');
    const checkoutButton = document.getElementById('checkout-button');
    const orderForm = document.getElementById('orderForm');
   

    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');
    let logoutBtn = document.getElementById('logoutBtn');

    // Khởi tạo giỏ hàng từ localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartTotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    

    // Thêm sản phẩm vào giỏ hàng khi nhấn nút
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const productForm = event.target.closest('form');
            const formData = new FormData(productForm);

            const product = {
                id: formData.get('MaHang'),
                name: formData.get('TenSanPham'),
                price: parseFloat(formData.get('GiamGia')) > 0 ? parseFloat(formData.get('GiamGia')) : parseFloat(formData.get('GiaNhap')),
                quantity: 1,
                image: formData.get('HinhAnh')
            };

            if (product.price <= 0) {
                alert('Sản phẩm đã hết hàng hoặc không có sẵn để mua.');
                return;
            }

            // Gửi yêu cầu AJAX đến add_to_cart.php
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addToCart(product);
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
            });
        });
    });

    // Hiển thị hoặc ẩn giỏ hàng khi nhấn biểu tượng giỏ hàng
    cartIcon.addEventListener('click', (event) => {
        event.stopPropagation(); // Ngăn chặn sự kiện nhấp chuột nổi lên
        cartMenu.classList.toggle('show');
    });

    // Thanh toán khi nhấn nút thanh toán
    checkoutButton.addEventListener('click', () => {
        if (cartCount === 0) {
            alert('Giỏ hàng của bạn đang trống. Không thể thanh toán.');
            return;
        }
        window.location.href = 'checkout.php';
    });

    // Thêm sản phẩm vào giỏ hàng và cập nhật hiển thị
    function addToCart(product) {
        const existingProduct = cart.find(item => item.id === product.id);
        if (existingProduct) {
            existingProduct.quantity++;
        } else {
            cart.push(product);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        cartCount++;
        cartTotal += product.price;
        updateCartDisplay();
    }

    // Cập nhật hiển thị giỏ hàng
    function updateCartDisplay() {
        cartCountElement.textContent = cartCount;
        cartItemsElement.innerHTML = '';
        cart.forEach(item => {
            const cartItem = document.createElement('li');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="item-details">
                    ${item.name} - ${item.price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} x ${item.quantity}
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
            cartItemsElement.appendChild(cartItem);
            cartItem.querySelector('.increase-quantity').addEventListener('click', () => changeQuantity(item.id, 1));
            cartItem.querySelector('.decrease-quantity').addEventListener('click', () => changeQuantity(item.id, -1));
            cartItem.querySelector('.remove-from-cart').addEventListener('click', () => removeFromCart(item.id));
        });
        cartTotalElement.textContent = `${cartTotal.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}`;
    }

    // Thay đổi số lượng sản phẩm trong giỏ hàng
    function changeQuantity(productId, change) {
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
                        cartTotal += product.price * change;
                        cartCount += change;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartDisplay();
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
    }

    // Xóa sản phẩm khỏi giỏ hàng
    function removeFromCart(productId) {
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
                const productIndex = cart.findIndex(item => item.id === productId);
                if (productIndex !== -1) {
                    const product = cart[productIndex];
                    cartTotal -= product.price * product.quantity;
                    cartCount -= product.quantity;
                    cart.splice(productIndex, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartDisplay();
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
        });
    }

    // Hiển thị menu người dùng
    userBtn.addEventListener('click', () => {
        userMenu.style.display = (userMenu.style.display === 'none' || userMenu.style.display === '') ? 'block' : 'none';
    });

    // Đăng xuất
    logoutBtn.addEventListener('click', () => {
        document.getElementById('logoutForm').submit();
    });

    // Cập nhật hiển thị giỏ hàng khi tải trang
    updateCartDisplay();
});


document.addEventListener('DOMContentLoaded', function() {
            const districtsByCity = {
                "Hà Nội": ["Ba Đình", "Hoàn Kiếm", "Tây Hồ", "Long Biên", "Cầu Giấy", "Đống Đa", "Hai Bà Trưng", "Hoàng Mai", "Thanh Xuân", "Sóc Sơn", "Đông Anh", "Gia Lâm", "Nam Từ Liêm", "Thanh Trì", "Bắc Từ Liêm"],
                "Hồ Chí Minh": ["Quận 1", "Quận 2", "Quận 3", "Quận 4", "Quận 5", "Quận 6", "Quận 7", "Quận 8", "Quận 9", "Quận 10", "Quận 11", "Quận 12", "Bình Tân", "Tân Bình", "Tân Phú", "Gò Vấp", "Phú Nhuận", "Thủ Đức", "Bình Chánh", "Hóc Môn", "Củ Chi", "Nhà Bè", "Cần Giờ"],
                "Đà Nẵng": ["Hải Châu", "Thanh Khê", "Sơn Trà", "Ngũ Hành Sơn", "Liên Chiểu", "Cẩm Lệ", "Hòa Vang"],
                "An Giang": ["Thành phố Long Xuyên", "Thành phố Châu Đốc", "Thị xã Tân Châu", "Huyện An Phú", "Huyện Châu Phú", "Huyện Châu Thành", "Huyện Chợ Mới", "Huyện Phú Tân", "Huyện Thoại Sơn", "Huyện Tịnh Biên", "Huyện Tri Tôn"],
                "Bà Rịa - Vũng Tàu": ["Thành phố Vũng Tàu", "Thành phố Bà Rịa", "Huyện Châu Đức", "Huyện Côn Đảo", "Huyện Đất Đỏ", "Huyện Long Điền", "Huyện Tân Thành", "Huyện Xuyên Mộc"],
                "Bắc Giang": ["Thành phố Bắc Giang", "Huyện Hiệp Hòa", "Huyện Lạng Giang", "Huyện Lục Nam", "Huyện Lục Ngạn", "Huyện Sơn Động", "Huyện Tân Yên", "Huyện Việt Yên", "Huyện Yên Dũng", "Huyện Yên Thế"],
                "Bắc Kạn": ["Thành phố Bắc Kạn", "Huyện Ba Bể", "Huyện Bạch Thông", "Huyện Chợ Đồn", "Huyện Chợ Mới", "Huyện Na Rì", "Huyện Ngân Sơn", "Huyện Pác Nặm"],
                "Bạc Liêu": ["Thành phố Bạc Liêu", "Huyện Hòa Bình", "Huyện Đông Hải", "Huyện Giá Rai", "Huyện Phước Long", "Huyện Vĩnh Lợi", "Huyện Hồng Dân", "Huyện Đầm Dơi"],
                "Bắc Ninh": ["Thành phố Bắc Ninh", "Thành Phố Từ Sơn", "Huyện Gia Bình", "Huyện Lương Tài", "Huyện Quế Võ", "Huyện Thuận Thành", "Huyện Tiên Du", "Huyện Yên Phong"],
                "Bến Tre": ["Thành phố Bến Tre", "Huyện Ba Tri", "Huyện Bình Đại", "Huyện Châu Thành", "Huyện Chợ Lách", "Huyện Mỏ Cày Bắc", "Huyện Mỏ Cày Nam", "Huyện Thạnh Phú", "Huyện Giồng Trôm", "Huyện Bình Đại"],
                "Bình Định": ["Thành phố Quy Nhơn", "Huyện An Lão", "Huyện An Nhơn", "Huyện Hoài Ân", "Huyện Hoài Nhơn", "Huyện Phù Cát", "Huyện Phù Mỹ", "Huyện Tây Sơn", "Huyện Vân Canh", "Huyện Vĩnh Thạnh"],
                "Bình Dương": ["Thành phố Thủ Dầu Một", "Thành phố Dĩ An", "Thành phố Thuận An", "Huyện Bến Cát", "Huyện Dầu Tiếng", "Huyện Phú Giáo", "Huyện Tân Uyên", "Huyện Bắc Tân Uyên"],
                "Bình Phước": ["Thành phố Đồng Xoài", "Huyện Bù Đăng", "Huyện Bù Gia Mập", "Huyện Bù Đốp", "Huyện Chơn Thành", "Huyện Hớn Quản", "Huyện Lộc Ninh", "Huyện Phú Riềng", "Huyện Đồng Phú"],
                "Cà Mau": ["Thành phố Cà Mau", "Huyện Cái Nước", "Huyện Cái Răng", "Huyện Đầm Dơi", "Huyện Năm Căn", "Huyện Ngọc Hiển", "Huyện Phú Tân", "Huyện Thới Bình", "Huyện Trần Văn Thời", "Huyện U Minh"],
                "Cần Thơ": ["Thành phố Cần Thơ", "Huyện Cờ Đỏ", "Huyện Phong Điền", "Huyện Thới Lai", "Huyện Vĩnh Thạnh", "Huyện Ninh Kiều", "Huyện Bình Thủy", "Huyện Ô Môn"],
                "Cao Bằng": ["Thành phố Cao Bằng", "Huyện Bảo Lạc", "Huyện Bảo Lâm", "Huyện Hạ Lang", "Huyện Nguyên Bình", "Huyện Phục Hòa", "Huyện Quảng Uyên", "Huyện Thạch An", "Huyện Trà Lĩnh", "Huyện Trùng Khánh"],
                "Đà Nẵng": ["Hải Châu", "Thanh Khê", "Sơn Trà", "Ngũ Hành Sơn", "Liên Chiểu", "Cẩm Lệ", "Hòa Vang"],
                "Đắk Lắk": ["Thành phố Buôn Ma Thuột", "Huyện Buôn Đôn", "Huyện Cư Kuin", "Huyện Cư M'gar", "Huyện Ea H'leo", "Huyện Ea Súp", "Huyện Krông Ana", "Huyện Krông Búk", "Huyện Krông Bông", "Huyện Krông Năng", "Huyện Krông Pắk", "Huyện Lắk", "Huyện M'Drắk", "Huyện Tây Sơn", "Huyện Đắk Glong"],
                "Đắk Nông": ["Thành phố Gia Nghĩa", "Huyện Cư Jút", "Huyện Cư M'gar", "Huyện Đắk Glong", "Huyện Đắk Mil", "Huyện Đắk R'lấp", "Huyện Đắk Song", "Huyện Krông Nô", "Huyện Tuy Đức"],
                "Điện Biên": ["Thành phố Điện Biên Phủ", "Huyện Điện Biên", "Huyện Điện Biên Đông", "Huyện Mường Ảng", "Huyện Mường Chà", "Huyện Mường Nhé", "Huyện Tủa Chùa", "Huyện Tuần Giáo", "Huyện Nậm Pồ"],
                "Đồng Nai": ["Thành phố Biên Hòa", "Huyện Cẩm Mỹ", "Huyện Định Quán", "Huyện Long Thành", "Huyện Nhơn Trạch", "Huyện Tân Phú", "Huyện Thống Nhất", "Huyện Trảng Bom", "Huyện Vĩnh Cửu"],
                "Đồng Tháp": ["Thành phố Cao Lãnh", "Thành phố Sa Đéc", "Huyện Cao Lãnh", "Huyện Châu Thành", "Huyện Hồng Ngự", "Huyện Hồng Dân", "Huyện Lai Vung", "Huyện Lấp Vò", "Huyện Tam Nông", "Huyện Tân Hồng", "Huyện Thanh Bình", "Huyện Tháp Mười"],
                "Gia Lai": ["Thành phố Pleiku", "Huyện Ayun Pa", "Huyện Chư Păh", "Huyện Chư Prông", "Huyện Chư Sê", "Huyện Đăk Đoa", "Huyện Đăk Pơ", "Huyện Đăk Tô", "Huyện Ia Grai", "Huyện Ia Pa", "Huyện Kbang", "Huyện Kông Chro", "Huyện Krông Pa", "Huyện Mang Yang", "Huyện Phú Thiện", "Huyện Phú Quốc"],
                "Hà Giang": ["Thành phố Hà Giang", "Huyện Bắc Mê", "Huyện Bắc Quang", "Huyện Đồng Văn", "Huyện Hoàng Su Phì", "Huyện Mèo Vạc", "Huyện Quản Bạ", "Huyện Vị Xuyên", "Huyện Xín Mần", "Huyện Yên Minh"],
                "Hà Nam": ["Thành phố Phủ Lý", "Huyện Bình Lục", "Huyện Duy Tiên", "Huyện Kim Bảng", "Huyện Lý Nhân", "Huyện Thanh Liêm"],
                "Hà Tĩnh": ["Thành phố Hà Tĩnh", "Huyện Cẩm Xuyên", "Huyện Đức Thọ", "Huyện Hương Khê", "Huyện Hương Sơn", "Huyện Kỳ Anh", "Huyện Lộc Hà", "Huyện Nghi Xuân", "Huyện Thạch Hà", "Huyện Vũ Quang"],
                "Hải Dương": ["Thành phố Hải Dương", "Huyện Bình Giang", "Huyện Cẩm Giàng", "Huyện Gia Lộc", "Huyện Kim Thành", "Huyện Kinh Môn", "Huyện Nam Sách", "Huyện Ninh Giang", "Huyện Thanh Hà", "Huyện Thanh Miện", "Huyện Tứ Kỳ"],
                "Hải Phòng": ["Quận Đồ Sơn", "Quận Dương Kinh", "Quận Hồng Bàng", "Quận Hải An", "Quận Kiến An", "Quận Lê Chân", "Quận Ngô Quyền", "Quận Tây Hồ", "Huyện An Dương", "Huyện An Lão", "Huyện Bạch Long Vĩ", "Huyện Cát Hải", "Huyện Cát Bà", "Huyện Kiến Thụy", "Huyện Tiên Lãng", "Huyện Vĩnh Bảo"],
                "Hậu Giang": ["Thành phố Vị Thanh", "Huyện Châu Thành", "Huyện Châu Thành A", "Huyện Long Mỹ", "Huyện Phụng Hiệp", "Huyện Vị Thuỷ"],
                "Hòa Bình": ["Thành phố Hòa Bình", "Huyện Đà Bắc", "Huyện Cao Phong", "Huyện Lạc Sơn", "Huyện Lạc Thủy", "Huyện Lương Sơn", "Huyện Kim Bôi", "Huyện Mai Châu", "Huyện Tân Lạc", "Huyện Yên Thủy"],
                "Hưng Yên": ["Thành phố Hưng Yên", "Huyện Ân Thi", "Huyện Khoái Châu", "Huyện Kim Động", "Huyện Mỹ Hào", "Huyện Phù Cừ", "Huyện Tiên Lữ", "Huyện Văn Giang", "Huyện Văn Lâm", "Huyện Văn Minh"],
                "Khánh Hòa": ["Thành phố Nha Trang", "Thành phố Cam Ranh", "Huyện Cam Lâm", "Huyện Diên Khánh", "Huyện Khánh Sơn", "Huyện Khánh Vĩnh", "Huyện Ninh Hòa", "Huyện Vạn Ninh"],
                "Kiên Giang": ["Thành phố Rạch Giá", "Thành phố Hà Tiên", "Huyện An Biên", "Huyện An Minh", "Huyện Châu Thành", "Huyện Giang Thành", "Huyện Hòn Đất", "Huyện Kiên Hải", "Huyện Kiên Lương", "Huyện Phú Quốc", "Huyện Tân Hiệp", "Huyện Tân Thạnh", "Huyện U Minh Thượng"],
                "Kon Tum": ["Thành phố Kon Tum", "Huyện Đắk Glei", "Huyện Đắk Hà", "Huyện Đắk Tô", "Huyện Ia H'Drai", "Huyện Ia Kha", "Huyện Ia Pa", "Huyện Ngọc Hồi", "Huyện Sa Thầy", "Huyện Tu Mơ Rông"],
                "Lai Châu": ["Thành phố Lai Châu", "Huyện Đăk Tô", "Huyện Mường Tè", "Huyện Nậm Nhùn", "Huyện Phong Thổ", "Huyện Sìn Hồ", "Huyện Tam Đường", "Huyện Tân Uyên", "Huyện Than Uyên"],
                "Lâm Đồng": ["Thành phố Đà Lạt", "Thành phố Bảo Lộc", "Huyện Đạ Huoai", "Huyện Đạ Tẻh", "Huyện Di Linh", "Huyện Đam Rông", "Huyện Lạc Dương", "Huyện Lạc Sơn", "Huyện Lâm Hà", "Huyện Đức Trọng"],
                "Lạng Sơn": ["Thành phố Lạng Sơn", "Huyện Bình Gia", "Huyện Cao Lộc", "Huyện Chi Lăng", "Huyện Đình Lập", "Huyện Hữu Lũng", "Huyện Lộc Bình", "Huyện Na Rì", "Huyện Văn Quan"],
                "Lào Cai": ["Thành phố Lào Cai", "Huyện Bát Xát", "Huyện Bắc Hà", "Huyện Bat Xat", "Huyện Mường Khương", "Huyện Sa Pa", "Huyện Simacai", "Huyện Văn Bàn"],
                "Long An": ["Thành phố Tân An", "Huyện Cần Giuộc", "Huyện Cần Đước", "Huyện Châu Thành", "Huyện Đức Hòa", "Huyện Đức Huệ", "Huyện Hậu Nghĩa", "Huyện Tân Trụ", "Huyện Tân Thạnh", "Huyện Tân Thạnh"],
                "Nam Định": ["Thành phố Nam Định", "Huyện Mỹ Lộc", "Huyện Nam Trực", "Huyện Nghĩa Hưng", "Huyện Trực Ninh", "Huyện Vụ Bản", "Huyện Xuân Trường", "Huyện Giao Thủy", "Huyện Hải Hậu"],
                "Nghệ An": ["Thành phố Vinh", "Huyện Anh Sơn", "Huyện Con Cuông", "Huyện Cửa Lò", "Huyện Diễn Châu", "Huyện Hưng Nguyên", "Huyện Kỳ Sơn", "Huyện Nam Đàn", "Huyện Nghi Lộc", "Huyện Quế Phong", "Huyện Quỳ Châu", "Huyện Quỳ Hợp", "Huyện Quỳnh Lưu", "Huyện Tân Kỳ", "Huyện Tương Dương"],
                "Ninh Bình": ["Thành phố Ninh Bình", "Huyện Hoa Lư", "Huyện Gia Viễn", "Huyện Kim Sơn", "Huyện Nho Quan", "Huyện Yên Khánh", "Huyện Yên Mô"],
                "Ninh Thuận": ["Thành phố Phan Rang - Tháp Chàm", "Huyện Bác Ái", "Huyện Ninh Hải", "Huyện Ninh Phước", "Huyện Ninh Sơn", "Huyện Thuận Bắc", "Huyện Thuận Nam"],
                "Phú Thọ": ["Thành phố Việt Trì", "Huyện Cẩm Khê", "Huyện Đoan Hùng", "Huyện Hạ Hòa", "Huyện Lâm Thao", "Huyện Phù Ninh", "Huyện Tam Nông", "Huyện Tân Sơn", "Huyện Thanh Ba", "Huyện Thanh Sơn", "Huyện Thanh Thủy"],
                "Phú Yên": ["Thành phố Tuy Hòa", "Huyện Đông Hòa", "Huyện Đồng Xuân", "Huyện Phú Hòa", "Huyện Phú Mỡ", "Huyện Sông Cầu", "Huyện Tây Hòa", "Huyện Tuy An"],
                "Quảng Bình": ["Thành phố Đồng Hới", "Huyện Bố Trạch", "Huyện Minh Hóa", "Huyện Quảng Ninh", "Huyện Quảng Trạch", "Huyện Lệ Thủy"],
                "Quảng Nam": ["Thành phố Tam Kỳ", "Thành phố Hội An", "Huyện Đại Lộc", "Huyện Đông Giang", "Huyện Duy Xuyên", "Huyện Hiệp Đức", "Huyện Nông Sơn", "Huyện Phú Ninh", "Huyện Quế Sơn", "Huyện Thăng Bình", "Huyện Tiên Phước", "Huyện Nam Giang", "Huyện Tây Giang"],
                "Quảng Ngãi": ["Thành phố Quảng Ngãi", "Huyện Ba Tơ", "Huyện Bình Sơn", "Huyện Đức Phổ", "Huyện Minh Long", "Huyện Mộ Đức", "Huyện Nghĩa Hành", "Huyện Sơn Hà", "Huyện Sơn Tây", "Huyện Tư Nghĩa"],
                "Quảng Ninh": ["Thành phố Hạ Long", "Thành phố Uông Bí", "Huyện Ba Chẽ", "Huyện Bình Liêu", "Huyện Cô Tô", "Huyện Đầm Hà", "Huyện Hải Hà", "Huyện Hoành Bồ", "Huyện Móng Cái", "Huyện Quảng Yên", "Huyện Tiên Yên", "Huyện Vân Đồn"],
                "Quảng Trị": ["Thành phố Đông Hà", "Huyện Cam Lộ", "Huyện Cồn Cỏ", "Huyện Đakrông", "Huyện Gio Linh", "Huyện Hướng Hóa", "Huyện Hải Lăng", "Huyện Khe Sanh", "Huyện Vĩnh Linh"],
                "Sóc Trăng": ["Thành phố Sóc Trăng", "Huyện Cù Lao Dung", "Huyện Kế Sách", "Huyện Long Phú", "Huyện Mỹ Tú", "Huyện Mỹ Xuyên", "Huyện Thạnh Trị", "Huyện Trần Đề", "Huyện Vĩnh Châu"],
                "Sơn La": ["Thành phố Sơn La", "Huyện Bắc Yên", "Huyện Mộc Châu", "Huyện Mai Sơn", "Huyện Phù Yên", "Huyện Quỳnh Nhai", "Huyện Sông Mã", "Huyện Sông Nhi", "Huyện Yên Châu"],
                "Tây Ninh": ["Thành phố Tây Ninh", "Huyện Bến Cầu", "Huyện Bến Thành", "Huyện Dương Minh Châu", "Huyện Gò Dầu", "Huyện Hòa Thành", "Huyện Tân Biên", "Huyện Tân Châu", "Huyện Tân Hưng", "Huyện Trảng Bàng"],
                "Thái Bình": ["Thành phố Thái Bình", "Huyện An Thi", "Huyện Hưng Hà", "Huyện Kiến Xương", "Huyện Quỳnh Phụ", "Huyện Thái Thụy", "Huyện Tiền Hải", "Huyện Vũ Thư"],
                "Thái Nguyên": ["Thành phố Thái Nguyên", "Huyện Đại Từ", "Huyện Đồng Hỷ", "Huyện Định Hóa", "Huyện Phú Lương", "Huyện Phú Bình", "Huyện Võ Nhai"],
                "Thanh Hóa": ["Thành phố Thanh Hóa", "Huyện Bá Thước", "Huyện Cẩm Thủy", "Huyện Đông Sơn", "Huyện Hà Trung", "Huyện Hậu Lộc", "Huyện Hoằng Hóa", "Huyện Lang Chánh", "Huyện Mường Lát", "Huyện Ngọc Lặc", "Huyện Ngọc Sơn", "Huyện Như Thanh", "Huyện Như Xuân", "Huyện Thạch Thành", "Huyện Thiệu Hóa", "Huyện Tĩnh Gia", "Huyện Vĩnh Lộc"],
                "Thừa Thiên Huế": ["Thành phố Huế", "Huyện A Lưới", "Huyện Nam Đông", "Huyện Phong Điền", "Huyện Phú Lộc", "Huyện Phú Vang", "Huyện Quảng Điền"],
                "Tiền Giang": ["Thành phố Mỹ Tho", "Thành phố Gò Công", "Huyện Châu Thành", "Huyện Chợ Gạo", "Huyện Cái Bè", "Huyện Cai Lậy", "Huyện Tân Phước", "Huyện Tân Phú Đông", "Huyện Gò Công Đông", "Huyện Gò Công Tây"],
                "Trà Vinh": ["Thành phố Trà Vinh", "Huyện Cầu Kè", "Huyện Cầu Ngang", "Huyện Châu Thành", "Huyện Duyên Hải", "Huyện Tiểu Cần", "Huyện Trà Cú", "Huyện Trà Vinh"],
                "Tuyên Quang": ["Thành phố Tuyên Quang", "Huyện Chiêm Hóa", "Huyện Hàm Yên", "Huyện Lâm Bình", "Huyện Na Hang", "Huyện Sơn Dương", "Huyện Yên Sơn"],
                "Vĩnh Long": ["Thành phố Vĩnh Long", "Huyện Bình Tân", "Huyện Bình Minh", "Huyện Long Hồ", "Huyện Mang Thít", "Huyện Tam Bình", "Huyện Trà Ôn", "Huyện Vũng Liêm"],
                "Vĩnh Phúc": ["Thành phố Vĩnh Yên", "Huyện Bình Xuyên", "Huyện Lập Thạch", "Huyện Tam Đảo", "Huyện Tam Dương", "Huyện Vĩnh Tường", "Huyện Yên Lạc"],
                "Yên Bái": ["Thành phố Yên Bái", "Huyện Ân Thi", "Huyện Lục Yên", "Huyện Mù Cang Chải", "Huyện Trấn Yên", "Huyện Văn Chấn", "Huyện Văn Yên", "Huyện Yên Bình"]






            };

            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');

            citySelect.addEventListener('change', function() {
                const selectedCity = citySelect.value;
                const districts = districtsByCity[selectedCity] || [];
                
                // Clear previous options
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                
                // Add new options
                districts.forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            });
        });

   
    

document.addEventListener('DOMContentLoaded', function() {
const orderForm = document.getElementById('orderForm');

    if (orderForm) {
        orderForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của biểu mẫu

            // Thu thập dữ liệu từ biểu mẫu
            const formData = new FormData(this);

            // Gửi dữ liệu biểu mẫu bằng AJAX
            fetch('process_order.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đặt hàng thành công!');
                    // Chuyển hướng đến trang cảm ơn hoặc cập nhật giỏ hàng
                    window.location.href = 'home.php'; // Chuyển hướng đến trang cảm ơn
                } else {
                    alert('Lỗi khi đặt hàng: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đặt hàng.');
            });
        });
    }
});

       </script>



<!-- 
  - ionicon link
-->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>
</html>

