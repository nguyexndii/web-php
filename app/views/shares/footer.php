    </div> <!-- Đóng thẻ div.container đã mở từ header.php -->

    <!-- Chân trang footer -->
    <footer class="bg-light text-center text-lg-start mt-4">
        <div class="container p-4">
            <div class="row">
                <!-- Cột thông tin liên hệ -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <h5 class="text-uppercase">Quản lý sản phẩm</h5>
                    <p>
                        Hệ thống quản lý sản phẩm giúp bạn theo dõi và cập nhật thông tin sản phẩm dễ dàng.
                      </p>
                </div>
                <!-- Cột liên kết nhanh -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Liên kết nhanh</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="<?php echo BASE_PATH; ?>/Product/" class="text-dark">Danh sách sản phẩm</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/Product/add" class="text-dark">Thêm sản phẩm</a></li>
                    </ul>
                </div>
                <!-- Cột mạng xã hội -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Kết nối với chúng tôi</h5>
                    <a href="#" class="text-dark mr-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-dark mr-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-dark mr-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <!-- Dòng bản quyền -->
        <div class="text-center p-3 bg-dark text-white">
            © 2026 Quản lý sản phẩm. All rights reserved.
        </div>
    </footer>

    <!-- Các thư viện JavaScript hỗ trợ (jQuery, Popper, Bootstrap JS) đặt ở cuối trang để tối ưu tải trang -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
