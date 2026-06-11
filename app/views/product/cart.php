<?php include 'app/views/shares/header.php'; ?>

<h1>Giỏ hàng</h1>

<?php if (!empty($cart)): ?>
    <ul class="list-group">
        <?php 
            // Khởi tạo biến lưu tổng tiền giỏ hàng
            $totalPrice = 0; 
        ?>
        <?php foreach ($cart as $id => $item): ?>
            <li class="list-group-item">
                <h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                
                <?php if (!empty($item['image'])): ?>
                    <img src="<?php echo BASE_PATH; ?>/public/images/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" style="max-width: 100px;">
                <?php endif; ?>
                
                <p>Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VND</p>
                <p>Số lượng: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php 
                    // Cộng dồn thành tiền của từng sản phẩm vào tổng tiền giỏ hàng
                    $totalPrice += $item['price'] * $item['quantity']; 
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <!-- Hiển thị tổng tiền giỏ hàng đơn giản, không màu mè -->
    <div class="mt-3">
        <h5>Tổng tiền: <strong><?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</strong></h5>
    </div>
<?php else: ?>
    <p>Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>

<div class="mt-3">
    <a href="<?php echo BASE_PATH; ?>/Product" class="btn btn-secondary mt-2">Tiếp tục mua sắm</a>
    <?php if (!empty($cart)): ?>
        <!-- Chỉ hiển thị nút thanh toán khi giỏ hàng có sản phẩm -->
        <a href="<?php echo BASE_PATH; ?>/Product/checkout" class="btn btn-primary mt-2">Thanh Toán</a>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>