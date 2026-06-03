<?php include 'app/views/shares/header.php'; ?>

<h1>Thanh toán</h1>

<form method="POST" action="/webbanhang/Product/processCheckout">
    <div class="form-group">
        <label for="name">Họ tên:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    
    <div class="form-group mt-3">
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" class="form-control" required>
    </div>
    
    <div class="form-group mt-3">
        <label for="address">Địa chỉ:</label>
        <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary mt-3">Thanh toán</button>
</form>

<a href="/webbanhang/Product/cart" class="btn btn-secondary mt-3">Quay lại giỏ hàng</a>

<?php include 'app/views/shares/footer.php'; ?>