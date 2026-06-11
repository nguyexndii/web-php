<?php include 'app/views/shares/header.php'; ?>

<?php 
    // Lấy thông tin giỏ hàng từ session để hiển thị tóm tắt trước khi thanh toán
    $cart = $_SESSION['cart'] ?? [];
    $totalPrice = 0;
?>

<h1 class="my-4 text-primary font-weight-bold text-center">Thanh toán đơn hàng</h1>

<div class="row">
    <!-- Cột hiển thị tóm tắt các sản phẩm trong đơn hàng -->
    <div class="col-md-6 mb-4">
        <h3 class="mb-3 text-secondary"> Tóm tắt đơn hàng</h3>
        <ul class="list-group mb-3 shadow-sm">
            <?php foreach ($cart as $id => $item): ?>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0 font-weight-bold"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                        <small class="text-muted">Số lượng: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?> x <?php echo number_format($item['price'], 0, ',', '.'); ?> VND</small>
                    </div>
                    <span class="text-muted font-weight-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</span>
                    <?php 
                        // Tính tổng tiền của đơn hàng
                        $totalPrice += $item['price'] * $item['quantity']; 
                    ?>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between bg-light">
                <span class="font-weight-bold">Tổng cộng:</span>
                <strong class="text-danger h5 mb-0"><?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</strong>
            </li>
        </ul>
        <a href="<?php echo BASE_PATH; ?>/Product/cart" class="btn btn-outline-secondary btn-block shadow-sm mt-3">Quay lại giỏ hàng</a>
    </div>
    
    <!-- Cột hiển thị Form để người dùng nhập thông tin giao nhận hàng -->
    <div class="col-md-6">
        <h3 class="mb-3 text-secondary">Thông tin giao hàng</h3>
        <form method="POST" action="<?php echo BASE_PATH; ?>/Product/processCheckout" class="p-4 border rounded shadow-sm bg-white">
            <div class="form-group">
                <label for="name" class="font-weight-bold">Họ tên người nhận:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập đầy đủ họ tên..." required>
            </div>
            
            <div class="form-group mt-3">
                <label for="phone" class="font-weight-bold">Số điện thoại liên hệ:</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại..." required>
            </div>
            
            <div class="form-group mt-3">
                <label for="address" class="font-weight-bold">Địa chỉ nhận hàng:</label>
                <textarea id="address" name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ giao hàng cụ thể..." required></textarea>
            </div>
            
            <button type="submit" class="btn btn-success btn-block font-weight-bold mt-4 shadow-sm">Xác nhận thanh toán</button>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>