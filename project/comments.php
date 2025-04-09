<?php
require '../vendor/autoload.php';

// Sử dụng tên lớp đầy đủ để tránh xung đột
use MongoDB\Client as MongoDBClient;
use MongoDB\BSON\UTCDateTime; // Khai báo đúng namespace

// Khởi tạo client MongoDB
$client = new MongoDBClient("mongodb://localhost:27017");
$collection = $client->quanlibanhang->comments;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $productID = $_POST['product_id'];

    if (!empty($comment) && !empty($productID)) {
        $collection->insertOne([
            'comment' => htmlspecialchars($comment),
            'product_id' => htmlspecialchars($productID),
            'created_at' => new UTCDateTime() // Sử dụng đúng class
        ]);

        // Quay lại trang sản phẩm sau khi gửi bình luận
        header("Location: products_details.php?MaHang=" . urlencode($productID));
        exit();
    } else {
        $error = "Bình luận hoặc mã hàng không được để trống.";
    }
}
?>
<div class="comments-section">
    <h2>Bình luận</h2>
    <form method="post" action="">
        <textarea name="comment" rows="4" cols="50" required></textarea><br>
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['MaHang']); ?>">
        <input type="submit" value="Gửi bình luận">
    </form>
    <ul>
        <?php
        if (isset($error)) {
            echo '<p style="color:red;">' . $error . '</p>';
        }

        $comments = $collection->find(['product_id' => $product['MaHang']], ['sort' => ['created_at' => -1]]);
        foreach ($comments as $comment) {
            echo '<li>' . htmlspecialchars($comment['comment']) . ' (vào lúc ' . $comment['created_at']->toDateTime()->format('Y-m-d H:i:s') . ')</li>';
        }
        ?>
    </ul>
</div>

    </main>