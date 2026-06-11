<?php include 'app/views/shares/header.php';?>

<div class="text-center my-5">
    <div class="display-1 text-success"></div>
    <h2 class="font-weight-bold text-success mt-3">Xác nhận đặt hàng thành công!</h2>
    <p class="lead text-muted">Cảm ơn bạn đã mua hàng</p>
    
    <?php if (isset($orderId) && $orderTotal > 0): ?>
        <?php 
            // Thông tin tài khoản ngân hàng nhận tiền VietQR
            $bankId = "MB"; // Ngân hàng Quân Đội (MB Bank)
            $accountNo = "0123456789"; // Số tài khoản demo
            $accountName = "NGUYEN DANG DANG DUY"; // Tên chủ tài khoản viết hoa không dấu
            $orderMessage = "Thanh toan don hang " . $orderId; // Nội dung chuyển tiền tự động
            
            // Xây dựng URL hình ảnh QR Code theo chuẩn VietQR.io (sử dụng template compact)
            $qrUrl = "https://img.vietqr.io/image/" . $bankId . "-" . $accountNo . "-compact.png"
                     . "?amount=" . urlencode($orderTotal)
                     . "&addInfo=" . urlencode($orderMessage)
                     . "&accountName=" . urlencode($accountName);
        ?>
        
        <!-- Khối hiển thị quét mã QR thanh toán nhanh VietQR -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border rounded p-4 bg-white">
                    <h4 class="text-primary font-weight-bold mb-3"><i class="fas fa-qrcode"></i> Chuyển khoản ngân hàng VietQR</h4>
                    <p class="text-muted small">Quét mã QR dưới đây bằng ứng dụng Ngân hàng (Mobile Banking) để thanh toán nhanh:</p>
                    
                    <div class="my-3">
                        <img src="<?php echo $qrUrl; ?>" alt="Mã QR VietQR" class="img-fluid border rounded shadow-sm" style="max-width: 280px;">
                    </div>
                    
                    <div class="text-left bg-light p-3 rounded border">
                        <p class="mb-1 text-secondary"><strong>Ngân hàng:</strong> <?php echo $bankId; ?> (MB Bank)</p>
                        <p class="mb-1 text-secondary"><strong>Số tài khoản:</strong> <span class="text-dark font-weight-bold"><?php echo $accountNo; ?></span></p>
                        <p class="mb-1 text-secondary"><strong>Chủ tài khoản:</strong> <span class="text-dark font-weight-bold"><?php echo $accountName; ?></span></p>
                        <p class="mb-1 text-secondary"><strong>Nội dung chuyển khoản:</strong> <code class="text-dark bg-warning-light px-1 rounded"><?php echo $orderMessage; ?></code></p>
                        <p class="mb-0 text-secondary"><strong>Số tiền:</strong> <span class="text-danger font-weight-bold"><?php echo number_format($orderTotal, 0, ',', '.'); ?> VND</span></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?php echo BASE_PATH; ?>/Product" class="btn btn-primary font-weight-bold shadow-sm px-4 py-2">Tiếp tục mua hàng</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php';?>