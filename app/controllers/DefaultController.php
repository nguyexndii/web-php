<?php
class DefaultController {
    // Tự động chuyển hướng từ trang chủ sang danh sách sản phẩm để tránh lỗi màn hình trống
    public function index() {
        header('Location: /webbanhang/Product');
        exit();
    }
}
?>
