<?php
// Include file config.php which contains database connection
include '../php/config.php';

// Determine current page and set the number of products per page
$productsPerPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

// Get the category filter if available
$category = isset($_GET['category']) ? $con->real_escape_string($_GET['category']) : 'all';

// SQL query to fetch total number of products based on category
$totalProductsQuery = "SELECT COUNT(*) AS total FROM products";
if ($category !== 'all') {
    $totalProductsQuery .= " WHERE tendanhmuc = '$category'";
}
$totalResult = $con->query($totalProductsQuery);
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

// SQL query to fetch products from database based on category and pagination
$sql = "SELECT MaHang, TenSanPham, GiaNhap, GiamGia, SoLuongNhap, HinhAnh, tendanhmuc 
        FROM products";
if ($category !== 'all') {
    $sql .= " WHERE tendanhmuc = '$category'";
}
$sql .= " LIMIT $offset, $productsPerPage";

$result = $con->query($sql);

if ($result === false) {
    die("Query error: " . $con->error);
}

// Array to store products
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Close connection
$con->close();

// Return products and pagination info as JSON
echo json_encode([
    'products' => $products,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);
?>
