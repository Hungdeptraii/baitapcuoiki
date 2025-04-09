<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style3.css">
    <title>ADMIN | NgocHung</title>
</head>
<link rel="shortcut icon" href="favicon.ico" type="image/svg+xml">
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <i class='bx bxl-github' ></i>
            <div class="logo-name"><span>Ngoc</span>Hung</div>
        </a>
        <ul class="side-menu">
            <li><a href="?page=dashboard" class="active"><i class='bx bxl-postgresql' ></i>Dashboard</a></li>
            <li><a href="?page=shop"><i class='bx bx-store-alt'></i>Products</a></li>
            <li><a href="?page=usersearch"><i class='bx bx-search-alt-2' ></i>Customers Search</a></li>
            <li><a href="?page=tickets"><i class='bx bx-cart' ></i></i>Orders</a></li>
            <li><a href="?page=top"><i class='bx bxl-ok-ru' ></i>Best & Unsold</a></li>
            <li><a href="?page=users"><i class='bx bx-group'></i>Customers</a></li>
            <li><a href="?page=returns"><i class='bx bx-repeat'></i>Returns</a></li>
            <li><a href="?page=settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="../project/dangnhap.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile">
                <img src="a1d8e1ec-bd79-4096-9206-c1be69c7bdf9.jpg">
            </a>
        </nav>
        <!-- End of Navbar -->

        <main>
            <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                switch ($page) {
                    case 'usersearch':
                        include 'usersearch.php';  // Ensure this file exists and is correctly named
                        break;
                    case 'shop':
                        include 'shop.php';
                        break;
                    case 'dashboard':// Include the form filter
                        include 'dashboard.php';
                        break;
                    case 'tickets':
                        include 'donhang.php';
                        break;
                    case 'users':
                        include 'users.php';
                        break;
                    case 'top':
                        include 'best&unsold.php';
                        break;
                    case 'returns':
                        include 'returns.php';
                        break;
                    case 'settings':
                        include 'pages/settings.php';
                        break;
                    default:
                        include 'usersearch.php';
                }
            ?>
        </main>

    </div>

    <script src="../assest/js/script2.js"></script>
</body>

</html>
