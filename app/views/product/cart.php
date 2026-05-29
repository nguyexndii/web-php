<?php include 'app/views/shares/header.php'; ?>

<h1>Giỏ hàng</h1>

<?php if (!empty($cart)): ?>
    <ul class="list-group">
        <?php foreach ($cart as $id => $item): ?>
            <li class="list-group-item">
                <h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                
                <?php if (!empty($item['image'])): ?>
                    <img src="/webbanhang/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" style="max-width: 100px;">
                <?php endif; ?>
                
                <p>Giá: <?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?> VND</p>
                <p>Số lượng: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>

<div class="mt-3">
    <a href="/webbanhang/Product" class="btn btn-secondary mt-2">Tiếp tục mua sắm</a>
    <a href="/webbanhang/Product/checkout" class="btn btn-primary mt-2">Thanh Toán</a>
</div>

<?php include 'app/views/shares/footer.php'; ?>