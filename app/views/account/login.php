<?php include 'app/views/shares/header.php'; ?> 

<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header bg-white text-dark text-center py-4 rounded-top border-bottom">
                <h3 class="font-weight-bold mb-0 text-dark">Đăng Nhập</h3>
                <p class="text-muted mb-0 mt-1 small">Vui lòng nhập tài khoản và mật khẩu</p>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger shadow-sm py-2 text-center" role="alert">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_PATH; ?>/account/checklogin" method="post">
                    <div class="form-group">
                        <label for="username" class="font-weight-bold text-secondary">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Nhập username..." required autofocus>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password" class="font-weight-bold text-secondary">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-dark btn-block py-2 font-weight-bold shadow-sm rounded-pill">
                            Đăng nhập
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3 border-top-0 rounded-bottom">
                <span class="text-muted small">Chưa có tài khoản? </span>
                <a href="<?php echo BASE_PATH; ?>/account/register" class="font-weight-bold text-dark small">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
